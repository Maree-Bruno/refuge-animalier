<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Jobs\ProcessUploadedImage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'title' => 'Profil',
            'picture' => $request->user()->picture,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
            if ($user->picture) {
                Storage::disk(config('userimage.disk'))->delete(
                    config('userimage.original_path').'/'.$user->picture
                );
            }
            $filename = Str::uuid().'.webp';
            $originalPath = Storage::disk(config('userimage.disk'))->putFileAs(
                config('userimage.original_path'),
                $image,
                $filename
            );
            if ($originalPath) {
                $user->picture = $filename;
                ProcessUploadedImage::dispatchSync(
                    $originalPath,
                    $filename,
                    'userimage'
                );
                $user->update(['picture' => $filename]);
            }
        }
        $user->save();

        return to_route('profile.edit');
    }


    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
