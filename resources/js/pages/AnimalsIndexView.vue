<script setup>

import AnimalIndex from "@/pages/animals/AnimalIndex.vue";
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps({
    animals: {type: Object},
    filters: {
        type: Object,
        default: () => ({})
    },
    species: {type: Object},
    races: {type: Object},
    coats: {type: Object},
    vaccines: {type: Object},
    allSuitableTypes: {type: Object},
    can: {type: Object},
})
</script>

<template>
    <AnimalIndex v-bind="props"/>
</template>
