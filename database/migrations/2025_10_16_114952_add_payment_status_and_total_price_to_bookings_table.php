<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // ضع الأعمدة بدون "after" لتجنب الخطأ
        if (!Schema::hasColumn('bookings', 'total_price')) {
            $table->decimal('total_price', 10, 2)->nullable();
        }
        if (!Schema::hasColumn('bookings', 'payment_status')) {
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        }
    });
}


    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'payment_status']);
        });
    }
};
