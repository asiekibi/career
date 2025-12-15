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
        Schema::create('certificate_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_certificate_id')->constrained('user_certificates')->onDelete('cascade');
            $table->foreignId('certificate_education_id')->constrained('certificate_educations')->onDelete('cascade');
            $table->integer('score');
            $table->timestamps();
            
            // Aynı user_certificate ve certificate_education için tekrar kayıt olmasın
            $table->unique(['user_certificate_id', 'certificate_education_id'], 'cert_lessons_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_lessons');
    }
};
