<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Open profile page
    public function profile()
    {
        $userId = Session::get('user_id');
        $profile = User::where('id', $userId)->first();

        if (!$profile) {
            return redirect('/')->withErrors(['error' => 'User not found']);
        }

        return view('profile', compact('profile'));
    }

    // Update profile
    public function profileUpdate(Request $request)
    {
        $userToken = Session::get('user_token');
        $profile = User::where('remember_token', $userToken)->first();

        if (!$profile) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'candidateName' => 'required|string|max:255',
            'candidatePosition' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png|max:100',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $profile->name = $validatedData['candidateName'];
        $profile->role = $validatedData['candidatePosition'];

        if ($request->hasFile('image')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $path = $request->file('image')->store('avatars', 'public');
            $profile->avatar = $path;
        }

        $profile->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
