<?php

// Quick script to check events and enable attendance
use Illuminate\Support\Facades\DB;
use App\Models\Event;

$events = Event::all();

echo "Total events: " . $events->count() . "\n";

foreach ($events as $event) {
    echo "Event: {$event->name}\n";
    echo "  - ID: {$event->id}\n";
    echo "  - Club: {$event->club->name}\n";
    echo "  - Attendance Enabled: " . ($event->attendance_enabled ? 'Yes' : 'No') . "\n";
    echo "  - QR Code: " . ($event->qr_code ?? 'Not generated') . "\n";
    echo "  - Start Date: {$event->start_date}\n";
    echo "\n";
}

// Enable attendance for all events
$updated = Event::where('attendance_enabled', false)->orWhereNull('attendance_enabled')->update(['attendance_enabled' => true]);
echo "Updated {$updated} events to enable attendance.\n";

// Show updated status
echo "\nAfter update:\n";
$events = Event::all();
foreach ($events as $event) {
    echo "Event: {$event->name} - Attendance: " . ($event->attendance_enabled ? 'Enabled' : 'Disabled') . "\n";
}
