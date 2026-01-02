<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Document') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
</head>
<body class="bg-lightgray">
<div class="fixed top-2 left-2 z-50 px-2 py-1 text-white text-sm font-bold rounded bg-black/70">
    <span class="block sm:hidden">XS ( < 640px )</span>
    <span class="hidden sm:block md:hidden">SM ( ≥ 640px )</span>
    <span class="hidden md:block lg:hidden">MD ( ≥ 768px )</span>
    <span class="hidden lg:block xl:hidden">LG ( ≥ 1024px )</span>
    <span class="hidden xl:block 2xl:hidden">XL ( ≥ 1280px )</span>
    <span class="hidden 2xl:block">2XL ( ≥ 1536px )</span>
</div>
<header class="">
    <h1 class="sr-only">{{ config('app.name') }}</h1>
    <x-nav.client_nav/>
</header>
<main class="space-y-10 transition-all lg:space-y-20 ">
{{$slot}}
</main>
<x-footer.footer/>
</body>
</html>
