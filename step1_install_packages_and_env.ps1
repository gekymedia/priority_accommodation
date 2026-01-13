# step1_install_packages_and_env.ps1
# Safe for Windows PowerShell 5.1 / PowerShell 7+

$ErrorActionPreference = "Stop"

Write-Host "Step 1 - Installing packages and updating .env (dummy values) for Priority Accommodations (Laravel 12.5)."

# ---------------------------------------------------------------------
# Verify we are in the Laravel project root
# ---------------------------------------------------------------------
if (-not (Test-Path ".\artisan")) {
    Write-Error "ERROR: artisan not found. Run this script from your Laravel project root."
    exit 1
}

# ---------------------------------------------------------------------
# Composer packages
# ---------------------------------------------------------------------
Write-Host ""
Write-Host "Installing composer packages..."
composer require laravel/sanctum `
    spatie/laravel-permission `
    barryvdh/laravel-dompdf `
    maatwebsite/excel `
    laravel/scout `
    teamtnt/laravel-scout-tntsearch-driver `
    guzzlehttp/guzzle `
    laravel/horizon `
    yabacon/paystack

# ---------------------------------------------------------------------
# Breeze (Blade)
# ---------------------------------------------------------------------
Write-Host ""
Write-Host "Installing Breeze (Blade) and dev tools..."
composer require laravel/breeze --dev
php artisan breeze:install blade

# ---------------------------------------------------------------------
# NPM packages
# ---------------------------------------------------------------------
Write-Host ""
Write-Host "Installing npm packages..."
npm install
npm i bootstrap @popperjs/core axios aos laravel-vite-plugin vite --save-dev

Write-Host ""
Write-Host "Building assets (production build)..."
npm run build

# ---------------------------------------------------------------------
# Publish configs
# ---------------------------------------------------------------------
Write-Host ""
Write-Host "Publishing vendor configs..."
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config" --force
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider" --tag="config" --force

# ---------------------------------------------------------------------
# Update .env (dummy values)
# ---------------------------------------------------------------------
Write-Host ""
Write-Host "Updating .env with dummy values..."

$envPath = ".env"
if (-not (Test-Path $envPath)) {
    Copy-Item ".env.example" $envPath -Force
}

$additions = @'
APP_NAME="Priority Accommodations"
APP_URL=http://localhost
SESSION_DRIVER=cookie
QUEUE_CONNECTION=database

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SCOUT_DRIVER=tntsearch
TNTSEARCH_STORAGE=/storage/search

PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxxx

WHATSAPP_VERIFY_TOKEN=whatsapp-verify-demo
WHATSAPP_ACCESS_TOKEN=EAAxxxxxxx

HUBTEL_ACCOUNT=demo
HUBTEL_API_KEY=hubtel_key
HUBTEL_API_SECRET=hubtel_secret
HUBTEL_FROM=PRIORITY

ADMISSIONS_PARTNER_ID=cug-admissions
ADMISSIONS_SIGNING_SECRET=cug_signing_demo_secret
ADMISSIONS_WEBHOOK_SECRET=priority_webhook_demo_secret

FILESYSTEM_DISK=public
HORIZON_PREFIX=priority_hzn_
'@

$envText = Get-Content $envPath -Raw
foreach ($line in ($additions -split "`n")) {
    $line = $line.TrimEnd("`r")
    if ($line -eq "") { continue }
    $key = ($line -split '=')[0].Trim()
    if ($envText -match ('(?m)^\s*' + [regex]::Escape($key) + '\s*=')) {
        $envText = [regex]::Replace(
            $envText,
            ('(?m)^\s*' + [regex]::Escape($key) + '\s*=.*$'),
            $line
        )
    }
    else {
        $envText += [Environment]::NewLine + $line
    }
}

# Write UTF-8 without BOM
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($envPath, $envText, $utf8NoBom)

Write-Host ""
Write-Host "Step 1 complete: composer/npm installed and .env updated successfully."
