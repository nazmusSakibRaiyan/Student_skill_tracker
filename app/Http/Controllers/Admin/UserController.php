<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        $query = User::with('role');
        if ($role) {
            $query->whereHas('role', function($q) use ($role) {
                $q->where('name', $role);
            });
        }
        $users = $query->orderBy('name')->paginate(20);
        $totalUsers = User::count();
        return view('admin.users', compact('users', 'role', 'totalUsers'));
    }

    public function banClubManager(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubManager = \App\Models\ClubManager::where('user_id', $request->user_id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$clubManager) {
            return back()->with('error', 'Club manager not found.');
        }

        $clubManager->banned = true;
        $clubManager->save();

        return back()->with('success', 'Club manager banned successfully.');
    }

    public function unbanClubManager(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubManager = \App\Models\ClubManager::where('user_id', $request->user_id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$clubManager) {
            return back()->with('error', 'Club manager not found.');
        }

        $clubManager->banned = false;
        $clubManager->save();

        return back()->with('success', 'Club manager unbanned successfully.');
    }

    public function manageRoles(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::with(['role', 'clubManagers.club']);
        
        // Filter by role if specified
        if ($role !== 'all') {
            $query->whereHas('role', function($q) use ($role) {
                $q->where('name', $role);
            });
        } else {
            // Only show students and club managers (not master admins)
            $query->whereHas('role', function($q) {
                $q->whereIn('name', ['student', 'club_manager']);
            });
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        // Get statistics
        $stats = [
            'total_students' => User::whereHas('role', fn($q) => $q->where('name', 'student'))->count(),
            'total_club_managers' => User::whereHas('role', fn($q) => $q->where('name', 'club_manager'))->count(),
            'banned_club_managers' => \App\Models\ClubManager::where('banned', true)->count(),
        ];
        
        return view('admin.manage-roles', compact('users', 'role', 'stats'));
    }

    public function deleteUser($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            
            // Prevent deletion of master admin accounts
            if ($user->isMasterAdmin()) {
                return back()->with('error', 'Cannot delete master admin accounts.');
            }

            $userEmail = $user->email;
            $userName = $user->name;

            // Log the deletion
            \Log::info('User deleted by master admin', [
                'deleted_user' => $userEmail,
                'admin' => auth()->user()->email
            ]);

            // Delete the user (cascade should handle related records)
            $user->delete();

            return back()->with('success', "User '{$userName}' ({$userEmail}) has been deleted successfully.");
            
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'user_id' => $user_id,
                'error' => $e->getMessage(),
                'admin' => auth()->user()->email
            ]);
            
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function showImportForm()
    {
        return view('admin.import-users');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            
            $imported = 0;
            $skipped = 0;
            $errors = [];

            if (($handle = fopen($path, 'r')) !== FALSE) {
                $header = fgetcsv($handle); // Read header row
                
                // Validate header
                $expectedHeaders = ['name', 'email', 'role', 'password'];
                $headerMap = [];
                foreach ($expectedHeaders as $expected) {
                    $index = array_search(strtolower($expected), array_map('strtolower', $header));
                    if ($index === false && $expected !== 'password') {
                        throw new \Exception("Required column '{$expected}' not found in CSV");
                    }
                    $headerMap[$expected] = $index;
                }

                while (($row = fgetcsv($handle)) !== FALSE) {
                    try {
                        $name = $row[$headerMap['name']] ?? '';
                        $email = $row[$headerMap['email']] ?? '';
                        $role = $row[$headerMap['role']] ?? '';
                        $password = isset($headerMap['password']) ? ($row[$headerMap['password']] ?? 'password123') : 'password123';

                        // Validate data
                        if (empty($name) || empty($email) || empty($role)) {
                            $skipped++;
                            $errors[] = "Missing required data for row with email: {$email}";
                            continue;
                        }

                        // Check if user already exists
                        if (User::where('email', $email)->exists()) {
                            $skipped++;
                            $errors[] = "User with email {$email} already exists - skipped";
                            continue;
                        }

                        // Get role
                        $roleObj = Role::where('name', strtolower($role))->first();
                        if (!$roleObj) {
                            $skipped++;
                            $errors[] = "Invalid role '{$role}' for {$email} - skipped";
                            continue;
                        }

                        // Create user
                        $user = User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => \Hash::make($password),
                            'email_verified_at' => now(),
                        ]);

                        // Assign role
                        $user->role_id = $roleObj->id;
                        $user->save();

                        $imported++;

                    } catch (\Exception $e) {
                        $skipped++;
                        $errors[] = "Error importing {$email}: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }

            $message = "Import completed! ";
            $message .= "Imported: {$imported} users. ";
            
            if ($skipped > 0) {
                $message .= "Skipped: {$skipped} users. ";
            }

            if (!empty($errors)) {
                $message .= "Errors occurred - check logs.";
                \Log::warning('User import errors', [
                    'errors' => $errors,
                    'admin' => auth()->user()->email
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('User import failed', [
                'error' => $e->getMessage(),
                'admin' => auth()->user()->email
            ]);
            
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="user_import_template.csv"',
        ];

        $template = "name,email,role,password\n";
        $template .= "John Doe,john@example.com,student,password123\n";
        $template .= "Jane Smith,jane@example.com,club_manager,password123\n";
        $template .= "Admin User,admin@example.com,master_admin,password123\n";

        return response($template, 200, $headers);
    }
}
