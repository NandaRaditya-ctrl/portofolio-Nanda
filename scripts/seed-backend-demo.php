<?php
$root = $argv[1] ?? '';
if (!str_contains((string) getenv('DB_DATABASE'), '.demo-runtime')) {
    throw new RuntimeException('Refusing to seed outside the isolated demo database.');
}
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = App\Models\User::firstOrNew(['email' => 'demo@portfolio.test']);
$user->forceFill(['name' => 'Pengunjung Demo', 'password' => Illuminate\Support\Facades\Hash::make('DemoPortfolio2026!'), 'role' => 'admin'])->save();
echo "Demo login prepared.\n";
