<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Events\VolunteerCreatedEvent;
use App\Http\Requests\VolunteerRequest;
use App\Jobs\ProcessUploadedImage;
use App\Mail\VolunteerCreatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $orderby = $request->get('orderby', 'name');
        $dir = $request->get('dir', 'asc');
        $roles = collect(UserRole::cases())->map(fn($role) => [
            'value' => $role->value,
            'label' => $role->label(),
        ]);

        $volunteers = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy($orderby, $dir)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('VolunteersIndexView', [
            'title' => "Bénévoles",
            'volunteers' => $volunteers,
            'filters' => [
                'search' => $search,
                'orderby' => $orderby,
                'dir' => $dir,
            ],
            'roles' => $roles,
        ]);
    }

    public function create()
    {
    }

    public function store(VolunteerRequest $request)
    {
        $validated = $request->validated();
        $plainPassword = Str::random(12);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'number' => $validated['number'],
            'city' => $validated['city'],
            'cp' => $validated['cp'],
            'password' => Hash::make($plainPassword),
            'role' => $validated['role'],
            'picture' => $this->handleImageUpload($request),
        ]);

        Mail::to($user->email)->send(new VolunteerCreatedMail($user, $plainPassword));

        return back();
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(VolunteerRequest $request, User $volunteer)
    {
        $validated = $request->validated();

        $volunteer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'number' => $validated['number'],
            'city' => $validated['city'],
            'cp' => $validated['cp'],
            'role' => $validated['role'],
            'picture' => $this->handleImageUpload($request) ?? $volunteer->picture,
        ]);

        return back();
    }

    public function destroy(Request $request, User $volunteer)
    {
        if (!empty($volunteer->pictures)) {
            $this->deleteImage($request, $volunteer);
        }
        $volunteer->delete();

        return back();
    }


    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('picture')) {
            return null;
        }

        $image = $request->file('picture');
        $filename = Str::uuid().'.webp';

        $config = config('userimage');
        $disk = $config['disk'];
        $originalPath = $config['original_path'];

        $storedPath = Storage::disk($disk)->putFileAs(
            $originalPath,
            $image,
            $filename
        );

        if (!$storedPath) {
            return null;
        }

        ProcessUploadedImage::dispatch(
            $storedPath,
            $filename,
            'userimage'
        );

        return $filename;
    }

    public function deleteImage(Request $request, User $volunteer)
    {
        $validated = $request->validate([
            'filename' => 'required|string'
        ]);

        $filename = $validated['filename'];


        Storage::disk(config('userimage.disk'))->delete(
            config('userimage.original_path').'/'.$filename
        );

        $sizes = ['64x64', '128x128', '256x256', '512x512'];
        foreach ($sizes as $size) {
            Storage::disk(config('userimage.disk'))->delete(
                "images/users/variants/{$size}/{$filename}"
            );
        }

        $picture = null;

        $volunteer->update(['picture' => $picture]);

        return back();
    }
}
