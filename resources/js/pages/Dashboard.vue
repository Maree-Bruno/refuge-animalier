<script setup>
import AnimalIndex from "@/pages/animals/AnimalIndex.vue";
import StatsSection from "@/components/widgets/stats/StatsSection.vue";
import AdoptionRequestIndex from "@/pages/adoptionrequests/AdoptionRequestIndex.vue";


const props = defineProps({
    adoptionRequests:Object,
    adopter: Object,
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
    refugedAnimals: Number,
    adoptedAnimals: Number,
    accepted: Number,
    inProgress: Number,
})
</script>

<template>
  <StatsSection
      :accepted="accepted"
      :refugedAnimals="refugedAnimals"
      :adoptedAnimals="adoptedAnimals"
      :inProgress="inProgress"
  />
    <AnimalIndex
        :animals="animals"
        :species="species"
        :races="races"
        :coats="coats"
        :vaccines="vaccines"
        :can="can"
        :allSuitableTypes="allSuitableTypes"/>

  <AdoptionRequestIndex
      :adoptionRequests="adoptionRequests"
      :filters="filters"
      :adopter="adopter"
      :animals="animals"
      :species="species"
      :races="races"
      :coats="coats"
      :vaccines="vaccines"
      :suitableTypes="allSuitableTypes"
  />


</template>
