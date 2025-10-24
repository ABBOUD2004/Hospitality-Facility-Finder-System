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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');

            // Customer Information
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('phone');

            // Trip Details
            $table->string('pickup_location');
            $table->string('drop_location');
            $table->date('pickup_date');
            $table->time('pickup_time');

            // Vehicle Information
            $table->enum('vehicle_type', ['car', 'van', 'truck', 'bus'])->default('car');
            $table->integer('passengers')->nullable();
            $table->string('distance')->nullable();

            // Pricing & Status
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');

            // Additional Information
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('pickup_date');
            $table->index('vehicle_type');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
