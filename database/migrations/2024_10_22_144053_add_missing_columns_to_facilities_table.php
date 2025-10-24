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
        Schema::table('facilities', function (Blueprint $table) {
            // تحقق إذا العمود مش موجود قبل ما تضيفه
            if (!Schema::hasColumn('facilities', 'phone')) {
                $table->string('phone', 20)->nullable()->after('contact');
            }

            if (!Schema::hasColumn('facilities', 'opening_hours')) {
                $table->string('opening_hours')->nullable()->after('capacity');
            }

            if (!Schema::hasColumn('facilities', 'cuisine_type')) {
                $table->string('cuisine_type')->nullable()->after('opening_hours');
            }

            if (!Schema::hasColumn('facilities', 'delivery_available')) {
                $table->boolean('delivery_available')->default(false)->after('cuisine_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['phone', 'opening_hours', 'cuisine_type', 'delivery_available']);
        });
    }
};
