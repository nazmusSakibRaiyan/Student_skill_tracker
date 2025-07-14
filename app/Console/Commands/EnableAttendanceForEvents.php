<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class EnableAttendanceForEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:enable-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable attendance for all events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Enabling attendance for all events...');
        
        $updated = Event::whereNull('attendance_enabled')
            ->orWhere('attendance_enabled', false)
            ->update(['attendance_enabled' => true]);
            
        $this->info("Attendance enabled for {$updated} events.");
        
        return 0;
    }
}
