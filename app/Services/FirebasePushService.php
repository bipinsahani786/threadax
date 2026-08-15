<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebasePushService
{
    private string $projectId;
    private ?string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id') ?: env('FIREBASE_PROJECT_ID', 'threadax');
        $this->credentialsPath = config('services.firebase.credentials') ?: storage_path('app/firebase-credentials.json');
    }

    /**
     * Check if Firebase FCM credentials exist
     */
    public function isConfigured(): bool
    {
        if ($this->credentialsPath && file_exists($this->credentialsPath)) {
            return true;
        }

        return !empty(config('services.firebase.server_key')) || !empty(env('FCM_SERVER_KEY'));
    }

    /**
     * Send push notification to a single device token using Google FCM HTTP v1
     */
    public function sendToToken(string $token, string $title, string $body, ?string $link = null, ?string $icon = null, ?string $image = null): bool
    {
        return $this->sendV1Message($token, $title, $body, $link, $icon, $image);
    }

    /**
     * Send push notification to all devices registered by a specific User
     */
    public function sendToUser(User $user, string $title, string $body, ?string $link = null, ?string $icon = null, ?string $image = null): int
    {
        $tokens = $user->deviceTokens()->pluck('token')->filter()->toArray();

        if (empty($tokens)) {
            Log::info("No device tokens registered for user #{$user->id} ({$user->name})");
            return 0;
        }

        $this->broadcast($tokens, $title, $body, $link, $icon, $image);
        return count($tokens);
    }

    /**
     * Broadcast push notification to multiple device tokens
     */
    public function broadcast(array $tokens, string $title, string $body, ?string $link = null, ?string $icon = null, ?string $image = null): bool
    {
        $tokens = array_values(array_unique(array_filter($tokens)));

        if (empty($tokens)) {
            return false;
        }

        if (!$this->isConfigured()) {
            Log::info("Firebase FCM push notification skipped (No credentials configured): '{$title}' to " . count($tokens) . " devices.");
            return true;
        }

        $successCount = 0;
        foreach ($tokens as $token) {
            if ($this->sendV1Message($token, $title, $body, $link, $icon, $image)) {
                $successCount++;
            }
        }

        Log::info("FCM HTTP v1 broadcast completed: {$successCount}/" . count($tokens) . " delivered successfully.");
        return $successCount > 0;
    }

    /**
     * Send message via modern Google Firebase Cloud Messaging HTTP v1 API
     */
    private function sendV1Message(string $token, string $title, string $body, ?string $link = null, ?string $icon = null, ?string $image = null): bool
    {
        $accessToken = $this->getOAuth2AccessToken();

        if (!$accessToken) {
            Log::error("Failed to generate Google OAuth2 Access Token for FCM HTTP v1");
            return false;
        }

        $iconUrl = $icon ?: asset('images/logo.png');
        $clickAction = $link ?: url('/');

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => [
                    'title'        => $title,
                    'body'         => $body,
                    'link'         => $clickAction,
                    'click_action' => $clickAction,
                    'icon'         => $iconUrl,
                ],
                'webpush' => [
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => $iconUrl,
                        'badge' => asset('favicon.ico'),
                    ],
                    'fcm_options' => [
                        'link' => $clickAction,
                    ],
                ],
            ],
        ];

        if ($image) {
            $payload['message']['notification']['image'] = $image;
            $payload['message']['webpush']['notification']['image'] = $image;
        }

        try {
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json; UTF-8',
            ])->timeout(10)->post($url, $payload);

            if ($response->successful()) {
                return true;
            }

            $errorData = $response->json();
            $errorCode = $errorData['error']['status'] ?? '';

            // Clean up invalid or expired tokens
            if (in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT', 'NOT_FOUND'])) {
                DeviceToken::where('token', $token)->delete();
                Log::info("Removed invalid/unregistered FCM token from DB: " . substr($token, 0, 15) . '...');
            } else {
                Log::warning("FCM v1 send failed [{$response->status()}]: " . $response->body());
            }

            return false;
        } catch (\Exception $e) {
            Log::error("FCM v1 network exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate / Retrieve cached Google OAuth2 Access Token using Service Account JWT
     */
    private function getOAuth2AccessToken(): ?string
    {
        return Cache::remember('fcm_v1_oauth_token', 3000, function () {
            if (!$this->credentialsPath || !file_exists($this->credentialsPath)) {
                return null;
            }

            $json = json_decode(file_get_contents($this->credentialsPath), true);
            if (!$json || empty($json['client_email']) || empty($json['private_key'])) {
                return null;
            }

            $clientEmail = $json['client_email'];
            $privateKey = $json['private_key'];
            $tokenUri = $json['token_uri'] ?? 'https://oauth2.googleapis.com/token';

            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $claims = [
                'iss'   => $clientEmail,
                'sub'   => $clientEmail,
                'aud'   => $tokenUri,
                'iat'   => $now,
                'exp'   => $now + 3600,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            ];

            $encodedHeader = $this->base64UrlEncode(json_encode($header));
            $encodedClaims = $this->base64UrlEncode(json_encode($claims));
            $signatureInput = $encodedHeader . '.' . $encodedClaims;

            $signature = '';
            if (!openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                Log::error("Failed to sign Google JWT with private key");
                return null;
            }

            $jwt = $signatureInput . '.' . $this->base64UrlEncode($signature);

            $response = Http::withoutVerifying()->asForm()->timeout(10)->post($tokenUri, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::error("OAuth2 Token generation failed: " . $response->body());
            return null;
        });
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
