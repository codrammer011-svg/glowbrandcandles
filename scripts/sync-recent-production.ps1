[CmdletBinding()]
param(
 [Parameter(Mandatory=$true)][string]$SqlDump,
 [ValidateRange(1,365)][int]$Days=7,
 [string]$Database='latest',
 [string]$DbHost='127.0.0.1',
 [int]$DbPort=10009,
 [string]$DbUser='root',
 [string]$DbPassword='root',
 [string]$Php='C:\xampp\php\php.exe',
 [string]$MySqlBin="$env:APPDATA\Local\lightning-services\mysql-8.4.0+2\bin\win64\bin",
 [switch]$Apply
)
$ErrorActionPreference='Stop'
if($Database -notmatch '^[A-Za-z0-9_]+$' -or $DbHost -notin @('127.0.0.1','localhost')){throw 'Only a local database with a valid name is allowed.'}
$SqlDump=(Resolve-Path -LiteralPath $SqlDump).Path
$root=[IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$job=Join-Path $env:TEMP ('glow-recent-sync-'+(Get-Date -Format yyyyMMdd-HHmmss)+'-'+[Guid]::NewGuid().ToString('N').Substring(0,6))
New-Item -ItemType Directory -Path $job | Out-Null
$stage='glow_recent_'+[Guid]::NewGuid().ToString('N').Substring(0,12)
$mysql=Join-Path $MySqlBin mysql.exe;$dump=Join-Path $MySqlBin mysqldump.exe
foreach($path in @($Php,$mysql,$dump)){if(!(Test-Path -LiteralPath $path)){throw "Missing tool: $path"}}
$config=Join-Path $job config.json;$cnf=Join-Path $job mysql.cnf
$utf8=New-Object Text.UTF8Encoding($false)
function Native([string]$exe,[string[]]$arguments){& $exe @arguments;if($LASTEXITCODE -ne 0){throw "Command failed: $exe ($LASTEXITCODE)"}}
function Escape-Cnf([string]$v){return $v.Replace('\','\\').Replace('"','\"').Replace("`r",'\r').Replace("`n",'\n')}
[IO.File]::WriteAllText($cnf,"[client]`nhost=$DbHost`nport=$DbPort`nuser=`"$(Escape-Cnf $DbUser)`"`npassword=`"$(Escape-Cnf $DbPassword)`"`n",$utf8)
try{
 if($Apply){
  Write-Host 'Creating a full backup before applying changes...'
  Native $dump @("--defaults-extra-file=$cnf",'--single-transaction','--no-tablespaces',"--result-file=$job/local-before.sql",$Database)
  # Local-only guard: copied orders must never send customer emails or run workers.
  $guard=@'
<?php
defined('ABSPATH') || exit;
if (!defined('GLOW_EMAIL_WORKER_DISABLED')) define('GLOW_EMAIL_WORKER_DISABLED', true);
if (!defined('DISABLE_WP_CRON')) define('DISABLE_WP_CRON', true);
add_filter('pre_wp_mail', '__return_false', PHP_INT_MAX);
add_filter('action_scheduler_allow_async_request_runner', '__return_false', PHP_INT_MAX);
'@
  $guardPath=Join-Path $root 'wp-content/mu-plugins/000-glow-local-mail-guard.php'
  if(Test-Path -LiteralPath $guardPath){Copy-Item -LiteralPath $guardPath -Destination (Join-Path $job mail-guard-before.php)}
  [IO.File]::WriteAllText($guardPath,$guard,$utf8)
 }
 Write-Host 'Preparing an isolated staging database from the supplied SQL dump...'
 Native $Php @((Join-Path $PSScriptRoot prepare-production-stage.php),$SqlDump,(Join-Path $job staging.sql))
 Native $mysql @("--defaults-extra-file=$cnf",'-e',"CREATE DATABASE ``$stage`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
 # Use redirected stdin rather than PowerShell piping to preserve SQL bytes.
 $start=New-Object Diagnostics.ProcessStartInfo
 $start.FileName=$mysql;$start.Arguments="--defaults-extra-file=`"$cnf`" --default-character-set=utf8mb4 $stage"
 $start.UseShellExecute=$false;$start.RedirectStandardInput=$true
 $proc=[Diagnostics.Process]::Start($start)
 $inputFile=[IO.File]::OpenRead((Join-Path $job staging.sql))
 try{$inputFile.CopyTo($proc.StandardInput.BaseStream)}finally{$inputFile.Dispose();$proc.StandardInput.Close()}
 $proc.WaitForExit();if($proc.ExitCode -ne 0){throw 'Staging import failed; target database was not changed.'}
 $settings=@{host=$DbHost;port=$DbPort;user=$DbUser;password=$DbPassword;database=$Database;stage=$stage;since=(Get-Date).AddDays(-$Days).ToString('yyyy-MM-dd HH:mm:ss');apply=[bool]$Apply;report=(Join-Path $job report.json)}
 [IO.File]::WriteAllText($config,($settings|ConvertTo-Json),$utf8)
 Native $Php @('-d','memory_limit=1536M',(Join-Path $PSScriptRoot sync-recent-business.php),$config)
 Write-Host "Backup/report folder: $job"
 Write-Host "Staging database retained for review: $stage"
}finally{
 foreach($secret in @($config,$cnf)){if(Test-Path -LiteralPath $secret){Remove-Item -LiteralPath $secret}}
}
