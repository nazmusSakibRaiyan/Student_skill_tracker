<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Map role name to role_id
        $roleMap = [
            'student' => Role::where('name', 'student')->first()->id,
            'club-manager' => Role::where('name', 'club_manager')->first()->id,
        ];

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleMap[$request->role],
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user role
        if ($user->isMasterAdmin()) {
            return redirect(route('admin.dashboard'));
        } elseif ($user->isClubManager()) {
            return redirect(route('club-manager.dashboard'));
        } else {
            return redirect(route('student.dashboard'));
        }
    }
}
