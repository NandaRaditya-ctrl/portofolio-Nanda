$ErrorActionPreference = 'Stop'
$runtime = Join-Path (Split-Path $PSScriptRoot -Parent) '.demo-runtime'
foreach ($kind in 'php','mysql') {
    $pidFile = Join-Path $runtime "$kind.pid"
    if (!(Test-Path $pidFile)) { continue }
    $demoPid = [int](Get-Content $pidFile)
    $processInfo = Get-CimInstance Win32_Process -Filter "ProcessId=$demoPid" -ErrorAction SilentlyContinue
    if (!$processInfo) { continue }
    $matches = if ($kind -eq 'php') { $processInfo.Name -eq 'php.exe' -and $processInfo.CommandLine.Contains('127.0.0.1:3118') } else { $processInfo.Name -eq 'mariadbd.exe' -and $processInfo.CommandLine.Contains((Join-Path $runtime 'mysql')) }
    if (!$matches) { throw "PID $demoPid no longer belongs to this demo; it was not stopped." }
    Stop-Process -Id $demoPid
}
Write-Output 'Demo backend stopped. Demo databases are preserved.'
