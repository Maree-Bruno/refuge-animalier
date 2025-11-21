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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="fixed top-2 left-2 z-50 px-2 py-1 text-white text-sm font-bold rounded bg-black/70">
    <span class="block sm:hidden">XS ( < 640px )</span>
    <span class="hidden sm:block md:hidden">SM ( ≥ 640px )</span>
    <span class="hidden md:block lg:hidden">MD ( ≥ 768px )</span>
    <span class="hidden lg:block xl:hidden">LG ( ≥ 1024px )</span>
    <span class="hidden xl:block 2xl:hidden">XL ( ≥ 1280px )</span>
    <span class="hidden 2xl:block">2XL ( ≥ 1536px )</span>
</div>
<header>
    <h1 class="sr-only">HappyPaws</h1>
    <x-nav.client_nav/>
</header>
<main class="p-5 lg:p-10">
    <section class="flex flex-col gap-5 leading-9">
        <h2 class="text-2xl font-bold">
            Parce que sauver un animal, c'est sauver le monde. Un cœur à la fois.
        </h2>
        <p class="text-xl leading-9">Chez Happy Paws, nous recueillons, soignons et replaçons les animaux abandonnés ou
            maltraités. Chaque
            adoption est une nouvelle chance, un nouveau départ, et un pas de plus vers un monde plus bienveillant.</p>
        <div class="w-fit flex flex-col gap-2.5">
            <x-buttons.button_link_icons icon="paws" class="button-yellow" :fillblack="true">
                Adoptez !
            </x-buttons.button_link_icons>
            <x-buttons.button_link_icons icon="arrow_right" class="button-orange flex-row-reverse">
                En savoir plus
            </x-buttons.button_link_icons>
        </div>
    </section>
    <section>
        <h2>Quelques chiffres</h2>
        <div>
            <span>60</span>
            <p>C’est le nombre d’adoption cette année</p>
        </div>
    </section>
    <section>
        <h2>Nos pensionnaires prêt à l’adoption</h2>
        <article>
            <h3>Billy</h3>
            <div>
                <x-svg.age/>
                <span>1 an</span>
            </div>
        </article>
    </section>
</main>
<x-footer.footer/>
</body>
</html>
