<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('journey:role {email} {role}', function () {
    $role = $this->argument('role');
    if (! in_array($role, ['student', 'company', 'admin'])) {
        $this->error('Peran harus student, company, atau admin.');

        return 1;
    }
    $user = User::where('email', $this->argument('email'))->first();
    if (! $user) {
        $this->error('Akun belum terdaftar.');

        return 1;
    }
    $user->forceFill(['role' => $role])->save();
    $this->info('Peran berhasil diperbarui.');
})->purpose('Tetapkan peran akun PKLFinder melalui terminal pengelola');

Artisan::command('journey:demo', function () {
    if (! app()->environment('local')) {
        $this->error('Akun demo hanya untuk APP_ENV=local.');

        return 1;
    }
    $lines = ['# Akun demo lokal PKLFinder', '', 'Masuk di http://127.0.0.1:8013/login. Akun berikut hanya untuk pengujian lokal.', ''];
    foreach (['student' => 'Siswa Demo', 'company' => 'Perusahaan Demo', 'admin' => 'Admin Demo'] as $role => $name) {
        $email = $role.'-journey@example.test';
        if (User::where('email', $email)->exists()) {
            continue;
        }
        $password = Str::password(20);
        $user = User::create(compact('name', 'email', 'password'));
        $user->forceFill(['role' => $role])->save();
        $lines[] = '- '.$name.': `'.$email.'` / `'.$password.'`';
    }
    if (count($lines) > 4) {
        Storage::disk('local')->append('journey-demo-accounts.md', implode(PHP_EOL, $lines));
    }
    $this->info('Akun demo tersedia. Detail: storage/app/private/journey-demo-accounts.md');
})->purpose('Siapkan akun siswa, perusahaan, dan admin demo dengan kata sandi acak');
