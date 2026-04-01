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
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('clinic_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('clinic_id')->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('preferred_language', 12)->default('uz_latn')->after('remember_token');
            $table->boolean('is_active')->default(true)->after('preferred_language');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('clinic_id');
            $table->dropConstrainedForeignId('branch_id');
            $table->dropConstrainedForeignId('doctor_id');
            $table->dropColumn(['phone', 'preferred_language', 'is_active', 'last_login_at']);
        });
    }
};
