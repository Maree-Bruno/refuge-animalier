@php use Illuminate\Support\Facades\URL; @endphp
<nav class="flex flex-col items-center justify-between bg-blueslate lg:flex-row lg:h-fit">
    <div class="flex justify-between w-full py-4 px-5 items-center lg:h-fit lg:gap-12">
        <a href="{{route('homepage')}}" class="z-20">
            <figure>
                <picture>
                    <img
                        src="{{URL('images/logo.webp')}}"
                        alt="{{__('logo')}}"
                        loading="lazy"
                        width="132"
                        height="79"
                        class="w-10 h-6 lg:w-[90px] lg:h-auto"
                    >
                </picture>
                <figcaption class="sr-only">{{__('logo')}}</figcaption>
            </figure>
        </a>
        <div>
            <label for="burger" class="sr-only">Menu</label>
            <input type="checkbox" id="burger" name="burger">
            <div class="burger-wrapper">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="client-nav-list h-full p-10 lg:h-fit">
                <ul class="flex flex-col gap-10 justify-center text-lightgray lg:flex-row lg:justify-between
                lg:space-x-5">
                    <x-nav.nav_item
                        href="{{route('homepage')}}"
                        icon="home"
                        :color="true">
                        Accueil
                    </x-nav.nav_item>
                    <x-nav.nav_item
<<<<<<< Updated upstream
                        href="{{route('homepage')}}"
                        icon="paws">
                        HappyPaws
                    </x-nav.nav_item>
                    <x-nav.nav_item
                        href="{{route('homepage')}}"
                        icon="dog">
                        Nos pensionnaires
                    </x-nav.nav_item>
                    <x-nav.nav_item
                        href="{{route('homepage')}}"
                        icon="volunteer">
=======
                        href="{{route('about')}}"
                        icon="paws"
                        class="p-2 font-semibold"
                    >
                        HappyPaws
                    </x-nav.nav_item>
                    <x-nav.nav_item
                        href="{{route('animals')}}"
                        icon="dog"
                        class="p-2 font-semibold"
                    >
                        Nos pensionnaires
                    </x-nav.nav_item>
                    <x-nav.nav_item
                        href="{{route('volunteer')}}"
                        icon="volunteer"
                        class="p-2 font-semibold"
                    >
>>>>>>> Stashed changes
                        Devenez bénévole !
                    </x-nav.nav_item>
                    <li class="">
                        <x-buttons.button_link_icons class="button-yellow-animation button-yellow"
                                                     icon="contact"
                                                     :color="false"
                                                     href="{{route('contact')}}">
                            Contactez-nous !
                        </x-buttons.button_link_icons>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
