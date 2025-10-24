<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('manager')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable(); // ✅ إضافة
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('type'); // hotel, restaurant, coffee_shop

            // ✅ أعمدة خاصة بالمطاعم والكوفي شوب
            $table->string('opening_hours')->nullable();
            $table->string('cuisine_type')->nullable();
            $table->boolean('delivery_available')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
