<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    title: String,
    method: {type: String, default: 'get'},
    collapsed: Boolean,
    active: Boolean,
});


</script>

<template>
    <li class="relative group">
        <Link
            :href="href"
            :method="method"
            class="flex items-center gap-2.5 rounded-md cursor-pointer text-white py-2 px-4 transition-all nav-item nav-item-animation"
            :class="[
        collapsed ? 'justify-center' : '',
        active ? 'bg-lightblueslate font-bold' : '',
    ]"
        >

            <div class="flex-shrink-0">
                <slot name="icon"/>
            </div>

            <span v-if="!collapsed">{{ title }}</span>

        </Link>

        <div
            v-if="collapsed"
            class="absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-gray-900 text-white text-sm rounded-md sr-only group-hover:not-sr-only pointer-events-none transition-all whitespace-nowrap z-50 shadow-lg"
        >
            <span> {{ title }} </span>
        </div>
    </li>
</template>
<style scoped>
li::before {
    content: none;
    display: none;
}
</style>
