<script setup lang="ts">

import {Link} from "@inertiajs/vue3";

defineProps({
    links:Object
})
</script>

<template>
    <div class="mt-3 flex gap-2">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-3 py-1 border rounded"
                preserve-scroll
                :class="{ 'bg-blueslate text-white': link.active }"
            />
            <span
                v-else
                v-html="link.label"
                class="px-3 py-1 border rounded opacity-50 cursor-not-allowed"
            />
        </template>
    </div>
</template>

<style scoped>

</style>
