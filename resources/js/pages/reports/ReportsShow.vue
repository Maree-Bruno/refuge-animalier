<script setup>
import AnimalShow from "@/pages/animals/AnimalShow.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import {ref} from "vue";
import AnimalCard from "@/components/widgets/animals/AnimalCard.vue";
import StatsSection from "@/components/widgets/stats/StatsSection.vue";

const props = defineProps({
    accepted: Number,
    adoptedAnimals: Number,
    refugedAnimals: Number,
    inProgress: Number,
    animalsByStatus: Object,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    allSuitableTypes: Object,
    can: Object,
    selectedMonth: Number,
    selectedYear: Number,
    monthLabel: String
});

const emit = defineEmits(['export-pdf']);

const isShowModalOpen = ref(false);
const selectedRow = ref(null);

const openShowModal = (animal) => {
    selectedRow.value = animal;
    isShowModalOpen.value = true;
};

const exportPdf = () => {
    emit('export-pdf');
};
</script>

<template>
    <section class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-auto space-y-5">
            <div class="flex justify-between items-center pb-4 border-b">
                <h2 class="subtitle">Rapport de {{ monthLabel }}</h2>
                <button
                    @click="exportPdf"
                    class="button-yellow button-animation rounded-md px-4 py-2 text-sm sm:text-base font-semibold flex items-center gap-2"
                >
                    <span>Exporter PDF</span>
                </button>
            </div>

            <StatsSection
                :accepted="accepted"
                :refugedAnimals="refugedAnimals"
                :adoptedAnimals="adoptedAnimals"
                :inProgress="inProgress"
            />

            <section
                v-for="(list, status) in {
                    Adopted: 'Animaux adoptés',
                    Validated: 'Animaux au refuge',
                    'In progress': 'Animaux en cours de validation'
                }"
                :key="status"
                class="space-y-4"
            >
                <h3 class="subsubtitle">{{ list }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <AnimalCard
                        v-for="animal in animalsByStatus?.[status]"
                        :key="animal.id"
                        :animal="animal"
                        @click="openShowModal(animal)"
                    />
                </div>

                <div
                    v-if="!animalsByStatus?.[status]?.length"
                    class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg"
                >
                    Aucun animal enregistré pour cette période
                </div>
            </section>
        </div>
    </section>

    <CenterModal v-model="isShowModalOpen" route-key="animal" :route-value="selectedRow?.id">
        <AnimalShow
            v-if="selectedRow"
            :animal="selectedRow"
            :species="species"
            :races="races"
            :coats="coats"
            :vaccines="vaccines"
            :can="can"
            :allSuitableTypes="allSuitableTypes"
            @close="isShowModalOpen = false"
        />
    </CenterModal>
</template>
