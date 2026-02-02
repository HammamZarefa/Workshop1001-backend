<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('admin')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        $logs = $query->paginate(20);

        return view('admin.audit-logs.index', compact('logs'));
    }


    public function export()
    {
        $logs = AuditLog::with('admin')->latest()->get();

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM

        $csv .= "ID,Action,Resource,Admin,IP Address,Created At\n";

        foreach ($logs as $log) {
            $adminName = $log->admin?->name ?? 'System';

            $csv .= "{$log->id},"
                . "\"{$log->action}\","
                . "\"{$log->resource}\","
                . "\"{$adminName}\","
                . "{$log->ip_address},"
                . "{$log->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="audit_logs.csv"');
    }

}
