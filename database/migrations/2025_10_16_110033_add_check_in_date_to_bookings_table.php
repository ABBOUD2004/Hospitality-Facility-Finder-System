<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
   Schema::table('bookings', function (Blueprint $table) {
    if (!Schema::hasColumn('bookings', 'check_in_date')) {
        $table->date('check_in_date')->nullable();
    }
});

}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn('check_in_date');
    });
}

};
