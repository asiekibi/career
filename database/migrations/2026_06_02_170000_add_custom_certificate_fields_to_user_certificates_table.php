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
        Schema::table('user_certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('certificate_id')->nullable()->change();
            $table->string('custom_certificate_name')->nullable()->after('certificate_id');
            $table->string('file_path')->nullable()->after('custom_certificate_name');
            $table->integer('achievement_score')->nullable()->change();
            $table->integer('success_score')->nullable()->change();
            $table->string('issuing_institution')->nullable()->change();
            $table->date('acquisition_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('certificate_id')->nullable(false)->change();
            $table->dropColumn('custom_certificate_name');
            $table->dropColumn('file_path');
            $table->integer('achievement_score')->nullable(false)->change();
            $table->integer('success_score')->nullable(false)->change();
            $table->string('issuing_institution')->nullable(false)->change();
            $table->date('acquisition_date')->nullable(false)->change();
        });
    }
};
