<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminClubStudentController extends Controller
{
    public function pending()
    {
        $pendingStudents = DB::table('club_student')
            ->where('status', 'pending')
            ->join('clubs', 'club_student.club_id', '=', 'clubs.id')
            ->join('users', 'club_student.user_id', '=', 'users.id')
            ->select('club_student.club_id', 'club_student.user_id', 'clubs.name as club_name', 'users.name as student_name', 'users.email as student_email')
            ->get()
            ->map(function($row) {
                $row->club = Club::find($row->club_id);
                $row->student = User::find($row->user_id);
                return $row;
            });
        return view('admin.pending_students', ['pendingStudents' => $pendingStudents]);
    }

    public function approve($clubId, $userId)
    {
        DB::table('club_student')
            ->where('club_id', $clubId)
            ->where('user_id', $userId)
            ->update(['status' => 'approved']);
        return back()->with('success', 'Student approved.');
    }

    public function reject($clubId, $userId)
    {
        DB::table('club_student')
            ->where('club_id', $clubId)
            ->where('user_id', $userId)
            ->delete();
        return back()->with('success', 'Student rejected.');
    }
}
