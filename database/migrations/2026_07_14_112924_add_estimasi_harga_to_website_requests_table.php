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
        if (!Schema::hasColumn('website_requests', 'estimasi_harga')) {
            Schema::table('website_requests', function (Blueprint $table) {
                $table->string('estimasi_harga')->nullable()->after('target_tanggal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('website_requests', 'estimasi_harga')) {
            Schema::table('website_requests', function (Blueprint $table) {
                $table->dropColumn('estimasi_harga');
            });
        }
    }
};
