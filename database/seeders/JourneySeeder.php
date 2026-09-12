<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JourneySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Arena Futsal 01', 'Futsal', 120000], ['Court Badminton 01', 'Badminton', 45000], ['Arena Basket 01', 'Basket', 90000]] as [$name,$sport,$price]) {
            Court::firstOrCreate(['name' => $name], compact('sport', 'price'));
        }
        $owner = User::firstOrCreate(['email' => 'studio-demo@example.test'], ['name' => 'Studio Contoh (Demo)', 'password' => Str::password(32)]);
        $owner->forceFill(['role' => 'company'])->save();
        foreach ([['Frontend Developer', 'Bandung', 'Web Development'], ['UI/UX Designer', 'Jakarta', 'Desain'], ['Backend Developer', 'Yogyakarta', 'Web Development'], ['IT Support', 'Surabaya', 'Teknologi'], ['Content Designer', 'Bandung', 'Desain'], ['Quality Assurance', 'Jakarta', 'Teknologi']] as [$title,$city,$category]) {
            Opportunity::firstOrCreate(['user_id' => $owner->id, 'title' => $title], ['company' => 'Studio Contoh (Demo)', 'city' => $city, 'category' => $category,
                'description' => 'Lowongan demonstrasi untuk latihan; bukan rekrutmen nyata. Pelajari alur kerja tim, kerjakan proyek terarah, dan bangun portofolio bersama mentor. Terbuka untuk siswa SMK yang komunikatif, ingin belajar, dan memiliki dasar sesuai bidang. Lampirkan CV serta jelaskan minat dan proyek yang pernah Anda buat.',
                'deadline' => today()->addMonths(3)->toDateString(), 'capacity' => 5]);
        }
    }
}
