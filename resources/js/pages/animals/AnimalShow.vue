<script setup lang="ts">
import {ref, computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import AnimalEdit from "@/pages/animals/AnimalEdit.vue";
import AnimalCreate from "@/pages/animals/AnimalCreate.vue";

const props = defineProps({
    animal: {type:Object},
    species: {type:Object},
    races: {type:Object},
    coats: {type:Object},
    vaccines: {type:Object},
    allSuitableTypes: {type:Object},
    can: Object,
});

const showCreate = ref(false);
const showEdit = ref(false);
const showNotes = ref(false);
const selectedImageIndex = ref(0);

const getImageUrl = (filename: string, size: 'sm' | 'md' | 'lg' = 'md'): string => {
    if (!filename) return '/images/billy.webp';

    const sizeMap = {
        sm: '300x300',
        md: '600x600',
        lg: '900x900'
    };

    return `/images/animals/variants/${sizeMap[size]}/${filename}`;
};

const getImageSrcset = (filename: string): string => {
    if (!filename) return '';

    return [
        `/images/animals/variants/300x300/${filename} 300w`,
        `/images/animals/variants/600x600/${filename} 600w`,
        `/images/animals/variants/900x900/${filename} 900w`
    ].join(', ');
};

const mainImage = computed(() => {
    if (!props.animal?.pictures || props.animal.pictures.length === 0) {
        return {
            src: '/images/billy.webp',
            srcset: '',
            alt: props.animal?.name || 'Animal'
        };
    }

    const filename = props.animal.pictures[selectedImageIndex.value];
    return {
        src: getImageUrl(filename, 'lg'),
        srcset: getImageSrcset(filename),
        alt: props.animal.name
    };
});

const thumbnails = computed(() => {
    if (!props.animal?.pictures || props.animal.pictures.length === 0) {
        return [];
    }

    return props.animal.pictures.map((filename: string) => ({
        src: getImageUrl(filename, 'sm'),
        srcset: getImageSrcset(filename),
        filename
    }));
});

const suitableText = computed(() => {
    if (!props.animal?.suitable_types || props.animal.suitable_types.length === 0) {
        return 'Non spécifié';
    }

    return props.animal.suitable_types
        .map((type: any) => type.name)
        .join(', ');
});


const outsideText = computed(() => {
    return props.animal?.outside
        ? 'Les sorties ne posent pas de problèmes'
        : 'Les sorties sont restreintes';
});

const statusClass = computed(() => {
    const statusMap: Record<string, string> = {
        Adopted: 'bg-lightgreenmint/50 text-green-900 border-greenmint',
        Validated: 'bg-lightsweetorange/20 text-orange-900 border-sweetorange',
        default: 'bg-lightblueslate/20 text-blueslate border-blueslate'
    };

    return statusMap[props.animal?.status as string] || statusMap.default;
});

const selectImage = (index: number) => {
    selectedImageIndex.value = index;
};
</script>

<template>
    <section v-if="animal" class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-scroll">
            <div class="flex flex-col gap-3 sm:gap-4">
                <h2 class="subtitle text-xl sm:text-2xl">{{ animal.name }}</h2>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <button
                        @click="showCreate = true"
                        class="button-green button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter une note
                    </button>
                    <button
                        @click="showNotes = true"
                        class="button-blue button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Toutes les notes
                    </button>
                    <button
                        @click="showEdit = true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Modifier
                    </button>
                    <button
                        class="button-orange button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Archiver
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-5">
                        <img
                            :src="mainImage.src"
                            :srcset="mainImage.srcset"
                            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 300px"
                            :alt="mainImage.alt"
                            class="w-full sm:w-auto sm:h-[250px] lg:h-[300px] aspect-square object-cover rounded-2xl"
                            loading="eager"
                        />

                        <div
                            v-if="thumbnails.length > 0"
                            class="flex sm:flex-col gap-2 sm:gap-3 overflow-x-auto sm:overflow-y-auto sm:max-h-[250px] lg:max-h-[300px] pb-2 sm:pb-0"
                        >
                            <button
                                v-for="(thumb, index) in thumbnails"
                                :key="index"
                                @click="selectImage(index)"
                                :class="[
                                    'w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden transition-all border-2',
                                    selectedImageIndex === index
                                        ? 'border-blueslate ring-2 ring-blueslate/30'
                                        : 'border-transparent hover:border-gray-300'
                                ]"
                            >
                                <img
                                    :src="thumb.src"
                                    :srcset="thumb.srcset"
                                    sizes="80px"
                                    :alt="`${animal.name} - Image ${index + 1}`"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                            </button>
                        </div>

                        <div v-else class="flex sm:flex-col gap-2 sm:gap-3">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-gray-100 flex items-center justify-center">
                                <span class="text-xs text-gray-400">Aucune photo</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Age</span>
                                <span class="text-xs sm:text-sm">{{ animal.age }} an(s)</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Espèce</span>
                                <span class="text-xs sm:text-sm">{{ animal.specie?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Race</span>
                                <span class="text-xs sm:text-sm">{{ animal.race?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Pelage</span>
                                <span class="text-xs sm:text-sm">{{ animal.coat?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col col-span-2">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Puce</span>
                                <span class="text-xs sm:text-sm font-mono">{{ animal.chip }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Convient pour</p>
                                <span class="text-xs sm:text-sm">{{ suitableText }}</span>
                            </div>

                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Sorties</p>
                                <p class="text-xs sm:text-sm">{{ outsideText }}</p>
                            </div>

                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Status</p>
                                <span
                                    :class="[
                                        'inline-block px-3 py-1 rounded-full text-xs font-medium border mt-1',
                                        statusClass
                                    ]"
                                >
                                    {{ animal.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <h3 class="font-quicksand font-bold text-lg sm:text-xl">Description</h3>
                    <p class="text-sm sm:text-base leading-relaxed wrap-break-word overflow-hidden">
                        {{ animal.description || 'Aucune description disponible.' }}
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
            <h2 class="subsubtitle">Modifier {{ animal?.name }}</h2>
        </template>
        <AnimalEdit
            :animal="animal"
            :species="species"
            :races="races"
            :coats="coats"
            :vaccines="vaccines"
            :allSuitableTypes="allSuitableTypes"
            :can="can"
            @close="showEdit = false"
        />
    </RightModal>

    <CenterModal v-model="showNotes">
    </CenterModal>
</template>

<style scoped>
.overflow-x-auto::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
