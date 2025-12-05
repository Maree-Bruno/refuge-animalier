<script setup>
import {computed, ref} from 'vue';
import AppLogoIcon from "@/components/AppLogoIcon.vue";
import {Form, Link, usePage} from "@inertiajs/vue3";
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
import HomeIconIcon from "@/components/widgets/svg/HomeIcon.vue";
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
import {Label} from "@/components/ui/label/index.ts";
import {Input} from "@/components/ui/input/index.ts";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";


// USER DATA
const page = usePage();
const user = page.props.auth.user;

const initials = user.name
    .split(" ")
    .filter(Boolean)
    .map(n => n[0])
    .join("")
    .toUpperCase();

const isPinned = ref(localStorage.getItem('sidebar_pinned') === '1');
// SIDEBAR STATE
const isHovered = ref(false);

const isCollapsed = computed(() => !isPinned.value && !isHovered.value);


const togglePin = () => {
    isPinned.value = !isPinned.value;
    localStorage.setItem('sidebar_pinned', isPinned.value ? '1' : '0');
};


const handleMouseEnter = () => {
    isHovered.value = true;
};

const handleMouseLeave = () => {
    isHovered.value = false;
};

const isActive = (href) => {
    const page = usePage();
    const current = page.url.split('?')[0];
    const target = new URL(href, window.location.origin).pathname;

    return current === target;
};

// NAVIGATION STRUCTURE
const navigation = [
    {
        label: null,
        items: [
            {title: "Dashboard", href: Dashboard, icon: HomeIcon},
        ]
    },
    {
        label: "Refuge",
        items: [
            {title: "Animaux", href: AnimalsIndexView, icon: DogIcon},
            {title: "Demande d'adoption", href: AdoptionRequestsIndexView, icon: FormInputIcon},
            {title: "Notes", href: NotesIndexView, icon: NotesIcon},
        ]
    },
    {
        label: "Admin",
        items: [
            {title: "Rapports", href: ReportsIndexView, icon: ReportsIcon},
            {title: "Base de données", href: DatabaseIndexView, icon: DatabaseIcon},
            {title: "Emails", href: EmailsIndexView, icon: EmailsIcon},
            {title: "Bénévoles", href: VolunteersIndexView, icon: VolunteerIcon},
        ]
    }
];

const props = defineProps({'title': String,})
</script>


<template>
    <header>
        <transition
            enter-active-class="transition-all duration-300"
            leave-active-class="transition-all duration-200"
            enter-from-class="w-16"
            leave-to-class="w-64"
        >
            <aside
                :class="[
                'fixed top-0 left-0 z-40 h-screen bg-blueslate text-white',
                isCollapsed ? 'w-16' : 'w-64'
            ]"
                @mouseenter="handleMouseEnter"
                @mouseleave="handleMouseLeave"
                aria-label="Sidebar"
            >
                <nav class="h-full flex flex-col mt-4 shadow-xl">
                    <div class="flex items-center justify-between mb-4 border-b border-b-white/20 mx-2">
                        <transition
                            enter-active-class="transition-all duration-200"
                            leave-active-class="transition-all duration-200"
                            enter-from-class="sr-only"
                            leave-to-class="sr-only"
                        >
                            <AppLogoIcon
                                v-if="!isCollapsed"
                                class="w-32 h-full max-h-fit"
                            />
                        </transition>
                        <button
                            @click="togglePin"
                            class="p-2 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                            :class="{ 'mx-auto': isCollapsed }"
                            :aria-label="isPinned ? 'Désépingler la navigation' : 'Épingler la navigation'"
                            :title="isPinned ? 'Désépingler la navigation' : 'Épingler la navigation'"
                        >
                            <SidebarIcon class="svg-strokewhite w-6 h-6"/>
                        </button>
                        <h2 class="sr-only">Navigation</h2>
                    </div>

                    <div class="px-4 py-6 space-y-6 overflow-y-scroll flex-1">
                        <template v-for="(section, index) in navigation" :key="index">
                            <ul class="space-y-2">
                                <li
                                    v-if="section.label && !isCollapsed"
                                    class="text-gray-300 smalltext px-2"
                                >
                                    <span>{{ section.label }}</span>
                                </li>

                                <NavItem
                                    v-for="item in section.items"
                                    :key="item.title"
                                    :title="item.title"
                                    :href="item.href()"
                                    :collapsed="isCollapsed"
                                    :active="isActive(item.icon) "
                                >
                                    <template #icon>
                                        <component :is="item.icon" class="svg-strokewhite w-6 h-6"/>
                                    </template>
                                </NavItem>

                            </ul>
                            <div
                                v-if="index < navigation.length - 1 && isCollapsed"
                                class="my-2 border-t border-white/20"
                                :class="isCollapsed ? 'mx-0.5 my-10' : 'mx-4'"
                            ></div>
                        </template>
                    </div>

                    <div class="mt-auto border-t border-white/20 bg-black/10">
                        <Link
                            :href="ProfileEdit()"
                            :class="[
                            'flex items-center gap-3 py-4 hover:bg-white/10 transition-all group',
                            isCollapsed ? 'px-2 justify-center' : 'px-4'
                        ]"
                        >
                            <div class="flex gap-2 min-w-0">
                                <div class="relative group/avatar">
                                    <img
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                        :alt="`Avatar de ${user.name}`"
                                        class="w-10 aspect-square rounded-full object-cover"
                                    />
                                    <span
                                        v-else
                                        class="w-10 aspect-square rounded-full flex items-center justify-center bg-sweetorange text-white"
                                    >
                                    {{ initials }}
                                </span>
                                    <div
                                        v-if="isCollapsed"
                                        class="absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover/avatar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-lg"
                                    >
                                        {{ user.name }}
                                    </div>
                                </div>

                                <div v-if="!isCollapsed">
                                    <p class="text-white font-semibold text-sm truncate">{{ user.name }}</p>
                                    <p class="text-white/80 text-xs truncate">{{ user.email }}</p>
                                </div>
                            </div>
                            <svg
                                v-if="!isCollapsed"
                                class="w-4 h-4 text-white/60 group-hover:text-white transition-colors ml-auto"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>

                        <div
                            :class="[
                            'py-4 border-t mb-4 border-white/10 flex justify-center items-center w-full',
                            isCollapsed ? 'px-2' : 'px-2'
                        ]"
                        >
                            <ButtonSvg
                                title="Se déconnecter"
                                :href="logout()"
                                method="post"
                                :collapsed="isCollapsed"
                                class="w-full"
                            >
                                <template #icon>
                                    <LogoutIcon class="svg-strokeblue w-6 h-6"/>
                                </template>
                            </ButtonSvg>
                        </div>
                    </div>
                </nav>
            </aside>
        </Transition>

    </header>
    <main
        :class="[
            'p-4 md:p-6 transition-all duration-300 space-y-20',
            isCollapsed ? 'ml-16' : 'sm:ml-64'
        ]"
    >
        <div class="flex items-center justify-between mt-5 mb-10">
            <h2 class="title">{{ title }}</h2>
            <div class="flex items-center justify-center gap-4">
                <div>
                    <button
                        class="flex gap-4 justify-between items-center md:border-2 md:border-lightblueslate/50 p-1 rounded-lg">
                        <span class="flex gap-2 text-lightblueslate/50">
                            <LoupeIcon
                                class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
                            <span class="sr-only md:not-sr-only">Rechercher</span>
                        </span>
                        <span
                            class="opacity-30 border p-2 rounded-sm sr-only md:not-sr-only bg-lightgray">⌘ K</span>
                    </button>
                </div>
                <BellIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-6 min-h-6"/>
            </div>
        </div>

        <slot/>
    </main>
</template>
