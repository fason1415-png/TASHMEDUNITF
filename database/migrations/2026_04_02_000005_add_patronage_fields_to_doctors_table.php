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
        Schema::table('doctors', function (Blueprint $table): void {
            $table->enum('doctor_type', ['hospital', 'family', 'specialist'])->default('hospital')->after('left_at');
            $table->string('territorial_region')->nullable()->after('doctor_type');
            $table->string('territorial_district')->nullable()->after('territorial_region');
            $table->string('telegram_chat_id')->nullable()->after('territorial_district');
            $table->string('push_token')->nullable()->after('telegram_chat_id');
            $table->boolean('accepts_patronage')->default(false)->after('push_token');
            $table->unsignedInteger('max_active_patronage')->default(10)->after('accepts_patronage');

            $table->index(['doctor_type', 'territorial_region', 'territorial_district'], 'doctors_type_region_district_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->dropIndex('doctors_type_region_district_idx');
            $table->dropColumn([
                'doctor_type',
                'territorial_region',
                'territorial_district',
                'telegram_chat_id',
                'push_token',
                'accepts_patronage',
                'max_active_patronage',
            ]);
        });
    }
};
