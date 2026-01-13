# step2_create_migrations.ps1
# --------------------------------------------------------------
# Creates all database migrations for Priority Accommodations
# Laravel 12.5 | UTF-8 (no BOM)
# --------------------------------------------------------------

$ErrorActionPreference = "Stop"
Write-Host "Step 2 - Creating migration files for Priority Accommodations..."

if (-not (Test-Path ".\artisan")) {
    Write-Error "ERROR: artisan not found. Run from your Laravel project root."
    exit 1
}

# --------------------------------------------------------------
# Define migrations and schema contents
# --------------------------------------------------------------

$migrations = @{
    "create_users_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->string("phone")->nullable();
            $table->string("gender")->nullable();
            $table->string("national_id")->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("users");
    }
};
'@

    "create_roles_and_permissions_tables" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // spatie/laravel-permission tables
        Schema::create("roles", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("guard_name");
            $table->timestamps();
        });

        Schema::create("permissions", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("guard_name");
            $table->timestamps();
        });

        Schema::create("model_has_permissions", function (Blueprint $table) {
            $table->unsignedBigInteger("permission_id");
            $table->string("model_type");
            $table->unsignedBigInteger("model_id");
            $table->index(["model_id", "model_type"]);
        });

        Schema::create("model_has_roles", function (Blueprint $table) {
            $table->unsignedBigInteger("role_id");
            $table->string("model_type");
            $table->unsignedBigInteger("model_id");
            $table->index(["model_id", "model_type"]);
        });

        Schema::create("role_has_permissions", function (Blueprint $table) {
            $table->unsignedBigInteger("permission_id");
            $table->unsignedBigInteger("role_id");
        });
    }
    public function down(): void {
        Schema::dropIfExists("role_has_permissions");
        Schema::dropIfExists("model_has_roles");
        Schema::dropIfExists("model_has_permissions");
        Schema::dropIfExists("permissions");
        Schema::dropIfExists("roles");
    }
};
'@

    "create_hostels_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("hostels", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("address");
            $table->string("city")->nullable();
            $table->integer("total_rooms")->default(0);
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("hostels");
    }
};
'@

    "create_rooms_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("rooms", function (Blueprint $table) {
            $table->id();
            $table->foreignId("hostel_id")->constrained("hostels")->cascadeOnDelete();
            $table->string("room_number");
            $table->integer("capacity");
            $table->decimal("price_per_semester", 10, 2);
            $table->boolean("available")->default(true);
            $table->string("video_url")->nullable();
            $table->json("photos")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("rooms");
    }
};
'@

    "create_tenants_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("tenants", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable()->constrained("users")->nullOnDelete();
            $table->string("student_id")->nullable();
            $table->string("admission_no")->nullable();
            $table->string("institution")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("tenants");
    }
};
'@

    "create_bookings_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("bookings", function (Blueprint $table) {
            $table->id();
            $table->foreignId("tenant_id")->nullable()->constrained("tenants")->nullOnDelete();
            $table->foreignId("room_id")->constrained("rooms")->cascadeOnDelete();
            $table->enum("status", ["reserved","paid","cancelled","expired"])->default("reserved");
            $table->timestamp("reserved_until")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("bookings");
    }
};
'@

    "create_payments_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("booking_id")->constrained("bookings")->cascadeOnDelete();
            $table->string("provider")->nullable();
            $table->string("transaction_id")->unique();
            $table->decimal("amount", 10, 2);
            $table->string("status")->default("pending");
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("payments");
    }
};
'@

    "create_audit_logs_table" = @'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("audit_logs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable()->constrained("users")->nullOnDelete();
            $table->string("action");
            $table->string("entity");
            $table->unsignedBigInteger("entity_id")->nullable();
            $table->json("changes")->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("audit_logs");
    }
};
'@
}

# --------------------------------------------------------------
# Create migration files
# --------------------------------------------------------------
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

foreach ($name in $migrations.Keys) {
    $timestamp = (Get-Date).ToString("yyyy_MM_dd_HHmmss")
    $file = "database/migrations/${timestamp}_${name}.php"
    [System.IO.File]::WriteAllText($file, $migrations[$name], $utf8NoBom)
    Write-Host "Created migration: $file"
    Start-Sleep -Milliseconds 300
}

Write-Host ""
Write-Host "Step 2 complete: All migration files created successfully."
Write-Host "Next: Run 'php artisan migrate' after completing Step 3 and 4."
