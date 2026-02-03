<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('admin')
            ->latest()

            ->when($request->action, function ($q) use ($request) {
                $q->where('action', $request->action);
            })

            ->when($request->from, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from);
            })

            ->when($request->to, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to);
            })

            ->when($request->admin_id, function ($q) use ($request) {
                $q->where('admin_id', $request->admin_id);
            })

            ->paginate(20);

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
