<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::table('bookings', function (Blueprint $table) {
    if (!Schema::hasColumn('bookings', 'booking_type')) {
        $table->string('booking_type')->nullable()->after('id');
    }
});



    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_type');
        });
    }
};
