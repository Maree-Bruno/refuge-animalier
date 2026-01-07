<nav class="w-full bg-blueslate lg:h-fit lg:py-5">
    <div class="flex justify-between w-full py-4 px-5 items-center lg:h-fit lg:gap-8">
        <a href="{{ route('homepage') }}" class="z-20">
            <x-svg.logo class="mid-logo w-10 h-6 lg:w-32 lg:h-14"/>
        </a>
        <div>
            <label for="burger" class="sr-only">{{ __('navbar.menu') ?? 'Menu' }}</label>
            <input type="checkbox" id="burger" name="burger">
            <div class="burger-wrapper">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="client-nav-list flex flex-col items-center h-full p-10 mt-6 lg:h-fit lg:mt-0">
                <ul class="flex flex-col gap-5 justify-center text-lightgray w-fit lg:flex-row lg:justify-between lg:space-x-5">
                    <x-nav.nav_item
                        href="{{ route('homepage') }}"
                        icon="home"
                        :color="true"
                        class="p-2 font-semibold"
                    >
                        {{ __('nav/nav_items.home') }}
                    </x-nav.nav_item>

                    <x-nav.nav_item
                        href="{{ route('about') }}"
                        icon="paws"
                        class="p-2 font-semibold"
                    >
                        {{ __('nav/nav_items.about') }}
                    </x-nav.nav_item>

                    <x-nav.nav_item
                        href="{{ route('animals') }}"
                        icon="dog"
                        class="p-2 font-semibold"
                    >
                        {{ __('nav/nav_items.animals') }}
                    </x-nav.nav_item>

                    <x-nav.nav_item
                        href="{{ route('volunteer') }}"
                        icon="volunteer"
                        class="p-2 font-semibold"
                    >
                        {{ __('nav/nav_items.volunteer') }}
                    </x-nav.nav_item>

                    <li>
                        <x-buttons.button_link_icons
                            class="button-yellow"
                            icon="contact"
                            :color="false"
                            href="{{ route('contact') }}"
                        >
                            {{ __('nav/nav_items.contact') }}
                        </x-buttons.button_link_icons>
                    </li>
                    <li class="flex items-center gap-4">
                        <x-nav.language-switcher/>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
