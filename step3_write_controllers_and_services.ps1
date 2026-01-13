# Step 3: Generate Controllers and Services for Laravel 12.5
# UTF-8 without BOM safe version

Write-Host "`nStarting Step 3: Creating controllers and services..."

# Ensure we are in Laravel project root
if (-not (Test-Path "artisan")) {
    Write-Error "Run this script from your Laravel project root (where artisan file is)."
    exit
}

# UTF-8 encoding (no BOM)
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

# Directories
$controllerDirs = @(
    "app/Http/Controllers",
    "app/Http/Controllers/Admin",
    "app/Http/Controllers/Api",
    "app/Http/Controllers/Auth"
)
$serviceDir = "app/Services"

# Create directories
foreach ($dir in $controllerDirs + $serviceDir) {
    if (-not (Test-Path $dir)) {
        New-Item -Path $dir -ItemType Directory -Force | Out-Null
        Write-Host "Created directory: $dir"
    }
}

# ---------------- Controllers ----------------
$controllers = @{
    "DashboardController.php" = @'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view("dashboard");
    }
}
'@

    "PayReturnController.php" = @'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PayReturnController extends Controller
{
    public function handle(Request $request)
    {
        return response()->json(["status" => "success"]);
    }
}
'@

    "Admin/UserController.php" = @'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view("admin.users.index");
    }
}
'@
}

# ---------------- Services ----------------
$services = @{
    "UserService.php" = @'
<?php
namespace App\Services;
use App\Models\User;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }
}
'@
}

# Write Controllers
foreach ($file in $controllers.Keys) {
    $path = "app/Http/Controllers/$file"
    [System.IO.File]::WriteAllText($path, $controllers[$file], $utf8NoBom)
    Write-Host "Created controller: $path"
}

# Write Services
foreach ($file in $services.Keys) {
    $path = "$serviceDir/$file"
    [System.IO.File]::WriteAllText($path, $services[$file], $utf8NoBom)
    Write-Host "Created service: $path"
}

Write-Host "`nStep 3 complete: All controllers and services created (UTF-8 without BOM)."
