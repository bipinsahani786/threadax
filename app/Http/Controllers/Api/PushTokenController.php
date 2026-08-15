<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushTokenController extends Controller
{
    /**
     * Store or update device push token
     */
    public function store(Request $request)
    {
        $request->validate([
            'token'       => 'required|string',
            'device_type' => 'nullable|string|in:web,android,ios',
            'browser'     => 'nullable|string|max:50',
        ]);

        $userId = Auth::id(); // null if guest visitor

        $deviceToken = DeviceToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id'     => $userId,
                'device_type' => $request->device_type ?? 'web',
                'browser'     => $request->browser ?? $request->header('User-Agent'),
                'ip_address'  => $request->ip(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device push token registered successfully.',
            'id'      => $deviceToken->id,
        ]);
    }

    /**
     * Remove / revoke device push token on logout or opt-out
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        DeviceToken::where('token', $request->token)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device push token revoked.',
        ]);
    }
}
