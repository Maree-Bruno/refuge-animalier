<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'picture' => $request->user()->picture ?? null,
                'role' => $request->user()->role ?? null,
                'name' => $request->user()->name ?? null,
                'email' => $request->user()->email ?? null,
                'phone' => $request->user()->phone ?? null,
                'address' => $request->user()->address ?? null,
                'city' => $request->user()->city ?? null,
                'number' => $request->user()->number ?? null,
                'cp' => $request->user()->cp ?? null,
                'availability' => $request->user()->availability ?? null,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'storage' => [
                'animals' => Storage::disk('images')->url(''),
                'users' => Storage::disk('userimages')->url(''),
            ],
        ];
    }
}
