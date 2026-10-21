[CmdletBinding()]
param(
    [Parameter(Mandatory=$true)][string]$WordPressRoot,
    [string]$Php='C:\xampp\php\php.exe',
    [string]$MySqlBin="$env:APPDATA\Local\lightning-services\mysql-8.4.0+2\bin\win64\bin"
)
$ErrorActionPreference='Stop'
$testDir=Join-Path $env:TEMP ('glow-rebooking-tests-'+[Guid]::NewGuid().ToString('N'))
$dataDir=Join-Path $testDir 'mysql'
New-Item -ItemType Directory -Path $dataDir,(Join-Path $testDir 'content\plugins'),(Join-Path $testDir 'content\mu-plugins') | Out-Null
$mysqlServer=Join-Path $MySqlBin 'mysqld.exe'
$mysqlAdmin=Join-Path $MySqlBin 'mysqladmin.exe'
$port=Get-Random -Minimum 20000 -Maximum 40000
$initialize=Start-Process -FilePath $mysqlServer -ArgumentList "--no-defaults --initialize-insecure --datadir=`"$dataDir`" --console" -WindowStyle Hidden -Wait -PassThru -RedirectStandardOutput (Join-Path $testDir 'initialize-out.log') -RedirectStandardError (Join-Path $testDir 'initialize.log')
if($initialize.ExitCode -ne 0){throw "MySQL initialization failed. See $testDir"}
$server=$null
try {
    $arguments="--no-defaults --datadir=`"$dataDir`" --port=$port --bind-address=127.0.0.1 --mysqlx=OFF --performance-schema=OFF --console"
    $server=Start-Process -FilePath $mysqlServer -ArgumentList $arguments -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $testDir 'mysql-out.log') -RedirectStandardError (Join-Path $testDir 'mysql-error.log')
    $ready=$false
    for($i=0;$i -lt 30;$i++){
        $ErrorActionPreference='Continue'
        & $mysqlAdmin --no-defaults --host=127.0.0.1 "--port=$port" --user=root --connect-timeout=1 ping 2>$null | Out-Null
        $ErrorActionPreference='Stop'
        if($LASTEXITCODE -eq 0){$ready=$true;break}
        Start-Sleep -Milliseconds 500
    }
    if(!$ready){throw "Isolated MySQL did not start. See $testDir"}
    & $Php (Join-Path $PSScriptRoot 'integration.php') $WordPressRoot $port $testDir
    if($LASTEXITCODE -ne 0){throw 'Integration checks failed'}
} finally {
    if($server -and !$server.HasExited){
        & $mysqlAdmin --no-defaults --host=127.0.0.1 "--port=$port" --user=root --connect-timeout=1 shutdown 2>$null | Out-Null
        $server.WaitForExit(10000) | Out-Null
    }
    Write-Host "Temporary test database and logs: $testDir"
}
