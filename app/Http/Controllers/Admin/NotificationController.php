<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use App\Notifications\OfferNotification;
use App\Services\FirebasePushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct(
        private FirebasePushService $firebasePushService
    ) {}

    public function create()
    {
        $totalCustomers = User::count();
        $buyersCount = User::has('orders')->count();
        $repeatCount = User::has('orders', '>=', 2)->count();
        $deviceTokensCount = DeviceToken::count();
        $mobileCount = DeviceToken::whereIn('device_type', ['android', 'ios'])->count();
        $desktopCount = DeviceToken::where('device_type', 'web')->count();

        $stats = [
            'total'          => $totalCustomers,
            'buyers'         => $buyersCount,
            'repeat'         => $repeatCount,
            'push_devices'   => $deviceTokensCount,
            'mobile_devices' => $mobileCount,
            'desktop_devices'=> $desktopCount,
            'fcm_configured' => $this->firebasePushService->isConfigured(),
        ];

        // Fetch paginated subscribed devices
        $subscribedDevices = DeviceToken::with('user')
            ->latest('updated_at')
            ->paginate(15, ['*'], 'devices_page');

        // Fetch recent broadcasts from database notifications
        $recentBroadcasts = DB::table('notifications')
            ->where('type', OfferNotification::class)
            ->select('data', 'created_at', DB::raw('count(*) as recipients_count'))
            ->groupBy('data', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->payload = json_decode($item->data, true);
                return $item;
            });

        return view('admin.pages.notifications.create', compact('stats', 'subscribedDevices', 'recentBroadcasts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'link'    => 'nullable|string|max:500',
            'target'  => 'required|in:all_users,buyers,repeat',
        ]);

        $target = $request->input('target', 'all_users');
        $title = $request->input('title');
        $message = $request->input('message');
        $link = $request->input('link') ?: url('/');

        if ($target === 'buyers') {
            $users = User::has('orders')->get();
            $targetLabel = 'Active Buyers';
            $deviceTokens = DeviceToken::whereIn('user_id', $users->pluck('id'))->pluck('token')->toArray();
        } elseif ($target === 'repeat') {
            $users = User::has('orders', '>=', 2)->get();
            $targetLabel = 'VIP Repeat Customers';
            $deviceTokens = DeviceToken::whereIn('user_id', $users->pluck('id'))->pluck('token')->toArray();
        } else {
            $users = User::all();
            $targetLabel = 'All Registered Customers';
            $deviceTokens = DeviceToken::pluck('token')->toArray();
        }

        // 1. Dispatch in-app Database notifications
        foreach ($users as $user) {
            $user->notify(new OfferNotification($title, $message, $link));
        }

        // 2. Dispatch Mobile Lockscreen Web Push via Firebase FCM
        if (!empty($deviceTokens)) {
            $this->firebasePushService->broadcast($deviceTokens, $title, $message, $link);
            Log::info("Firebase FCM push broadcast triggered for " . count($deviceTokens) . " devices.");
        }

        $msg = 'Push notification broadcast successfully delivered to ' . $users->count() . ' customers (' . $targetLabel . ')';
        if (!empty($deviceTokens)) {
            $msg .= ' and ' . count($deviceTokens) . ' subscribed mobile/desktop devices.';
        } else {
            $msg .= '.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Send test push alert to a specific device/phone
     */
    public function testDevice(DeviceToken $deviceToken)
    {
        if (!$this->firebasePushService->isConfigured()) {
            return back()->with('error', 'Firebase FCM is not configured in .env yet. Please configure FCM_SERVER_KEY or upload firebase-credentials.json.');
        }

        $sent = $this->firebasePushService->sendToToken(
            $deviceToken->token,
            '🔥 ThreadAX VIP Drop Alert',
            'Push notification test successful on your ' . $deviceToken->device_name . '!',
            url('/')
        );

        if ($sent) {
            return back()->with('success', 'Test notification sent to ' . $deviceToken->device_name . ' (' . $deviceToken->browser_name . ')');
        }

        return back()->with('error', 'Failed to send to ' . $deviceToken->device_name . '. Token may be expired or FCM rejected the request.');
    }

    /**
     * Remove / unsubscribe a device
     */
    public function destroyDevice(DeviceToken $deviceToken)
    {
        $name = $deviceToken->device_name;
        $deviceToken->delete();
        return back()->with('success', 'Device (' . $name . ') removed from notification subscriber list.');
    }
}
