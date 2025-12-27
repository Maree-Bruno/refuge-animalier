<script setup>

import BellIcon from "@/components/widgets/svg/BellIcon.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import NavHeader from "@/components/widgets/nav/NavHeader.vue";
import {useSidebar} from "@/composables/useIsSidebar.js";
import {computed, ref, watch} from "vue";
import {Head, router, usePage} from "@inertiajs/vue3";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import TopModal from "@/components/widgets/modals/TopModal.vue";
import Toaster from "@/components/widgets/Toaster.vue";


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
    router.get(window.location.pathname, {search: value}, {
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
        <div class="max-w-7xl mx-auto transition-all duration-300 space-y-16">
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
    <TopModal v-model="showSearchBar">
        <div
            class=" flex gap-4 justify-between items-center md:border-2 border-lightblueslate/50 p-1 rounded-lg">
            <LoupeIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
            <input type="text" placeholder="Search... " v-model="search" class="flex-1 outline-none">
            <button @click="showSearchBar = false" class="opacity-30 border p-2 rounded-sm bg-lightgray">
                ESC
            </button>
        </div>
    </TopModal>
    <Toaster/>
</template>
