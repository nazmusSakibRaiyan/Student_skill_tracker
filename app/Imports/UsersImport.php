<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $errors = [];
    protected $imported = 0;
    protected $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Check if user already exists
                if (User::where('email', $row['email'])->exists()) {
                    $this->skipped++;
                    $this->errors[] = "User with email {$row['email']} already exists - skipped";
                    continue;
                }

                // Get role
                $role = Role::where('name', strtolower($row['role']))->first();
                if (!$role) {
                    $this->skipped++;
                    $this->errors[] = "Invalid role '{$row['role']}' for {$row['email']} - skipped";
                    continue;
                }

                // Create user
                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password'] ?? 'password123'),
                    'email_verified_at' => now(),
                ]);

                // Assign role
                $user->role_id = $role->id;
                $user->save();

                $this->imported++;

            } catch (\Exception $e) {
                $this->skipped++;
                $this->errors[] = "Error importing {$row['email']}: " . $e->getMessage();
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['student', 'club_manager', 'master_admin'])],
            'password' => 'nullable|string|min:6',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }
}
