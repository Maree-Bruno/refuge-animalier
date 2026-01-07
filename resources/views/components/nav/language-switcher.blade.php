@php
    $languages = [
        'en' => ['flag' => '🇬🇧'],
        'fr' => ['flag' => '🇫🇷'],
        'de' => ['flag' => '🇩🇪'],
        'nl' => ['flag' => '🇳🇱'],
    ];
    $currentLocale = app()->getLocale();
@endphp

<select
    onchange="window.location.href = this.value"
    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer">
    @foreach($languages as $code => $language)
        <option
            value="{{ route('locale.switch', $code) }}"
            {{ $currentLocale === $code ? 'selected' : '' }}>
            {{ $language['flag'] }}
        </option>
    @endforeach
</select>
