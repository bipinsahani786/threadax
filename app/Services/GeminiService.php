<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeminiService
{
    protected string $geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models';

    /**
     * Get the active Gemini API key from database settings or .env
     */
    public function getApiKey(): ?string
    {
        $key = Setting::where('key', 'gemini_api_key')->value('value');
        if (!empty($key)) {
            return trim($key);
        }

        $configKey = config('services.gemini.api_key');
        if (!empty($configKey)) {
            return trim($configKey);
        }

        return env('GEMINI_API_KEY');
    }

    /**
     * Get configured Gemini text model (default: gemini-1.5-flash)
     */
    public function getModel(): string
    {
        $model = Setting::where('key', 'gemini_model')->value('value');
        return !empty($model) ? trim($model) : 'gemini-flash-lite-latest';
    }

    /**
     * Check if Gemini API key is configured
     */
    public function hasApiKey(): bool
    {
        return !empty($this->getApiKey());
    }

    /**
     * Generate structured product copywriting (short description, rich HTML description, SEO meta)
     * using Gemini 1.5 Flash.
     */
    public function generateProductContent(
        string $name,
        ?string $roughNotes = null,
        ?string $category = null,
        ?string $tone = 'streetwear_bold'
    ): array {
        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            throw new \Exception('Gemini API key is not configured. Please add your key in Store Settings.');
        }

        $toneDescriptions = [
            'streetwear_bold' => 'Bold, confident, energetic streetwear vibe tailored for young Indian Gen Z & Millennials.',
            'minimal_luxury'  => 'Clean, minimalist, quiet luxury and modern streetwear tone.',
            'casual_everyday' => 'Friendly, relatable, comfortable everyday urban wear tone.',
        ];
        $selectedTone = $toneDescriptions[$tone] ?? $toneDescriptions['streetwear_bold'];

        $prompt = <<<PROMPT
You are a premier fashion copywriter and e-commerce SEO specialist for ThreadAX (https://threadax.co.in), a top-tier Indian streetwear apparel brand known for premium heavy-GSM cotton fabrics, oversized drop-shoulder fits, and bold visual storytelling.

Generate high-converting, professional product copy for:
- Product Title: {$name}
- Category: {$category}
- Creator's Rough Notes / Key Highlights: {$roughNotes}
- Tone / Aesthetic: {$selectedTone}

You MUST return a strictly valid JSON object with the following exact keys:
{
  "short_description": "A punchy, catchy 1-2 sentence subtitle/hook (under 160 characters) highlighting the streetwear vibe and premium feel. Example: 'Ignite your motion, own your space. Heavyweight 240 GSM drop-shoulder cut with bold graphic prints.'",
  "description": "Rich, beautifully structured HTML content (do NOT wrap in <html> or <body> tags, only use clean semantic HTML tags: <h2>, <h3>, <p>, <ul>, <li>, <strong>, <blockquote>). Ensure the content is detailed and well-spaced:
    - An opening story paragraph introducing the streetwear silhouette, attitude, and visual appeal.
    - <h2>Design & Fit Details</h2> with <ul><li> detailing the oversized/relaxed silhouette, drop shoulder, ribbed collar, and stitching durability.
    - <h2>Fabric & Sizing Reference</h2> with <ul><li> detailing premium fabric (e.g. 240+ GSM), 100% super-combed cotton, bio-washed, pre-shrunk, breathable comfort.
    - <h2>Wash Care & Maintenance</h2> with <ul><li> (machine wash cold, wash & dry inside out, do not iron directly on prints).",
  "meta_title": "SEO title under 60 characters with brand name, e.g., '{$name} | ThreadAX Streetwear'",
  "meta_description": "Compelling SEO meta description under 155 characters for high Google search CTR."
}
PROMPT;

        $candidateModels = array_unique(array_filter([
            'gemini-flash-lite-latest',
            'gemini-flash-latest',
            $this->getModel(),
            'gemini-3.5-flash-lite',
            'gemini-3.5-flash',
            'gemini-2.5-flash-lite',
            'gemini-2.5-flash',
        ]));

        $lastError = 'Unknown Gemini API Error';
        $response = null;

        foreach ($candidateModels as $candidateModel) {
            $url = "{$this->geminiEndpoint}/{$candidateModel}:generateContent?key={$apiKey}";

            for ($attempt = 1; $attempt <= 2; $attempt++) {
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'X-goog-api-key' => $apiKey,
                        'Content-Type'   => 'application/json',
                    ])
                    ->timeout(45)
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'responseMimeType' => 'application/json'
                        ]
                    ]);

                if ($response->successful()) {
                    break 2;
                }

                $status = $response->status();
                $lastError = $response->json('error.message') ?? $response->body();

                // If high demand spike (503/429), wait 1s and retry once
                if (($status === 503 || $status === 429) && $attempt === 1) {
                    sleep(1);
                    continue;
                }

                // If model not found, rate limited or high demand, try next candidate model
                if (in_array($status, [404, 429, 503, 500])) {
                    break;
                } else {
                    break 2;
                }
            }
        }

        if (!$response || !$response->successful()) {
            Log::error('Gemini API Error:', ['status' => $response ? $response->status() : 500, 'body' => $lastError]);
            throw new \Exception("Gemini API Error: {$lastError}");
        }

        $responseData = $response->json();
        $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (empty($rawText)) {
            throw new \Exception('No content generated by Gemini.');
        }

        // Clean any accidental markdown fence if present
        $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawText));
        $parsed = json_decode($cleanJson, true);

        if (!is_array($parsed)) {
            throw new \Exception('Failed to parse Gemini response as JSON.');
        }

        return [
            'short_description' => $parsed['short_description'] ?? '',
            'description'       => $parsed['description'] ?? '',
            'meta_title'        => $parsed['meta_title'] ?? '',
            'meta_description'  => $parsed['meta_description'] ?? '',
        ];
    }

    /**
     * Generate an on-model streetwear photoshoot image using Imagen 3 or Gemini multimodal prompt.
     *
     * @param string $productName
     * @param string|null $roughNotes
     * @param string|null $referenceImageBase64 Data URI or raw base64 of uploaded product image
     * @param string|null $modelStyle
     * @return array ['url' => string, 'relative_path' => string, 'prompt_used' => string]
     */
    public function generateModelPhotoshoot(
        string $productName,
        ?string $roughNotes = null,
        ?string $referenceImageBase64 = null,
        ?string $modelStyle = 'male_streetwear'
    ): array {
        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            throw new \Exception('Gemini API key is not configured. Please add your key in Store Settings.');
        }

        // Step 1: If reference image is provided, use Gemini 1.5 Flash Vision to analyze the apparel
        $apparelDetails = $productName . ' ' . ($roughNotes ?? '');
        if (!empty($referenceImageBase64)) {
            try {
                $apparelDetails = $this->analyzeProductImage($referenceImageBase64, $productName, $apiKey);
            } catch (\Throwable $e) {
                Log::warning('Gemini Vision analysis failed, falling back to text prompt: ' . $e->getMessage());
            }
        }

        // Step 2: Build detailed photorealistic prompt for Imagen 3
        $modelStyles = [
            'male_streetwear'   => 'handsome young Indian male fashion model with stylish haircut, confident streetwear pose',
            'female_streetwear' => 'stylish young Indian female fashion model with trendy streetwear aesthetic, confident gaze',
            'urban_night'       => 'fashion model standing on a neon-lit cyberpunk Tokyo/Seoul urban street at night with wet asphalt reflection',
            'studio_minimal'    => 'fashion model posing in a minimalist concrete architectural studio with warm golden hour cinematic lighting',
        ];
        $selectedSubject = $modelStyles[$modelStyle] ?? $modelStyles['male_streetwear'];

        $imagePrompt = "Editorial fashion magazine photoshoot of a {$selectedSubject}, wearing {$apparelDetails}. The apparel is clearly visible, premium streetwear clothing, 100% natural folds and texture, realistic lighting, 8k resolution, highly detailed, photorealistic, sharp focus, cinematic color grading, 35mm lens.";

        // Step 3: Try Google Imagen 3 first, then fallback to free high-res Flux engine
        $imageBinary = null;

        try {
            $imagenUrl = "{$this->geminiEndpoint}/imagen-3.0-generate-002:predict?key={$apiKey}";
            $googleResponse = Http::withoutVerifying()->timeout(20)->post($imagenUrl, [
                'instances' => [['prompt' => $imagePrompt]],
                'parameters' => [
                    'sampleCount' => 1,
                    'aspectRatio' => '3:4',
                    'personGeneration' => 'ALLOW_ADULT'
                ]
            ]);

            if ($googleResponse->successful()) {
                $base64Image = $googleResponse->json('predictions.0.bytesBase64Encoded');
                if (!empty($base64Image)) {
                    $imageBinary = base64_decode($base64Image);
                }
            }
        } catch (\Throwable $e) {
            Log::info('Google Imagen not available on free tier, using Flux fallback: ' . $e->getMessage());
        }

        // High-res photorealistic Flux engine (100% Free, no billing required)
        if (empty($imageBinary)) {
            $encodedPrompt = urlencode($imagePrompt);
            $fluxUrl = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width=768&height=1024&nologo=true&seed=" . rand(1000, 999999);

            $fluxResponse = Http::withoutVerifying()->timeout(60)->get($fluxUrl);

            if ($fluxResponse->successful() && str_contains($fluxResponse->header('Content-Type') ?? '', 'image')) {
                $imageBinary = $fluxResponse->body();
            }
        }

        if (empty($imageBinary)) {
            throw new \Exception('Failed to generate photoshoot image. Please try again.');
        }

        // Step 4: Save image to public storage
        $filename = 'products/ai-model-' . Str::random(12) . '-' . time() . '.jpg';
        Storage::disk('public')->put($filename, $imageBinary);

        return [
            'url'           => asset('storage/' . $filename),
            'relative_path' => $filename,
            'prompt_used'   => $imagePrompt
        ];
    }

    /**
     * Inspect an uploaded product picture using Gemini Multimodal Vision
     */
    protected function analyzeProductImage(string $imageBase64, string $productName, string $apiKey): string
    {
        $mimeType = 'image/jpeg';
        $cleanData = $imageBase64;

        if (preg_match('/^data:(image\/[a-zA-Z0-9\+\-\.]+);base64,(.+)$/', $imageBase64, $matches)) {
            $mimeType = $matches[1];
            $cleanData = $matches[2];
        }

        $url = "{$this->geminiEndpoint}/gemini-1.5-flash:generateContent?key={$apiKey}";

        $response = Http::withoutVerifying()->timeout(30)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Analyze this product picture for '{$productName}'. In 2 concise sentences, describe the exact garment type, primary base color, secondary colors, any artwork, text, print placement (front/back), and collar style so it can be accurately worn by a model in an image generation prompt."
                        ],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data'     => $cleanData
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $text = $response->json('candidates.0.content.parts.0.text');
            if (!empty($text)) {
                return trim($text);
            }
        }

        return $productName;
    }
}
