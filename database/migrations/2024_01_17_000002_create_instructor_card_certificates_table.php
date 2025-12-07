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
        Schema::create('instructor_card_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_card_request_id')->constrained('instructor_card_requests')->onDelete('cascade');
            $table->foreignId('user_certificate_id')->constrained('user_certificates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_card_certificates');
    }
};

