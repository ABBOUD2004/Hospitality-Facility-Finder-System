<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // احذف أي أعمدة مكررة (زي address, city, إلخ)
            // وسيب بس الأعمدة اللي مش موجودة في الجدول الأصلي

            if (!Schema::hasColumn('facilities', 'capacity')) {
                $table->integer('capacity')->nullable()->after('address');
            }

            if (!Schema::hasColumn('facilities', 'manager')) {
                $table->string('manager')->nullable()->after('capacity');
            }

            if (!Schema::hasColumn('facilities', 'contact')) {
                $table->string('contact')->nullable()->after('manager');
            }

            if (!Schema::hasColumn('facilities', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'manager', 'contact', 'website']);
        });
    }
};
