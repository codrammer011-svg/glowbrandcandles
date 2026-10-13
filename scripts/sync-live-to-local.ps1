[CmdletBinding()]
param(
    [string]$SshHost = '167.88.40.111',
    [string]$SshUser = 'u336530615',
    [int]$SshPort = 65002,
    [string]$LiveRoot = '/home/u336530615/domains/glowbrandscandles.com/public_html',
    [string]$Database = 'u336530615_latest',
    [string]$DbHost = '127.0.0.1',
    [int]$DbPort = 10009,
    [string]$DbUser = 'root',
    [string]$DbPassword = 'root',
    [string]$TablePrefix = 'wp_',
    [string]$LocalUrl = 'http://glow.local',
    [ValidateRange(1,365)][int]$ImageDays = 7,
    [string]$Php = 'C:\xampp\php\php.exe',
    [string]$MySqlBin = "$env:APPDATA\Local\lightning-services\mysql-8.4.0+2\bin\win64\bin",
    [switch]$ImagesOnly,
    [switch]$DatabaseOnly,
    [switch]$PlanOnly
)
$ErrorActionPreference = 'Stop'
$root = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
if ($ImagesOnly -and $DatabaseOnly) { throw 'Choose only one of ImagesOnly or DatabaseOnly.' }
if ($SshUser -notmatch '^[A-Za-z0-9_-]+$' -or $SshHost -notmatch '^[A-Za-z0-9.-]+$' -or $Database -notmatch '^[A-Za-z0-9_]+$' -or $TablePrefix -notmatch '^[A-Za-z0-9_]+$') { throw 'Invalid connection identifier.' }
if ($LiveRoot -notmatch '^/[A-Za-z0-9_./-]+$' -or $LiveRoot -match '(^|/)\.\.(/|$)') { throw 'Invalid live root.' }
if ($LocalUrl -notmatch '^https?://(localhost|127\.0\.0\.1|[A-Za-z0-9.-]+\.(local|test))(:[0-9]+)?(/[^\s]*)?$') { throw 'LocalUrl must point to a local/test site.' }
$mysql = Join-Path $MySqlBin 'mysql.exe'; $dump = Join-Path $MySqlBin 'mysqldump.exe'
foreach ($tool in @('ssh','scp','tar')) { Get-Command $tool -ErrorAction Stop | Out-Null }
if (!$ImagesOnly) { foreach ($tool in @($Php,$mysql,$dump)) { if (!(Test-Path -LiteralPath $tool)) { throw "Missing tool: $tool" } } }
if ($PlanOnly) {
    Write-Host "Live -> local: $SshUser@$SshHost ($LiveRoot) -> $root"
    Write-Host "Database: $Database at ${DbHost}:$DbPort; local URL: $LocalUrl"
    Write-Host "Images: last $ImageDays days, replace existing files. No changes made."
    return
}
function Run-Native([string]$exe, [string[]]$arguments) {
    & $exe @arguments
    if ($LASTEXITCODE -ne 0) { throw "$exe failed (exit $LASTEXITCODE)." }
}
$job = Join-Path $env:TEMP ('glow-sync-' + (Get-Date -Format 'yyyyMMdd-HHmmss') + '-' + [Guid]::NewGuid().ToString('N').Substring(0,8))
New-Item -ItemType Directory -Path $job | Out-Null
$remote = "/home/$SshUser/glow-sync-" + [Guid]::NewGuid().ToString('N')
$server = "$SshUser@$SshHost"
$sshArgs = @('-o','BatchMode=yes','-o','ConnectTimeout=20','-p',"$SshPort",$server)
$utf8 = New-Object Text.UTF8Encoding($false)
$credentials = Join-Path $job 'mysql.cnf'
$configFile = Join-Path $job 'local-config.json'
try {
    if (!$ImagesOnly) {
        $escape = { param($value) $value.Replace('\','\\').Replace('"','\"').Replace("`r",'\r').Replace("`n",'\n') }
        $cnf = "[client]`nhost=`"$(& $escape $DbHost)`"`nport=$DbPort`nuser=`"$(& $escape $DbUser)`"`npassword=`"$(& $escape $DbPassword)`"`n"
        [IO.File]::WriteAllText($credentials,$cnf,$utf8)
        Write-Host 'Backing up the current local database...'
        Run-Native $dump @("--defaults-extra-file=$credentials",'--single-transaction','--routines','--triggers','--no-tablespaces',"--result-file=$job/local-before.sql",$Database)
        $guard = @'
<?php
/** Plugin Name: Glow Local Mail Guard (local only) */
defined('ABSPATH') || exit;
if (!defined('GLOW_EMAIL_WORKER_DISABLED')) { define('GLOW_EMAIL_WORKER_DISABLED', true); }
if (!defined('DISABLE_WP_CRON')) { define('DISABLE_WP_CRON', true); }
add_filter('pre_wp_mail', '__return_false', PHP_INT_MAX);
'@
        $mu = Join-Path $root 'wp-content/mu-plugins'
        New-Item -ItemType Directory -Force -Path $mu | Out-Null
        $guardPath = Join-Path $mu '000-glow-local-mail-guard.php'
        if (Test-Path -LiteralPath $guardPath) { Copy-Item -LiteralPath $guardPath -Destination (Join-Path $job 'mail-guard-before.php') }
        [IO.File]::WriteAllText($guardPath,$guard,$utf8)
    }
    $remoteScript = @'
#!/bin/bash
set -euo pipefail
umask 077
cd __ROOT__
if [[ __DB__ == yes ]]; then
  php -d disable_functions= /usr/local/bin/wp db export __JOB__/live.sql --add-drop-table --single-transaction
  sha256sum __JOB__/live.sql | cut -d ' ' -f 1 > __JOB__/live.sha256
fi
if [[ __IMAGES__ == yes ]]; then
  cd wp-content/uploads
  find . -type f -mtime -__DAYS__ -iregex '.*\.\(jpg\|jpeg\|png\|gif\|webp\|avif\|svg\|bmp\|ico\)' -print0 > __JOB__/images.list
  tar --null -czf __JOB__/images.tar.gz -T __JOB__/images.list
  sha256sum __JOB__/images.tar.gz | cut -d ' ' -f 1 > __JOB__/images.sha256
fi
'@
    $remoteScript = $remoteScript.Replace('__ROOT__', $LiveRoot).Replace('__JOB__',$remote).Replace('__DAYS__',"$ImageDays").Replace('__DB__',$(if($ImagesOnly){'no'}else{'yes'})).Replace('__IMAGES__',$(if($DatabaseOnly){'no'}else{'yes'}))
    [IO.File]::WriteAllText((Join-Path $job 'export.sh'),$remoteScript.Replace("`r`n","`n"),$utf8)
    Run-Native 'ssh' ($sshArgs + @("mkdir -m 700 $remote"))
    Run-Native 'scp' @('-P',"$SshPort",(Join-Path $job 'export.sh'),"${server}:$remote/export.sh")
    Write-Host 'Exporting live data (live is read-only)...'
    Run-Native 'ssh' ($sshArgs + @("bash $remote/export.sh"))
    function Download-Checked([string]$name, [string]$hashName) {
        Run-Native 'scp' @('-P',"$SshPort","${server}:$remote/$name",(Join-Path $job $name))
        Run-Native 'scp' @('-P',"$SshPort","${server}:$remote/$hashName",(Join-Path $job $hashName))
        $actual = (Get-FileHash -LiteralPath (Join-Path $job $name) -Algorithm SHA256).Hash
        $expected = (Get-Content -LiteralPath (Join-Path $job $hashName) -Raw).Trim()
        if ($actual -ne $expected) { throw "Checksum mismatch: $name" }
    }
    if (!$ImagesOnly) {
        Download-Checked 'live.sql' 'live.sha256'
        $last = Get-Content -LiteralPath (Join-Path $job 'live.sql') -Tail 4
        if (!($last -match 'Dump completed')) { throw 'Database export is incomplete.' }
        Write-Host 'Importing into local (existing local database data will be replaced)...'
        $sql = (Join-Path $job 'live.sql').Replace('\','/')
        Run-Native $mysql @("--defaults-extra-file=$credentials",'--max-allowed-packet=256M',$Database,"--execute=source $sql")
        $settings = @{host=$DbHost;port=$DbPort;user=$DbUser;password=$DbPassword;database=$Database;prefix=$TablePrefix;localUrl=$LocalUrl}
        [IO.File]::WriteAllText($configFile,($settings | ConvertTo-Json),$utf8)
        Write-Host 'Replacing URLs while preserving serialized data and email addresses...'
        Run-Native $Php @('-d','memory_limit=1024M',(Join-Path $PSScriptRoot 'local-db-urls.php'),$configFile)
    }
    if (!$DatabaseOnly) {
        Download-Checked 'images.tar.gz' 'images.sha256'
        $archive = Join-Path $job 'images.tar.gz'
        $entries = @(& tar -tzf $archive)
        if ($LASTEXITCODE -ne 0) { throw 'Invalid image archive.' }
        $uploads = [IO.Path]::GetFullPath((Join-Path $root 'wp-content/uploads'))
        New-Item -ItemType Directory -Force -Path $uploads | Out-Null
        foreach ($entry in $entries) {
            if ($entry -match '(^|/)\.\.(/|$)' -or $entry -match '^[A-Za-z]:|^/' -or $entry -notmatch '(?i)\.(jpg|jpeg|png|gif|webp|avif|svg|bmp|ico)$') { throw "Unsafe image path: $entry" }
            $relative = $entry -replace '^\./',''
            $target = [IO.Path]::GetFullPath((Join-Path $uploads $relative))
            if (!$target.StartsWith($uploads + '\',[StringComparison]::OrdinalIgnoreCase)) { throw 'Image target outside uploads.' }
            if (Test-Path -LiteralPath $target) {
                $saved = Join-Path (Join-Path $job 'images-before') $relative
                New-Item -ItemType Directory -Force ([IO.Path]::GetDirectoryName($saved)) | Out-Null
                Copy-Item -LiteralPath $target -Destination $saved
            }
        }
        Run-Native 'tar' @('-xzf',$archive,'-C',$uploads)
        Write-Host "Images synced: $($entries.Count) (existing files replaced)."
    }
    Write-Host "Completed. Local backups and downloaded data: $job"
    Write-Host "Local site: $LocalUrl. Local email sending and automatic cron remain disabled."
    Write-Host "Remote export retained privately at $remote"
} finally {
    foreach ($file in @($credentials,$configFile)) { if (Test-Path -LiteralPath $file) { Remove-Item -LiteralPath $file } }
}
