<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => auth()->user(),
            'canEditIdentity' => auth()->user()->can('edit account identity'),
        ]);
    }

    /**
     * Update data profil.
     * Nama & foto bisa diubah semua user. Username & email hanya bisa
     * diubah oleh user yang punya permission 'edit account identity'
     * (Super Admin otomatis lolos lewat Gate::before).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $canEditIdentity = $user->can('edit account identity');

        $rules = [
            'name'    => ['required', 'string', 'max:255'],
            'profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        if ($canEditIdentity) {
            $rules['username'] = [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ];
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ];
        }

        // validate() hanya mengembalikan field yang ada di $rules,
        // jadi username/email dari user tanpa izin otomatis diabaikan.
        $validated = $request->validate($rules);

        if ($request->hasFile('profile')) {
            // Hapus foto lama kalau ada
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            $validated['profile'] = $request->file('profile')->store('profiles', 'public');
        }

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password akun.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}