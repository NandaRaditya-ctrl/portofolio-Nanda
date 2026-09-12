<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('student'));
        Schema::create('courts', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('sport');
            $t->unsignedInteger('price');
            $t->timestamps();
        });
        Schema::create('court_bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained();
            $t->foreignId('court_id')->constrained('courts');
            $t->date('date');
            $t->unsignedTinyInteger('hour');
            $t->unsignedInteger('price');
            $t->timestamps();
            $t->unique(['court_id', 'date', 'hour']);
        });
        Schema::create('opportunities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained();
            $t->string('title');
            $t->string('company');
            $t->string('city');
            $t->string('category');
            $t->text('description');
            $t->date('deadline');
            $t->unsignedSmallInteger('capacity');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('pkl_applications', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained();
            $t->foreignId('opportunity_id')->constrained('opportunities');
            $t->text('motivation');
            $t->string('cv_path');
            $t->string('status')->default('pending');
            $t->timestamps();
            $t->unique(['user_id', 'opportunity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_applications');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('court_bookings');
        Schema::dropIfExists('courts');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
