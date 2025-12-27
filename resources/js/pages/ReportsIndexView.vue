<script setup lang="ts">
import ReportsIndex from "@/pages/reports/ReportsIndex.vue";

const props = defineProps({
    reports: Array,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    allSuitableTypes: Object,
    can: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
})
</script>

<template>
    <ReportsIndex
        :reports="reports"
        :species="species"
        :races="races"
        :coats="coats"
        :vaccines="vaccines"
        :can="can"
        :allSuitableTypes="allSuitableTypes"
        :filters="filters"
    />
</template>
