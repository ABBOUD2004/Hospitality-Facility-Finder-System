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
    if (!Schema::hasColumn('bookings', 'total_price_rwf')) {
        $table->decimal('total_price_rwf', 10, 2)->nullable();
    }
    if (!Schema::hasColumn('bookings', 'total_price_usd')) {
        $table->decimal('total_price_usd', 10, 2)->nullable();
    }
});

}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['total_price_rwf', 'total_price_usd']);
    });
}

};
