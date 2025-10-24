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
        Schema::table('bookings', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('bookings', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('bookings', 'refund_reason')) {
                $table->text('refund_reason')->nullable()->after('transaction_id');
            }

            if (!Schema::hasColumn('bookings', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('refund_reason');
            }

            // Update payment_status to include more options
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('pending')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'transaction_id',
                'refund_reason',
                'refunded_at'
            ]);
        });
    }
};
