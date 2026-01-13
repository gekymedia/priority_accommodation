<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index()
    {
        $auditLogs = AuditLog::with('user')
            ->latest()
            ->paginate(20);

        return view('audit-logs.index', compact('auditLogs'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        
        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Get recent activities for dashboard.
     */
    public function getRecentActivities()
    {
        $recentActivities = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return $recentActivities;
    }
}