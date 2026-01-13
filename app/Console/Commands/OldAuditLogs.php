<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class OldAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'audit:purge-old';

    /**
     * The console command description.
     */
    protected $description = 'Delete audit logs older than 90 days';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $thresholdDate = Carbon::now()->subDays(90);

        $count = AuditLog::where('created_at', '<', $thresholdDate)->count();

        if ($count > 0) {
            AuditLog::where('created_at', '<', $thresholdDate)->delete();
            $this->info("Purged {$count} old audit log(s).");
        } else {
            $this->info("No old audit logs to purge.");
        }

        return Command::SUCCESS;
    }
}
