<script setup lang="ts">
import {ref} from "vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";

const props = defineProps({
    animal: Object,
});
const showCreate = ref(false);
const showEdit = ref(false);
const showNotes = ref(false);
</script>

<template>
    <section v-if="props.animal" class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col gap-3 sm:gap-4">
                <h2 class="subtitle text-xl sm:text-2xl">{{ props.animal.name }}</h2>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <button @click="showCreate=true"
                            class="button-green button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap">
                        Ajouter une note
                    </button>
                    <button
                        @click="showNotes=true"
                        class="button-blue button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap">
                        Toutes les notes
                    </button>
                    <button
                        @click="showEdit=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold">
                        Modifier
                    </button>
                    <button
                        class="button-orange button-animation rounded-md p-2 text-sm sm:text-base font-semibold">
                        Archiver
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-5">
                        <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="w-full sm:w-auto sm:h-[250px] lg:h-[300px] aspect-square object-cover rounded-2xl"
                        />

                        <div class="flex sm:flex-col gap-2 sm:gap-3 overflow-x-auto sm:overflow-y-auto sm:max-h-[250px] lg:max-h-[300px] pb-2 sm:pb-0">
                            <img
                                v-for="i in 5"
                                :key="i"
                                src="/images/billy.webp"
                                :alt="props.animal.name"
                                class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg"
                            />
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Age</span>
                                <span class="text-xs sm:text-sm">{{ props.animal.age }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Espèce</span>
                                <span class="text-xs sm:text-sm">{{ props.animal.specie?.name }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Race</span>
                                <span class="text-xs sm:text-sm">{{ props.animal.specie.race?.name }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Pelage</span>
                                <span class="text-xs sm:text-sm">{{ props.animal.coat?.name }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Convient pour</p>
                                <span class="text-xs sm:text-sm">{{ props.animal.suitable }}</span>
                            </div>

                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Sorties</p>
                                <p class="text-xs sm:text-sm">Les sorties ne posent pas de problèmes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <h3 class="font-quicksand font-bold text-lg sm:text-xl">Description</h3>
                    <p class="text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ props.animal.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <RightModal v-model="showCreate">
        <template #header>
            <h2 class="subsubtitle">Ajouter une note</h2>
        </template>
    </RightModal>

    <RightModal v-model="showEdit">
        <template #header>
            <h2 class="subsubtitle">Modifier {{ props.animal?.name }}</h2>
        </template>
    </RightModal>

    <CenterModal v-model="showNotes">
    </CenterModal>
</template>
