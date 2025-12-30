<script setup>

import DatabaseIndex from "@/pages/database/DatabaseIndex.vue";

const props= defineProps(
    {
        filters: {
            type: Object,
            default: () => ({})
        },
        vaccines:Object,
        species:Object,
        races:Object,
        coats:Object,
        suitableTypes:Object,
    }
);
</script>
<template>
    <DatabaseIndex
    :filters="filters"
    :vaccines="vaccines"
    :species="species"
    :races="races"
    :coats="coats"
    :suitableTypes="suitableTypes"
    />
</template>

<style scoped>

</style>

