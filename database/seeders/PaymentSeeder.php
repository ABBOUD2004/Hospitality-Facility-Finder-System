<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        $users = User::all();

        if ($bookings->isEmpty()) {
            $this->command->warn('No bookings found. Please seed bookings first.');
            return;
        }

        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        $paymentMethods = ['credit_card', 'debit_card', 'mobile_wallet', 'cash', 'bank_transfer'];

        foreach ($bookings->take(20) as $booking) {
            $status = $statuses[array_rand($statuses)];

            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->total_price ?? rand(100, 5000),
                'status' => $status,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'currency' => 'USD',
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
                'paid_at' => $status === 'completed' ? now()->subDays(rand(1, 30)) : null,
                'failed_at' => $status === 'failed' ? now()->subDays(rand(1, 10)) : null,
                'refunded_at' => $status === 'refunded' ? now()->subDays(rand(1, 5)) : null,
            ]);
        }

        $this->command->info('Payments seeded successfully!');
    }
}
