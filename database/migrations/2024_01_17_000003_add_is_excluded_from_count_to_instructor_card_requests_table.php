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
        Schema::table('instructor_card_requests', function (Blueprint $table) {
            $table->boolean('is_excluded_from_count')->default(false)->after('request_count')->comment('Başvuru sayısından hariç tutulacak mı?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_card_requests', function (Blueprint $table) {
            $table->dropColumn('is_excluded_from_count');
        });
    }
};

