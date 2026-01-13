# step5_write_blades.ps1
# Step 5: Generate Blade templates for tenants and payments

# Directories to create
$dirs = @(
    "resources/views/layouts",
    "resources/views/tenants",
    "resources/views/payments"
)

# Create directories if they don't exist
foreach ($dir in $dirs) {
    if (-not (Test-Path $dir)) {
        New-Item -Path $dir -ItemType Directory | Out-Null
    }
}

# Layout file
$layoutContent = @'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priority Accommodations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Priority Accommodations</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
'@

Set-Content -Path "resources/views/layouts/app.blade.php" -Value $layoutContent -Encoding UTF8

# Tenants Index
$tenantsIndex = @'
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Tenants</h2>
    <a href="{{ route('tenants.create') }}" class="btn btn-success">Add Tenant</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tenants as $tenant)
        <tr>
            <td>{{ $tenant->name }}</td>
            <td>{{ $tenant->email }}</td>
            <td>{{ $tenant->phone }}</td>
            <td>
                <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this tenant?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
'@

Set-Content -Path "resources/views/tenants/index.blade.php" -Value $tenantsIndex -Encoding UTF8

# Payments Index
$paymentsIndex = @'
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Payments</h2>
    <a href="{{ route('payments.create') }}" class="btn btn-success">Add Payment</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tenant</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->tenant->name }}</td>
            <td>{{ $payment->amount }}</td>
            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this payment?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
'@

Set-Content -Path "resources/views/payments/index.blade.php" -Value $paymentsIndex -Encoding UTF8

Write-Host "✅ Step 5 complete: All Blade templates created successfully." -ForegroundColor Green
