<script setup lang="ts">

import {edit as ProfileEdit} from "@/routes/profile";
import {logout} from "@/routes";
import NavItem from "@/components/widgets/nav/NavItem.vue";
import {Link, usePage} from "@inertiajs/vue3";
import AppLogoIcon from "@/components/AppLogoIcon.vue";
import ButtonSvg from "@/components/widgets/button/ButtonSvg.vue";
import LogoutIcon from "@/components/widgets/svg/LogoutIcon.vue";
import SidebarIcon from "@/components/widgets/svg/SidebarIcon.vue";
import {computed, ref} from "vue";
import {index as Dashboard} from "@/actions/App/Http/Controllers/DashboardController";
import HomeIcon from "@/components/widgets/svg/HomeIcon.vue";
import {index as AnimalsIndexView} from "@/actions/App/Http/Controllers/AnimalController";
import DogIcon from "@/components/widgets/svg/DogIcon.vue";
import {index as AdoptionRequestsIndexView} from "@/actions/App/Http/Controllers/AdoptionRequestController";
import FormInputIcon from "@/components/widgets/svg/FormInputIcon.vue";
import {index as NotesIndexView} from "@/actions/App/Http/Controllers/NoteController";
import NotesIcon from "@/components/widgets/svg/NotesIcon.vue";
import {index as ReportsIndexView} from "@/actions/App/Http/Controllers/ReportController";
import ReportsIcon from "@/components/widgets/svg/ReportsIcon.vue";
import {index as DatabaseIndexView} from "@/actions/App/Http/Controllers/DatabaseController";
import DatabaseIcon from "@/components/widgets/svg/DatabaseIcon.vue";
import {index as EmailsIndexView} from "@/actions/App/Http/Controllers/EmailController";
import EmailsIcon from "@/components/widgets/svg/EmailsIcon.vue";
import {index as VolunteersIndexView} from "@/actions/App/Http/Controllers/VolunteerController";
import VolunteerIcon from "@/components/widgets/svg/VolunteerIcon.vue";
import {useSidebar} from "@/composables/useIsSidebar";
import {Locale, useNavigation} from "@/composables/useNavigation";

const page = usePage();
const user = computed(() => page.props.auth?.user || null);

const initials = computed(() => {
    if (!user.value?.name) return '';
    return user.value.name
        .split(" ")
        .filter(Boolean)
        .map(n => n[0])
        .join("")
        .toUpperCase();
});

const {
    isPinned,
    isCollapsed,
    togglePin,
    handleMouseEnter,
    handleMouseLeave
} = useSidebar();

const locale = ref<Locale>('fr');
const {navigation} = useNavigation(locale.value);

const changeLocale = (newLocale: Locale) => {
    locale.value = newLocale;
};
</script>

<template>
    <header v-if="user">
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-300 ease-in"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <aside
                :class="[
                'fixed top-0 left-0 z-40 h-screen bg-blueslate text-white transition-all duration-300 ease-in-out',
                isCollapsed ? 'w-16' : 'w-64'
            ]"
                @mouseenter="handleMouseEnter"
                @mouseleave="handleMouseLeave"
                aria-label="Sidebar"
            >
                <nav class="h-full flex flex-col mt-4 shadow-xl">
                    <div class="flex items-center justify-between mb-4 border-b border-b-white/20 mx-2 pb-3">
                        <Transition
                            enter-active-class="transition-all duration-300 ease-out delay-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            enter-from-class="opacity-0 -translate-x-4"
                            enter-to-class="opacity-100 translate-x-0"
                            leave-from-class="opacity-100 translate-x-0"
                            leave-to-class="opacity-0 -translate-x-4"
                        >
                            <AppLogoIcon
                                v-if="!isCollapsed"
                                class="w-32 h-full max-h-fit"
                            />
                        </Transition>
                        <button
                            @click="togglePin"
                            class="p-2 rounded-lg hover:bg-white/10 transition-all duration-200 ease-in-out cursor-pointer transform hover:scale-110"
                            :class="[
                            { 'mx-auto': isCollapsed },
                            isPinned ? 'bg-lightblueslate': ''
                        ]"
                            :aria-label="isPinned ? 'Désépingler la navigation' : 'Épingler la navigation'"
                            :title="isPinned ? 'Désépingler la navigation' : 'Épingler la navigation'"
                        >

                            <SidebarIcon
                                class="svg-strokewhite w-6 h-6 transition-transform duration-1000 ease-in-out"
                                :class="{ 'rotate-360': isPinned }"
                            />
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
                                    <Transition
                                        enter-active-class="transition-all duration-300 ease-out"
                                        leave-active-class="transition-all duration-200 ease-in"
                                        enter-from-class="opacity-0 translate-y-2"
                                        enter-to-class="opacity-100 translate-y-0"
                                        leave-from-class="opacity-100 translate-y-0"
                                        leave-to-class="opacity-0 translate-y-2"
                                    >
                                        <span>{{ section.label }}</span>
                                    </Transition>
                                </li>

                                <NavItem
                                    v-for="item in section.items"
                                    :key="item.title"
                                    :title="item.title"
                                    :href="item.href()"
                                    :collapsed="isCollapsed"
                                    :active="$page.component === item.component"
                                >
                                    <template #icon>
                                        <component :is="item.icon"
                                                   class="svg-strokewhite w-6 h-6 transition-transform duration-200 group-hover:scale-110"/>
                                    </template>
                                </NavItem>
                            </ul>
                            <Transition
                                enter-active-class="transition-all duration-300 ease-out"
                                leave-active-class="transition-all duration-200 ease-in"
                                enter-from-class="opacity-0 scale-x-0"
                                enter-to-class="opacity-100 scale-x-100"
                                leave-from-class="opacity-100 scale-x-100"
                                leave-to-class="opacity-0 scale-x-0"
                            >
                                <div
                                    v-if="index < navigation.length - 1 && isCollapsed"
                                    class="my-2 border-t border-white/20 origin-center"
                                    :class="isCollapsed ? 'mx-0.5 my-10' : 'mx-4'"
                                ></div>
                            </Transition>
                        </template>
                    </div>

                    <div class="mt-auto border-t border-white/20 bg-black/10">
                        <Link
                            :href="ProfileEdit()"
                            :class="[
                            'flex items-center gap-3 py-4 hover:bg-white/10 transition-all duration-300 ease-in-out group',
                            isCollapsed ? 'px-2 justify-center' : 'px-4',
                        ]"
                        >
                            <div class="flex gap-2 min-w-0">
                                <div class="relative group/avatar">
                                    <img
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                        :alt="`Avatar de ${user.name}`"
                                        class="w-10 aspect-square rounded-full object-cover transition-transform duration-300 group-hover:scale-110 group-hover:ring-2 group-hover:ring-white/30"
                                    />
                                    <span
                                        v-else
                                        class="w-10 aspect-square rounded-full flex items-center justify-center bg-sweetorange text-white transition-transform duration-300 group-hover:scale-110 group-hover:ring-2 group-hover:ring-white/30"
                                    >
                                    {{ initials }}
                                </span>
                                    <Transition
                                        enter-active-class="transition-all duration-200 ease-out"
                                        leave-active-class="transition-all duration-150 ease-in"
                                        enter-from-class="opacity-0 -translate-x-2"
                                        enter-to-class="opacity-100 translate-x-0"
                                        leave-from-class="opacity-100 translate-x-0"
                                        leave-to-class="opacity-0 -translate-x-2"
                                    >
                                        <div
                                            v-if="isCollapsed"
                                            class="absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-gray-900 text-white text-sm rounded-md opacity-0 group-hover/avatar:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-lg"
                                        >
                                            {{ user.name }}
                                        </div>
                                    </Transition>
                                </div>

                                <Transition
                                    enter-active-class="transition-all duration-300 ease-out delay-75"
                                    leave-active-class="transition-all duration-200 ease-in"
                                    enter-from-class="opacity-0 -translate-x-4"
                                    enter-to-class="opacity-100 translate-x-0"
                                    leave-from-class="opacity-100 translate-x-0"
                                    leave-to-class="opacity-0 -translate-x-4"
                                >
                                    <div v-if="!isCollapsed">
                                        <p class="text-white font-semibold text-sm truncate">{{ user.name }}</p>
                                        <p class="text-white/80 text-xs truncate">{{ user.email }}</p>
                                    </div>
                                </Transition>
                            </div>
                            <Transition
                                enter-active-class="transition-all duration-300 ease-out delay-100"
                                leave-active-class="transition-all duration-200 ease-in"
                                enter-from-class="opacity-0 translate-x-2"
                                enter-to-class="opacity-100 translate-x-0"
                                leave-from-class="opacity-100 translate-x-0"
                                leave-to-class="opacity-0 translate-x-2"
                            >
                                <svg
                                    v-if="!isCollapsed"
                                    class="w-4 h-4 text-white/60 group-hover:text-white transition-all duration-200 ml-auto group-hover:translate-x-1"
                                    stroke="#ffffff"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </Transition>
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
                                class="w-full transition-all duration-300 hover:scale-105"
                                as="button"
                            >
                                <template #icon>
                                    <LogoutIcon
                                        class="svg-strokeblue w-6 h-6 transition-transform duration-300 group-hover:translate-x-1"/>
                                </template>
                            </ButtonSvg>
                        </div>
                    </div>
                </nav>
            </aside>
        </Transition>
    </header>
</template>

<style scoped>

</style>
