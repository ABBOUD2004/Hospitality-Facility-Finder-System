<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // User & Facility (user_id nullable for guest orders)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');

            // Booking Info
            $table->string('booking_type')->default('restaurant'); // restaurant, hotel, transport, coffee
            $table->string('booking_reference')->unique();

            // Guest Info
            $table->string('guest_name')->nullable();
            $table->string('guest_firstname')->nullable();
            $table->string('guest_lastname')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();

            // Hotel Dates
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->date('checkin_date')->nullable();
            $table->date('checkout_date')->nullable();
            $table->integer('nights')->nullable();

            // Restaurant/Transport Dates
            $table->date('reservation_date')->nullable();
            $table->time('reservation_time')->nullable();
            $table->timestamp('order_date')->nullable();

            // Guests
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->integer('number_of_guests')->nullable();

            // Transport
            $table->string('pickup_location')->nullable();
            $table->string('destination')->nullable();

            // Restaurant Orders
            $table->json('order_items')->nullable();
            $table->text('special_requests')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('delivery_address')->nullable();

            // Pricing
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('total_price_rwf', 10, 2)->nullable();
            $table->decimal('total_price_usd', 10, 2)->nullable();

            // Payment
            $table->string('payment_method')->nullable();
            $table->string('payment_phone')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_paid', 'processing'])->default('pending');

            // Status
            $table->enum('status', [
                'pending',
                'confirmed',
                'in_progress',
                'ready',
                'completed',
                'cancelled',
                'checked_in',
                'checked_out'
            ])->default('pending');

            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Admin
            $table->text('admin_notes')->nullable();

            // Reviews
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();

            // Cancellation
            $table->text('cancelled_reason')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_status')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('booking_reference');
            $table->index('booking_type');
            $table->index('status');
            $table->index('payment_status');
            $table->index('user_id');
            $table->index('facility_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
