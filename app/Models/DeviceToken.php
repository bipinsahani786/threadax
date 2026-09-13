<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_type',
        'browser',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDeviceNameAttribute(): string
    {
        $ua = $this->browser ?? '';

        if (preg_match('/(SM-[A-Z0-9]+)/i', $ua, $m)) return "Samsung Galaxy ({$m[1]})";
        if (stripos($ua, 'SamsungBrowser') !== false) return "Samsung Galaxy";
        if (preg_match('/(Pixel [0-9a-zA-Z ]+)/i', $ua, $m)) return "Google " . $m[1];
        if (preg_match('/(OnePlus[A-Z0-9_ ]*|CPH[0-9]{4}|IN[0-9]{4})/i', $ua, $m)) return "OnePlus Phone";
        if (preg_match('/(Redmi[A-Z0-9_ ]*|POCO[A-Z0-9_ ]*|2[0-9]{3}[A-Z0-9]+)/i', $ua, $m)) return "Xiaomi / Redmi Phone";
        if (stripos($ua, 'Vivo') !== false || stripos($ua, 'V2') !== false) return "Vivo Phone";
        if (stripos($ua, 'Oppo') !== false) return "Oppo Phone";
        if (stripos($ua, 'Realme') !== false || stripos($ua, 'RMX') !== false) return "Realme Phone";

        if (stripos($ua, 'iPhone') !== false) return "Apple iPhone";
        if (stripos($ua, 'iPad') !== false) return "Apple iPad";
        if (stripos($ua, 'Android') !== false) return "Android Phone";
        if (stripos($ua, 'Windows') !== false) return "Windows PC";
        if (stripos($ua, 'Macintosh') !== false) return "MacBook / Mac";
        if (stripos($ua, 'Linux') !== false) return "Linux PC";

        return ucfirst($this->device_type ?? 'Web Device');
    }

    public function getBrowserNameAttribute(): string
    {
        $ua = $this->browser ?? '';

        if (stripos($ua, 'Edg/') !== false) return 'Edge';
        if (stripos($ua, 'SamsungBrowser/') !== false) return 'Samsung Internet';
        if (stripos($ua, 'Chrome/') !== false && stripos($ua, 'Mobile') !== false) return 'Chrome Mobile';
        if (stripos($ua, 'Chrome/') !== false) return 'Google Chrome';
        if (stripos($ua, 'Safari/') !== false && stripos($ua, 'Mobile') !== false) return 'Safari Mobile';
        if (stripos($ua, 'Safari/') !== false) return 'Apple Safari';
        if (stripos($ua, 'Firefox/') !== false) return 'Firefox';

        return 'Browser';
    }
}
