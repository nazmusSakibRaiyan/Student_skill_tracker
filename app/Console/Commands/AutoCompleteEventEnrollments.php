<?php

namespace App\Console\Commands;

use App\Models\EventEnrollment;
use Illuminate\Console\Command;

class AutoCompleteEventEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:auto-complete
                            {--dry-run : Show what would be completed without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically mark event enrollments as completed when events end';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        // Find all enrollments that should be auto-completed
        $enrollments = EventEnrollment::autoCompletable()
            ->with(['event', 'user'])
            ->get();

        if ($enrollments->isEmpty()) {
            $this->info('No enrollments found that need to be auto-completed.');
            return 0;
        }

        $this->info("Found {$enrollments->count()} enrollment(s) to auto-complete:");

        $completed = 0;
        foreach ($enrollments as $enrollment) {
            $eventName = $enrollment->event->name;
            $studentName = $enrollment->user->name;
            $endDate = $enrollment->event->end_date->format('Y-m-d H:i:s');

            if ($dryRun) {
                $this->line("Would complete: {$studentName} -> {$eventName} (ended: {$endDate})");
            } else {
                $enrollment->markAsCompleted();
                $this->line("✅ Completed: {$studentName} -> {$eventName} (ended: {$endDate})");
                $completed++;
            }
        }

        if ($dryRun) {
            $this->warn("Dry run completed. Use --dry-run=false to actually complete the enrollments.");
        } else {
            $this->info("Successfully auto-completed {$completed} enrollment(s).");
        }

        return 0;
    }
}
