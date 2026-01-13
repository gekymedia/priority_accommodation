# Step 4: Generate Models and web.php routes for Laravel 12.5
# UTF-8 without BOM safe version

Write-Host "`nStarting Step 4: Creating models and routes..."

if (-not (Test-Path "artisan")) {
    Write-Error "Run this script from your Laravel project root (where artisan file is)."
    exit
}

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

# ---------------- Models ----------------
$models = @{
    "User.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        "name", "email", "phone", "password", "role"
    ];

    protected $hidden = [
        "password", "remember_token"
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
'@

    "Hostel.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $fillable = ["name", "address", "description"];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
'@

    "Room.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        "hostel_id", "name", "capacity", "price", "status", "images", "video_url"
    ];

    protected $casts = [
        "images" => "array"
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
'@

    "Booking.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "room_id", "check_in", "check_out", "status", "payment_status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
'@

    "Payment.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "booking_id", "amount", "reference", "status", "method"
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
'@

    "AuditLog.php" = @'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "action", "entity", "entity_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
'@
}

# Write Models
foreach ($file in $models.Keys) {
    $path = "app/Models/$file"
    [System.IO.File]::WriteAllText($path, $models[$file], $utf8NoBom)
    Write-Host "Created model: $path"
}

# ---------------- Routes ----------------
$webRoutes = @'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PayReturnController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\HostelController;

// Home & Dashboard
Route::get("/", [DashboardController::class, "index"])->name("home");
Route::get("/dashboard", [DashboardController::class, "index"])->middleware(["auth"])->name("dashboard");

// Payment callback
Route::post("/payment/return", [PayReturnController::class, "handle"])->name("payment.return");

// Admin Routes
Route::prefix("admin")->middleware(["auth"])->group(function () {
    Route::resource("users", UserController::class);
    Route::resource("rooms", RoomController::class);
    Route::resource("hostels", HostelController::class);
});

// Auth routes
require __DIR__ . "/auth.php";
'@

[System.IO.File]::WriteAllText("routes/web.php", $webRoutes, $utf8NoBom)

Write-Host "`n✅ Step 4 complete: Models and routes created successfully (UTF-8 without BOM)."
