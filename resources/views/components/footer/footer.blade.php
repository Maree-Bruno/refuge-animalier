<footer class="bg-blueslate text-lightgray grid grid-cols-2 gap-5 py-5 px-2.5 md:grid-cols-4 md:p-10 md:gap-10">
    <h2 class="sr-only">Bas de page</h2>
    <div class="flex flex-col gap-2.5 lg:justify-center lg:items-center">
        <a href="{{route('homepage')}}" class=" w-fit h-auto">
            <x-svg.logo class="big-logo w-[150px] h-[80px] md:w-[230px] md:h-[150px]"/>
        </a>
        <div class="flex gap-2 w-fit items-center justify-center ml-3 lg:gap-5 lg:ml-0">
            <a href="https://facebook.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.fb class="svg-fillwhite"/>
            </a>
            <a href="https://instagram.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.instagram class="svg-fillwhite"/>
            </a>
            <a href="https://linkedin.com" class="nav-item nav-item-animation rounded-md p-1">
                <x-svg.linkedin class="svg-fillwhite"/>
            </a>
        </div>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle">Nos informations</h3>
        <div class="flex flex-col gap-1.5">
            <div>
                <p class="text-small">Rue de Genville <span class="text-small">334</span></p>
                <p class="flex gap-[10px] text-sm"><span class="text-small">8980</span>Passendale</p>
            </div>
            <a href="tel:+32 4 93 33 41 64" class="text-small nav-item nav-item-animation w-fit py-1 rounded-sm">+32 4
                93 33
                41 64</a>
            <a href="mailto:happy@paws.com" class="text-small nav-item nav-item-animation w-fit p-1 rounded-sm">happy@paws.com</a>
        </div>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle">Navigation</h3>
        <nav>
            <ul class="flex flex-col gap-2 w-fit
                lg:space-x-5">
                <x-nav.nav_item
                    href="{{route('homepage')}}"
                    icon="home"
                    :color="true"
                    class="text-small p-1"
                >
                    Accueil
                </x-nav.nav_item>
                <x-nav.nav_item
                    href="{{route('homepage')}}"
                    icon="paws"
                    class="text-small p-1"
                >
                    HappyPaws
                </x-nav.nav_item>
                <x-nav.nav_item
                    href="{{route('homepage')}}"
                    icon="dog"
                    class="text-small p-1"
                >
                    Nos pensionnaires
                </x-nav.nav_item>
                <x-nav.nav_item
                    href="{{route('homepage')}}"
                    icon="volunteer"
                    class="text-small p-1"
                >
                    Devenez bénévole !
                </x-nav.nav_item>
                <x-nav.nav_item
                    href="{{route('homepage')}}"
                    icon="contact"
                    class="text-small p-1"
                >
                    Contactez-nous !
                </x-nav.nav_item>

            </ul>
        </nav>
    </div>
    <div class="flex flex-col gap-2.5">
        <h3 class="subtitle font-bold">Nos horaires</h3>
        <div>
            <p class="font-semibold">Lun - Ven</p>
            <p class="text-small">9h00 - 18h00</p>
        </div>
        <div>
            <p class="font-semibold">Sam - Dim</p>
            <p class="text-small">10h00 - 17h00</p>
        </div>
    </div>
</footer>
