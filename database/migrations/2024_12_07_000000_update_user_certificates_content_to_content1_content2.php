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
            // Eğer content kolonu varsa kaldır
            if (Schema::hasColumn('user_certificates', 'content')) {
                $table->dropColumn('content');
            }
            
            // content1 ve content2 kolonlarını ekle
            if (!Schema::hasColumn('user_certificates', 'content1')) {
                $table->text('content1')->nullable()->after('register_no');
            }
            if (!Schema::hasColumn('user_certificates', 'content2')) {
                $table->text('content2')->nullable()->after('content1');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_certificates', function (Blueprint $table) {
            // content1 ve content2 kolonlarını kaldır
            if (Schema::hasColumn('user_certificates', 'content1')) {
                $table->dropColumn('content1');
            }
            if (Schema::hasColumn('user_certificates', 'content2')) {
                $table->dropColumn('content2');
            }
            
            // content kolonunu geri ekle
            if (!Schema::hasColumn('user_certificates', 'content')) {
                $table->text('content')->nullable()->after('register_no');
            }
        });
    }
};

