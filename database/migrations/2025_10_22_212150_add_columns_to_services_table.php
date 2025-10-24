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
        Schema::table('services', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('services', 'icon')) {
                $table->string('icon')->nullable()->after('description');
            }

            if (!Schema::hasColumn('services', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('icon');
            }

            if (!Schema::hasColumn('services', 'duration')) {
                $table->integer('duration')->nullable()->comment('Duration in minutes')->after('price');
            }

            if (!Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('duration');
            }

            if (!Schema::hasColumn('services', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }

            if (!Schema::hasColumn('services', 'category')) {
                $table->string('category')->nullable()->after('is_featured');
            }

            if (!Schema::hasColumn('services', 'capacity')) {
                $table->integer('capacity')->nullable()->comment('Max capacity')->after('category');
            }

            if (!Schema::hasColumn('services', 'booking_required')) {
                $table->boolean('booking_required')->default(false)->after('capacity');
            }

            if (!Schema::hasColumn('services', 'meta_data')) {
                $table->json('meta_data')->nullable()->after('booking_required');
            }

            if (!Schema::hasColumn('services', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            // Add indexes for better performance
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('category');
            $table->index(['facility_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'icon',
                'price',
                'duration',
                'is_active',
                'is_featured',
                'category',
                'capacity',
                'booking_required',
                'meta_data',
                'deleted_at'
            ]);
        });
    }
};
