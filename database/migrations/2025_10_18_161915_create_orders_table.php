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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('facility_id');
        $table->string('customer_name');
        $table->string('customer_phone');
        $table->string('customer_email')->nullable();
        $table->text('delivery_address')->nullable();
        $table->string('payment_method');
        $table->text('special_instructions')->nullable();
        $table->json('items'); // لتخزين المنتجات
        $table->decimal('total', 10, 2);
        $table->timestamp('order_date');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
