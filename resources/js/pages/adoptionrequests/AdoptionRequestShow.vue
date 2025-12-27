<script setup lang="ts">
import {computed, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useFormatDate} from "@/composables/useFormatDate.ts";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import AnimalShow from "@/pages/animals/AnimalShow.vue";
import {useToasterStore} from "@/stores/useToasterStore";

const props = defineProps({
    request: {
        type: Object,
        default: null
    },
    animal: {type: Object},
    species: {
        type: Object,
    },
    races: {
        type: Object,
    },
    coats: {
        type: Object,
    },
    vaccines: {
        type: Object,
    },
    suitableTypes: {
        type: Object
    },

});
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
const localStatus = ref(props.request?.status || '');
const isSaving = ref(false);

watch(() => props.request, (newRequest) => {
    if (newRequest) {
        localStatus.value = newRequest.status;
    }
}, {immediate: true});

const statusOptions = [
    {value: 'submitted', label: 'Soumise', color: 'bg-lightblueslate/20 text-blueslate border-blueslate'},
    {value: 'pending', label: 'En attente', color: 'bg-lightsweetorange/20 text-orange-900 border-sweetorange'},
    {value: 'accepted', label: 'Acceptée', color: 'bg-lightgreenmint/50 text-green-900 border-greenmint'},
    {value: 'rejected', label: 'Refusée', color: 'bg-gray-100 text-gray-900 border-gray-300'}
];

const getStatusLabel = (status) => {
    const option = statusOptions.find(opt => opt.value === status);
    return option ? option.label : status;
};

const getStatusColor = (status) => {
    const option = statusOptions.find(opt => opt.value === status);
    return option ? option.color : 'bg-gray-100 text-gray-900 border-gray-300';
};
const toast = useToasterStore();
const updateStatus = () => {
    if (!props.request || localStatus.value === props.request.status) return;

    isSaving.value = true;

    router.patch(`/adoption/${props.request.id}`, {
        status: localStatus.value
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success({text: 'Statut changé'});
            isSaving.value = false;
        },
        onError: (errors) => {
            console.error('Erreur lors de la mise à jour:', errors);
            toast.error({text: 'Un problème est survenu lors de la mise à jour du statut'});
            isSaving.value = false;
            localStatus.value = props.request.status;
        }
    });
};

const {formatDate} = useFormatDate();
let isAnimalModalOpen = ref(false);
let selectedAnimal = ref(null);

const openShowModal = () => {
    selectedAnimal.value = props.request.animal;
    isAnimalModalOpen.value = true;
};
</script>

<template>
    <div class="p-6 overflow-y-scroll">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">Détails de la demande d'adoption</h3>
        </div>

        <div v-if="request" class="space-y-5">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Adoptant</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                        <p class="font-medium text-gray-900">{{ request.adopter?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-medium text-gray-900">{{ request.adopter?.email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                        <p class="font-medium text-gray-900">{{ request.adopter?.phone }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Animal concerné</h4>
                <div class="flex gap-8">
                <div>
                    <img
                        :src="mainImage.src"
                        :srcset="mainImage.srcset"
                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 300px"
                        :alt="mainImage.alt"
                        class="w-full sm:w-auto sm:h-[250px] lg:h-[300px] aspect-square object-cover rounded-2xl"
                        loading="eager"
                    />
                </div>
                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nom de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Âge de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.age }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Sexe de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.sex }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Espèce de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.specie?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Race de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.race?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pelage de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal?.coat?.name }}</p>
                    </div>
                    <div v-if="request.animal?.vaccines">
                        <p class="text-sm text-gray-600 mb-1">Vaccins de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal.vaccines.map(v => v.name).join(',')}}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Puce de l'animal</p>
                        <p class="font-medium text-gray-900">{{ request.animal.chip }}</p>
                    </div>
                    <div v-if="request.animal?.suitableTypes">
                        <p class="text-sm text-gray-600 mb-1">Convient pour</p>
                        <p class="font-medium text-gray-900">{{ request.animal.suitableTypes.map(t =>t.name).join(',')}}</p>
                    </div>
                </div>
                </div>

            </div>
            <div v-if="request.message" class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Message</h4>
                <p class="text-gray-900 whitespace-pre-wrap">{{ request.message }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Statut de la demande</h4>
                <div class="flex items-center gap-3">
                    <select
                        v-model="localStatus"
                        @change="updateStatus"
                        :disabled="isSaving"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option
                            v-for="option in statusOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>

                    <span
                        :class="[
                            'px-3 py-2 rounded-lg text-sm font-medium border whitespace-nowrap',
                            getStatusColor(localStatus)
                        ]"
                    >
                        {{ getStatusLabel(localStatus) }}
                    </span>
                </div>

                <p v-if="isSaving" class="text-sm text-blue-600 mt-2 flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enregistrement en cours...
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Informations</h4>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Date de la demande</p>
                    <p class="font-medium text-gray-900">{{ formatDate(request.request_date) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
