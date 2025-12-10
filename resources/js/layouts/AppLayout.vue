<script setup>
import {computed, ref, watch} from 'vue';
import AppLogoIcon from "@/components/AppLogoIcon.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import HomeIcon from "@/components/widgets/svg/HomeIcon.vue";
import ButtonSvg from "@/components/widgets/button/ButtonSvg.vue";
import SidebarIcon from "@/components/widgets/svg/SidebarIcon.vue";
import DogIcon from "@/components/widgets/svg/DogIcon.vue";
import FormInputIcon from "@/components/widgets/svg/FormInputIcon.vue";
import NotesIcon from "@/components/widgets/svg/NotesIcon.vue";
import ReportsIcon from "@/components/widgets/svg/ReportsIcon.vue";
import DatabaseIcon from "@/components/widgets/svg/DatabaseIcon.vue";
import EmailsIcon from "@/components/widgets/svg/EmailsIcon.vue";
import VolunteerIcon from "@/components/widgets/svg/VolunteerIcon.vue";

// NavItem + Icon
import NavItem from "@/components/widgets/nav/NavItem.vue";

// Routes
import {index as Dashboard} from "@/actions/App/Http/Controllers/DashboardController";
import {index as AnimalsIndexView} from "@/actions/App/Http/Controllers/AnimalController";
import {index as ReportsIndexView} from "@/actions/App/Http/Controllers/ReportController";
import {index as AdoptionRequestsIndexView} from "@/actions/App/Http/Controllers/AdoptionRequestController";
import {index as NotesIndexView} from "@/actions/App/Http/Controllers/NoteController";
import {index as DatabaseIndexView} from "@/actions/App/Http/Controllers/DatabaseController";
import {index as EmailsIndexView} from "@/actions/App/Http/Controllers/EmailController";
import {index as VolunteersIndexView} from "@/actions/App/Http/Controllers/VolunteerController";
import {edit as ProfileEdit} from '@/routes/profile';
import {logout} from "@/routes/index.ts";
import LogoutIcon from "@/components/widgets/svg/LogoutIcon.vue";
import BellIcon from "@/components/widgets/svg/BellIcon.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import NavHeader from "@/components/widgets/nav/NavHeader.vue";
import {useSidebar} from "@/composables/useIsSidebar.js";


// USER DATA
const page = usePage();
const title = computed(() => page.props.title || 'Page sans titre');
const user = computed(() => page.props.auth?.user || null);
const {
    isCollapsed,
} = useSidebar();
const props = defineProps({'title': String,})
let search = ref('');
watch(search, value => {
    router.get('/dashboard', {search: value}, {
        preserveState: true,
        replace:true
    })
});
const showSearchBar = ref(false);
</script>


<template>
    <Head :title="title"/>
    <NavHeader/>
    <main
        v-if="user"
        :class="[
            'p-4 md:p-6 transition-all duration-300 space-y-20',
            user ? (isCollapsed ? 'ml-16' : 'sm:ml-64') : ''
        ]"
    >
        <div class="max-w-7xl mx-auto transition-all duration-300 space-y-20">
            <div v-if="user" class="flex items-center justify-between mt-5 mb-10">
                <h2 class="title">{{ title }}</h2>
                <div class="flex items-center justify-center gap-4">
                    <button @click="showSearchBar = true"
                            class="flex gap-4 justify-between items-center md:border-2 md:border-lightblueslate/50 p-1 rounded-lg">
                            <span class="flex gap-2 text-lightblueslate/50">
                                 <LoupeIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
                                 <span class="sr-only md:not-sr-only">Rechercher</span>
                            </span>
                        <span class="opacity-30 border p-2 rounded-sm sr-only md:not-sr-only bg-lightgray">⌘ K</span>
                    </button>
                    <BellIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-6 min-h-6"/>
                </div>
            </div>

            <slot/>
        </div>

    </main>

    <slot v-else/>
    <Teleport to="body">
        <Transition
            enter-from-class="opacity-0 scale-125"
            enter-to-class="opacity-100 scale-100"
            enter-active-class="transition duration-300"
            leave-active-class="transition duration-200"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-125"
        >
        <div class="bg-gray-700/30 backdrop-blur-xs fixed inset-0 z-50 flex items-center justify-center"
             @click.self="showSearchBar = false"
             @keydown.esc="showSearchBar = false"
             v-show="showSearchBar"
        >
            <div class="bg-white p-6 rounded-3xl shadow-xl max-w-2xl w-full ">
                <div
                    class="flex gap-4 justify-between items-center md:border-2 md:border-lightblueslate/50 p-1 rounded-lg">
                    <LoupeIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
                    <input type="text" placeholder="Search... " v-model="search" class="flex-1 outline-none">
                    <button @click="showSearchBar = false" class="opacity-30 border p-2 rounded-sm bg-lightgray">
                        ESC
                    </button>
                </div>
            </div>
        </div>
        </Transition>
    </Teleport>
</template>
