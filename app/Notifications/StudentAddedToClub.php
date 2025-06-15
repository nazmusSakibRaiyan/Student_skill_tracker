<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Club;
use App\Models\User;

class StudentAddedToClub extends Notification implements ShouldQueue
{
    use Queueable;

    public $club;
    public $student;

    public function __construct(Club $club, User $student)
    {
        $this->club = $club;
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Student Pending Approval')
            ->line('A new student has been added to the club: ' . $this->club->name)
            ->line('Student: ' . $this->student->name . ' (' . $this->student->email . ')')
            ->action('Approve Students', url('/admin/pending-students'));
    }

    public function toArray($notifiable)
    {
        return [
            'club_id' => $this->club->id,
            'club_name' => $this->club->name,
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'student_email' => $this->student->email,
        ];
    }
}
