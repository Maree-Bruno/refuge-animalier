<script setup>
import Stats from "@/components/widgets/dashboard/Stats.vue";

defineProps({
    accepted: Number,
    refugedAnimals: Number,
    adoptedAnimals: Number,
    inProgress: Number
});
</script>

<template>
    <section class="space-y-5">
        <h3 class="subsubtitle">Statistique</h3>
        <div class="flex flex-wrap gap-10 p-4 sm:p-0">
            <Stats title="Animaux adoptés" :number="adoptedAnimals"
                   className="bg-lighthoneyyellow border-honeyyellow"/>
            <Stats title="Animaux recueillis" :number="refugedAnimals"
                   className="border-greenmint bg-lightgreenmint"/>
            <Stats title="En cours d'adoption" :number="inProgress"
                   className="border-sweetorange bg-lightsweetorange"/>
            <Stats title="Demande Acceptées" :number="accepted"
                   className="border-gray-500 bg-lightgray"/>
        </div>
    </section>
</template>
