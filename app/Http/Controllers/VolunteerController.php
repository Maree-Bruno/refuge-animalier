<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Events\VolunteerCreatedEvent;
use App\Jobs\ProcessUploadedImage;
use App\Mail\VolunteerCreatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $orderby = $request->get('orderby', 'name');
        $dir = $request->get('dir', 'asc');
        $roles = collect(UserRole::cases())->map(fn ($role) => [
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
            'roles'=>$roles,
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'role' => 'required|in:admin,volunteer',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $plainPassword = Str::random(12);
        $storedImage = null;

        if ($request->hasFile('picture')) {
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

            if ($storedPath) {
                $storedImage = $filename;

                ProcessUploadedImage::dispatch(
                    $storedPath,
                    $filename,
                    'userimage'
                );
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($plainPassword),
            'role' => $validated['role'],
            'picture' => $storedImage,
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

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
