<script setup>
import AdoptionRequestIndex from "@/pages/adoptionrequests/AdoptionRequestIndex.vue";

const props = defineProps({
    adoptionRequests: Object,
    filters: { type: Object, default: () => ({}) },
    adopter: Object,
    animals: Object,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    suitableTypes: Object,
});
</script>

<template>
    <AdoptionRequestIndex
        :adoptionRequests="adoptionRequests"
        :filters="filters"
        :adopter="adopter"
        :animals="animals"
        :species="species"
        :races="races"
        :coats="coats"
        :vaccines="vaccines"
        :suitableTypes="suitableTypes"
    />
</template>
