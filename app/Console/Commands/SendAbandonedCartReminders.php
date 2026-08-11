<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Mail\AbandonedCartReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-abandoned-cart-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users with abandoned carts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find carts that belong to a user, have items, and haven't been updated in 24 hours
        // We also want to make sure we don't spam, so maybe limit to ones updated between 24 and 48 hours ago
        $carts = Cart::whereNotNull('user_id')
            ->whereHas('items')
            ->where('updated_at', '<=', Carbon::now()->subHours(24))
            ->where('updated_at', '>', Carbon::now()->subHours(48))
            ->with(['user', 'items.variant.product'])
            ->get();

        $count = 0;
        foreach ($carts as $cart) {
            if ($cart->user) {
                Mail::to($cart->user->email)->send(new AbandonedCartReminder($cart));
                $count++;
            }
        }

        $this->info("Sent {$count} abandoned cart reminders.");
    }
}
