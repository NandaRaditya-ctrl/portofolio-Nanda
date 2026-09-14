param(
    [string]$Source = 'C:/laragon/www/portofolio-v3',
    [string]$Php = 'C:/laragon/bin/php/php-8.5.6-Win32-vs17-x64/php.exe',
    [string]$MariaBin = 'C:/laragon/bin/mysql/mariadb-12.2.2-winx64/bin'
)
$ErrorActionPreference = 'Stop'
$repo = Split-Path $PSScriptRoot -Parent
$runtime = Join-Path $repo '.demo-runtime'
$app = Join-Path $runtime 'app'
$mysqlData = Join-Path $runtime 'mysql'
New-Item -ItemType Directory -Path $runtime -Force | Out-Null
if (!(Test-Path (Join-Path $app 'artisan'))) {
    New-Item -ItemType Directory -Path $app -Force | Out-Null
    & git -C $Source archive HEAD -o (Join-Path $runtime 'source.tar')
    if ($LASTEXITCODE) { throw 'Cannot archive the Laravel source.' }
    & tar -xf (Join-Path $runtime 'source.tar') -C $app
    if ($LASTEXITCODE) { throw 'Cannot unpack the Laravel source.' }
    New-Item -ItemType Junction -Path (Join-Path $app 'vendor') -Target (Join-Path $Source 'vendor') | Out-Null
    foreach ($month in 5,6,7) {
        $config = Join-Path $app "public/bulan-$month/config/database.php"
        $code = Get-Content -LiteralPath $config -Raw
        $code = $code.Replace("'localhost'", "'127.0.0.1'").Replace('$port = 3306;', '$port = 3317;')
        $code = $code.Replace('new mysqli($host, $user, $pass);', "new mysqli(`$host, `$user, `$pass, '', 3317);")
        [IO.File]::WriteAllText($config, $code)
    }
}
$bootstrapPath = Join-Path $app 'bootstrap/app.php'
$bootstrapText = Get-Content -LiteralPath $bootstrapPath -Raw
if (!$bootstrapText.Contains('trustProxies')) {
    $bootstrapText = $bootstrapText.Replace('$middleware->alias(', '$middleware->trustProxies(at: ["127.0.0.1", "::1"]); $middleware->alias(')
    [IO.File]::WriteAllText($bootstrapPath, $bootstrapText)
}
if (!(Test-Path (Join-Path $app 'public/build/manifest.json'))) {
    if (!(Test-Path (Join-Path $Source 'public/build/manifest.json'))) { throw 'Build the Laravel frontend with npm run build in the source project first.' }
    Copy-Item -LiteralPath (Join-Path $Source 'public/build') -Destination (Join-Path $app 'public/build') -Recurse
}
if (!(Test-Path (Join-Path $mysqlData 'mysql'))) {
    & (Join-Path $MariaBin 'mariadb-install-db.exe') "--datadir=$mysqlData" --port=3317 --silent
    if ($LASTEXITCODE) { throw 'Cannot initialize the isolated demo database.' }
}
$mysqlListener = Get-NetTCPConnection -LocalPort 3317 -State Listen -ErrorAction SilentlyContinue
if (!$mysqlListener) {
    $mysql = Start-Process -FilePath (Join-Path $MariaBin 'mariadbd.exe') -ArgumentList @('--no-defaults', "--datadir=$mysqlData", '--port=3317', '--bind-address=127.0.0.1', '--console') -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $runtime 'mysql-out.log') -RedirectStandardError (Join-Path $runtime 'mysql-err.log')
    $mysql.Id | Set-Content (Join-Path $runtime 'mysql.pid')
    for ($attempt=0; $attempt -lt 30; $attempt++) {
        if (Get-NetTCPConnection -LocalPort 3317 -State Listen -ErrorAction SilentlyContinue) { break }
        Start-Sleep -Milliseconds 500
    }
} else {
    $processInfo = Get-CimInstance Win32_Process -Filter "ProcessId=$($mysqlListener[0].OwningProcess)"
    if (!$processInfo.CommandLine.Contains($mysqlData)) { throw 'Port 3317 belongs to another database. Refusing to use it.' }
}
$sqlite = Join-Path $runtime 'laravel.sqlite'
if (!(Test-Path $sqlite)) { New-Item -ItemType File -Path $sqlite | Out-Null }
$keyFile = Join-Path $runtime 'key.txt'
if (!(Test-Path $keyFile)) { [Convert]::ToBase64String([Security.Cryptography.RandomNumberGenerator]::GetBytes(32)) | Set-Content $keyFile }
$env:APP_ENV='local'; $env:APP_DEBUG='false'; $env:APP_KEY='base64:'+(Get-Content $keyFile -Raw).Trim()
$env:APP_URL='http://127.0.0.1:3107'; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=$sqlite
$env:SESSION_DRIVER='file'; $env:SESSION_COOKIE='portfolio_demo_session'; $env:CACHE_STORE='file'; $env:MAIL_MAILER='log'; $env:QUEUE_CONNECTION='sync'
Push-Location $app
try {
    & $Php artisan migrate --force --no-interaction
    if ($LASTEXITCODE) { throw 'Laravel migration failed.' }
    & $Php artisan db:seed --class=JourneySeeder --force --no-interaction
    if ($LASTEXITCODE) { throw 'Laravel demo seeding failed.' }
    & $Php (Join-Path $PSScriptRoot 'seed-backend-demo.php') $app
    if ($LASTEXITCODE) { throw 'Cannot prepare demo login.' }
    if (!(Test-Path (Join-Path $runtime 'native-ready'))) {
        foreach ($month in 5,6,7) { & $Php -d mysqli.default_port=3317 "public/bulan-$month/setup.php" | Out-Null; if ($LASTEXITCODE) { throw "Setup failed for month $month" } }
        New-Item -ItemType File -Path (Join-Path $runtime 'native-ready') | Out-Null
    }
    if (!(Get-NetTCPConnection -LocalPort 3118 -State Listen -ErrorAction SilentlyContinue)) {
        $server = Start-Process -FilePath $Php -ArgumentList @('-S','127.0.0.1:3118','-t','.',(Join-Path $Source 'vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php')) -WorkingDirectory (Join-Path $app 'public') -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $runtime 'php-out.log') -RedirectStandardError (Join-Path $runtime 'php-err.log')
        $server.Id | Set-Content (Join-Path $runtime 'php.pid')
    }
} finally { Pop-Location }
Write-Output 'Backend demo ready at 127.0.0.1:3118. Separate MariaDB:3317 and Laravel SQLite database.'
