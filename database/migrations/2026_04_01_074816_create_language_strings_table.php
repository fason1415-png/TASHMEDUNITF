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
        Schema::create('language_strings', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('namespace')->default('app');
            $table->string('group')->default('common');
            $table->string('key');
            $table->string('locale', 12);
            $table->text('value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['clinic_id', 'locale']);
            $table->unique(['clinic_id', 'namespace', 'group', 'key', 'locale'], 'language_strings_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_strings');
    }
};
