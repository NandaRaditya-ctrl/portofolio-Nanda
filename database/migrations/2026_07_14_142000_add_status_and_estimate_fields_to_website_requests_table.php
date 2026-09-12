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
        Schema::table('website_requests', function (Blueprint $table) {
            $table->string('status')->default('Menunggu')->after('estimasi_harga');
            $table->string('estimasi_diterima')->nullable()->after('status');
            $table->string('estimasi_proses')->nullable()->after('estimasi_diterima');
            $table->dateTime('diterima_pada')->nullable()->after('estimasi_proses');
            $table->dateTime('diproses_pada')->nullable()->after('diterima_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_requests', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'estimasi_diterima',
                'estimasi_proses',
                'diterima_pada',
                'diproses_pada',
            ]);
        });
    }
};
