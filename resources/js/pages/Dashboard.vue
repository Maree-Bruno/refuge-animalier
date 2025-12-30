<script setup>
import AnimalIndex from "@/pages/animals/AnimalIndex.vue";
import StatsSection from "@/components/widgets/stats/StatsSection.vue";
import AdoptionRequestIndex from "@/pages/adoptionrequests/AdoptionRequestIndex.vue";
import ContactMessagesIndex from "@/pages/contactmessages/ContactMessagesIndex.vue";


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
    messages:Object
})
</script>

<template>

  <StatsSection
      :accepted="accepted"
      :refugedAnimals="refugedAnimals"
      :adoptedAnimals="adoptedAnimals"
      :inProgress="inProgress"
  />
    <section class="space-y-5">
        <h3 class="subsubtitle">Animaux</h3>
    <AnimalIndex
        :animals="animals"
        :species="species"
        :races="races"
        :coats="coats"
        :vaccines="vaccines"
        :can="can"
        :allSuitableTypes="allSuitableTypes"/>
    </section>
<section class="space-y-5">
    <h3 class="subsubtitle">Demandes d'adoption</h3>
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
</section>
    <section class="space-y-5" v-if="can.view">
        <h3 class="subsubtitle">Messages de contact</h3>
    <ContactMessagesIndex
    :messages="messages"
    :filters="filters"
    />
    </section>

</template>
