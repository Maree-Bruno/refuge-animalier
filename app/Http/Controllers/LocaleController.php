<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $availableLocales = config('app.available_locales', ['en', 'fr', 'de', 'nl']);
        if (!in_array($locale, $availableLocales)) {
            abort(400, 'Invalid locale');
        }
        Session::put('locale', $locale);
        return redirect()->back();
    }
}
