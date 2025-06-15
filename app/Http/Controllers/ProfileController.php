<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $user = Auth::user();
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
            }
            $file = $request->file('profile_picture');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public');
            $user->profile_picture = $filename;
            $user->save();
        }
        return back()->with('success', 'Profile picture updated successfully.');
    }
}
