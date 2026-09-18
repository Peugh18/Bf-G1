$ErrorActionPreference = 'Stop'
$phpDirectory = Join-Path $env:LOCALAPPDATA 'Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe'
$phpExecutable = Join-Path $phpDirectory 'php.exe'
if (-not (Test-Path -LiteralPath $phpExecutable)) { throw 'Install PHP.PHP.8.2 with WinGet first.' }
$composerDirectory = Join-Path $env:LOCALAPPDATA 'BRUCE-FIRE\tools'
New-Item -ItemType Directory -Path $composerDirectory -Force | Out-Null
$installer = Join-Path $composerDirectory 'composer-setup.php'
Invoke-WebRequest 'https://getcomposer.org/installer' -OutFile $installer
$signature = (Invoke-WebRequest 'https://composer.github.io/installer.sig').Content
$expected = if ($signature -is [byte[]]) { [Text.Encoding]::UTF8.GetString($signature).Trim() } else { ([string]$signature).Trim() }
$actual = (Get-FileHash -LiteralPath $installer -Algorithm SHA384).Hash
if ($actual -ne $expected) { throw 'Composer installer checksum mismatch.' }
& $phpExecutable $installer "--install-dir=$composerDirectory" '--filename=composer.phar'
if ($LASTEXITCODE -ne 0) { throw 'Composer installation failed.' }
$userPath = [Environment]::GetEnvironmentVariable('Path', 'User')
$entries = @($userPath -split ';' | Where-Object { $_ })
foreach ($directory in @($phpDirectory, $composerDirectory)) {
    if ($entries -notcontains $directory) { $entries += $directory }
}
[Environment]::SetEnvironmentVariable('Path', ($entries -join ';'), 'User')
$env:Path = "$phpDirectory;$composerDirectory;$env:Path"
& $phpExecutable -v
& $phpExecutable (Join-Path $composerDirectory 'composer.phar') --version
