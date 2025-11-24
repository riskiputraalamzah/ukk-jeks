<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        if (!Gate::allows('admin')) {
            abort(403, 'Unauthorized');
        }

        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    if (!Gate::allows('admin')) {
        abort(403, 'Unauthorized');
    }

    $user = $request->user();

    // Update username
    // 
    if ($request->username !== null) {
    $user->username = $request->username;
}


    // Update email
    // if ($request->filled('email')) {
    //     $user->email = $request->email;
    // }

    // // Update nomor HP
    // if ($request->filled('no_hp')) {
    //     $user->no_hp = $request->no_hp;
    // }

    // Upload avatar
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    // Update password
    if ($request->filled('password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return Redirect::route('admin.dashboard')
        ->with('success', 'Profil berhasil diperbarui!');
}



}
