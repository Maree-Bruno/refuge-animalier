<footer class="bg-blueslate text-lightgray grid grid-cols-2 gap-7 py-5 px-2.5 md:grid-cols-4 md:p-10 md:gap-10">
    <h2 class="sr-only">{{ __('footer.title') }}</h2>
    <div class="flex flex-col gap-2.5 lg:justify-center lg:items-center">
        <a href="{{ route('homepage') }}" class="w-fit h-auto">
            <x-svg.logo class="big-logo w-[150px] h-[80px] md:w-[230px] md:h-[150px]"/>
        </a>
        <div class="flex gap-2 w-fit items-center justify-center ml-3 lg:gap-5 lg:ml-0">
            <a href="https://facebook.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.fb class="svg-fillwhite scale-125"/>
            </a>
            <a href="https://instagram.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.instagram class="svg-fillwhite"/>
            </a>
            <a href="https://linkedin.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.linkedin class="svg-fillwhite"/>
            </a>
            <div class="flex items-center gap-4">
                <x-nav.language-switcher/>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle">{{ __('footer.info.title') }}</h3>
        <div class="flex flex-col">
            <p class="text-small">{{ __('footer.info.address') }}</p>
            <a href="tel:+32493334164" class="text-small nav-item nav-item-animation w-fit py-1 rounded-sm">{{ __('footer.info.phone') }}</a>
            <a href="mailto:happy@paws.com" class="text-small nav-item nav-item-animation w-fit p-1 rounded-sm">{{ __('footer.info.email') }}</a>
        </div>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle">{{ __('footer.navigation.title') }}</h3>
        <nav>
            <ul class="flex flex-col gap-2 w-fit lg:space-x-5">
                <x-nav.nav_item href="{{ route('homepage') }}" icon="home" :color="true" class="text-small p-1">{{ __('footer.navigation.home') }}</x-nav.nav_item>
                <x-nav.nav_item href="{{ route('about') }}" icon="paws" class="text-small p-1">{{ __('footer.navigation.about') }}</x-nav.nav_item>
                <x-nav.nav_item href="{{ route('animals') }}" icon="dog" class="text-small p-1">{{ __('footer.navigation.animals') }}</x-nav.nav_item>
                <x-nav.nav_item href="{{ route('volunteer') }}" icon="volunteer" class="text-small p-1">{{ __('footer.navigation.volunteer') }}</x-nav.nav_item>
                <x-nav.nav_item href="{{ route('contact') }}" icon="contact" class="text-small p-1">{{ __('footer.navigation.contact') }}</x-nav.nav_item>
            </ul>
        </nav>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle font-bold">{{ __('footer.hours.title') }}</h3>
        <div>
            <p class="font-semibold">{{ __('footer.hours.weekdays') }}</p>
            <p class="text-small">{{ __('footer.hours.weekdays_hours') }}</p>
        </div>
        <div>
            <p class="font-semibold">{{ __('footer.hours.weekend') }}</p>
            <p class="text-small">{{ __('footer.hours.weekend_hours') }}</p>
        </div>
    </div>
</footer>
