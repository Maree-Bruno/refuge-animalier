<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AvailabilityController extends Controller
{
    /**
     * Show the user's availability settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $defaultAvailability = [
            ['id' => 1, 'period' => 'Matin', 'monday' => false, 'tuesday' => false, 'wednesday' => false, 'thursday' => false, 'friday' => false, 'saturday' => false, 'sunday' => false],
            ['id' => 2, 'period' => 'Après-Midi', 'monday' => false, 'tuesday' => false, 'wednesday' => false, 'thursday' => false, 'friday' => false, 'saturday' => false, 'sunday' => false],
            ['id' => 3, 'period' => 'Soir', 'monday' => false, 'tuesday' => false, 'wednesday' => false, 'thursday' => false, 'friday' => false, 'saturday' => false, 'sunday' => false],
        ];

        return Inertia::render('settings/Availability', [
            'title' => 'Disponibilité',
            'availability' => $user->availability ?? $defaultAvailability,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'availability' => 'required|array',
            'availability.*.id' => 'required|integer',
            'availability.*.period' => 'required|string',
            'availability.*.monday' => 'required|boolean',
            'availability.*.tuesday' => 'required|boolean',
            'availability.*.wednesday' => 'required|boolean',
            'availability.*.thursday' => 'required|boolean',
            'availability.*.friday' => 'required|boolean',
            'availability.*.saturday' => 'required|boolean',
            'availability.*.sunday' => 'required|boolean',
        ]);

        $request->user()->update([
            'availability' => $validated['availability']
        ]);

        return redirect()->back()->with('success', 'Disponibilités mises à jour avec succès.');
    }
}
