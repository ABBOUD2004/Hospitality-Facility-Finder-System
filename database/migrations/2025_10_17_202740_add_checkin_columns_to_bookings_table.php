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
    // Schema::table('bookings', function (Blueprint $table) {
    //     $table->dateTime('checkin_date')->nullable();
    //     $table->dateTime('checkout_date')->nullable();
    // });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['checkin_date', 'checkout_date']);
    });
}

};
