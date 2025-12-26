<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import VenusIcon from "@/components/widgets/svg/VenusIcon.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import MarsIcon from "@/components/widgets/svg/MarsIcon.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import AnimalShow from "@/pages/animals/AnimalShow.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import {ref, watch} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {store} from "@/routes/animals/index.ts";
import AnimalCreate from "@/pages/animals/AnimalCreate.vue";
import {useFormatDate} from "@/composables/useFormatDate.ts";


const props = defineProps({
    animals: {
        type: Object
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    showTitle: {
        type: Boolean,
        default: false
    },
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
    can:Object,
})


let search = ref(props.filters.search || '');
let activeTab = ref(props.filters.status || 'all');
let timeout = null;

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            search: value,
            status: activeTab.value
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const tabs = [
    {value: 'all', label: 'Tous les animaux'},
    {value: 'Adopted', label: 'Animaux adoptés'},
    {value: 'refuge', label: 'Animaux au refuge'}
];

const switchTab = (tabValue) => {
    activeTab.value = tabValue;
    router.get(window.location.pathname, {
        search: search.value,
        status: tabValue
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const showCreateAnimal = ref(false);
const showDeleteConfirm = ref(false);
const animalToDelete = ref(null);
let isShowModalOpen = ref(false)
let selectedRow = ref(null)

const openShowModal = (row) => {
    selectedRow.value = row;
    isShowModalOpen.value = true;
}

const openDeleteConfirm = (animal) => {
    animalToDelete.value = animal;
    showDeleteConfirm.value = true;
}

const destroyAnimal = () => {
    if (!animalToDelete.value) return;

    router.delete(`/animals/${animalToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            animalToDelete.value = null;
        },
        onError: (errors) => {
            console.error('Erreur lors de la suppression:', errors);
        }
    });
}

const animalColumns = [
    {key: 'photo', label: 'Photo'},
    {key: 'name', label: 'Nom'},
    {key: 'admission_date', label: "Date d'admission"},
    {key: 'chip', label: 'Puce'},
    {key: 'sex', label: 'Sexe'},
    {key: 'age', label: 'Age'},
    {key: 'status', label: 'Status', class: 'font-sans'}
];

const animalActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => openShowModal(row)
    },
    {
        label: 'Archiver',
        icon: ArchiveIcon,
        handler: (row) => openDeleteConfirm(row),
        class: 'text-red-600 hover:text-red-700'
    },
];

const handleSort = ({key, order}) => {
    router.get(window.location.pathname, {
        search: search.value,
        status: activeTab.value,
        orderby: key,
        dir: order
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleAnimalRowSelect = (selected) => {

};
const getAnimalImageUrl = (animal, size = 'md') => {
    if (!animal.pictures || !animal.pictures[0]) {
        return '/images/billy.webp';
    }
    const filename = animal.pictures[0];
    const sizeMap = {
        'sm': '300x300',
        'md': '600x600',
        'lg': '900x900'
    };
    return `/images/animals/variants/${sizeMap[size]}/${filename}`;
}

const getAnimalImageSrcset = (animal) => {
    if (!animal.pictures || !animal.pictures[0]) {
        return '';
    }
    const filename = animal.pictures[0];
    return [
        `/images/animals/variants/300x300/${filename} 300w`,
        `/images/animals/variants/600x600/${filename} 600w`,
        `/images/animals/variants/900x900/${filename} 900w`
    ].join(', ');
}
const { formatDate } = useFormatDate();
</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <div class="flex justify-between">
            <h3 class="subsubtitle" :class="showTitle ? 'not-sr-only': 'sr-only'">Animaux</h3>
        </div>

        <div class="flex flex-col gap-3 sm:gap-4 lg:gap-5">
            <div class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:justify-between lg:items-start">
                <div
                    class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-1 overflow-x-auto pb-2 lg:pb-0">
                    <button
                        v-for="tab in tabs"
                        :key="tab.value"
                        @click="switchTab(tab.value)"
                        :class="[
                            'px-3 py-2 sm:px-4 rounded-lg transition-colors duration-200 text-xs sm:text-sm whitespace-nowrap flex-shrink-0',
                            activeTab === tab.value
                                ? 'bg-blueslate text-white font-semibold'
                                : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        {{ tab.label }}
                    </button>
                </div>

                <div
                    class="flex gap-2 sm:gap-3 items-center border-2 border-lightblueslate/50 p-2 rounded-lg lg:order-2 lg:min-w-[200px]">
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0"/>
                    <input
                        type="text"
                        placeholder="Rechercher un animal..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>

                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-3">
                    <button
                        @click="showCreateAnimal=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter un animal
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="animal in props.animals?.data"
                :key="animal.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                @click="openShowModal(animal)"
            >
                <div class="flex gap-3">
                    <img
                        :src="getAnimalImageUrl(animal, 'sm')"
                        :srcset="getAnimalImageSrcset(animal)"
                        sizes="64px"
                        :alt="animal.name"
                        class="w-16 h-16 object-cover rounded-full flex-shrink-0"
                        loading="lazy"
                    >

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h4 class="font-semibold text-base truncate">{{ animal.name }}</h4>
                            <span v-if="animal.sex === 'male'">
                                <MarsIcon class="svg-strokeblue w-5 h-5" stroke-width="2"/>
                            </span>
                            <span v-else>
                                <VenusIcon class="svg-strokeblue w-5 h-5" stroke-width="2"/>
                            </span>
                        </div>

                        <div class="space-y-1 text-xs text-gray-600">
                            <p><span class="font-medium">Age:</span> {{ animal.age }}</p>
                            <p><span class="font-medium">Puce:</span> {{ animal.chip }}</p>
                            <p><span class="font-medium">Admission:</span> {{ formatDate(animal.admission_date) }}</p>
                        </div>

                        <div class="mt-2">
                            <span
                                :class="[
                                    'inline-block px-2 py-1 rounded-full text-xs font-medium border',
                                    animal.status === 'Adopted' ? 'bg-lightgreenmint/50 text-green-900 border-greenmint' :
                                    animal.status === 'Validated' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                                    'bg-lightblueslate/20 text-blueslate border-blueslate'
                                ]"
                            >
                                {{ animal.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!props.animals?.data?.length" class="text-center py-8 text-gray-500">
                Aucun animaux enregistré
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                v-if="props.animals"
                :columns="animalColumns"
                :data="props.animals.data"
                :actions="animalActions"
                :selectable="true"
                empty-message="Aucun animaux enregistré"
                @row-select="handleAnimalRowSelect"
                @sort="handleSort"
            >
                <template #cell-photo="{ row }">
                    <img
                        :src="getAnimalImageUrl(row, 'sm')"
                        :srcset="getAnimalImageSrcset(row)"
                        sizes="32px"
                        :alt="row.name"
                        class="w-8 h-8 object-cover rounded-full"
                        loading="lazy"
                    >
                </template>
                <template #cell-sex="{ row }">
                    <span v-if="row.sex === 'male'">
                        <MarsIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/>
                    </span>
                    <span v-else>
                        <VenusIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/>
                    </span>
                </template>

                <template #cell-status="{ row }">
                    <span
                        :class="[
                            'px-2 py-1 rounded-full text-xs font-medium border',
                            row.status === 'Adopted' ? 'bg-lightgreenmint/50 text-green-900 border-greenmint' :
                            row.status === 'Validated' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                            'bg-lightblueslate/20 text-blueslate border-blueslate'
                        ]"
                    >
                        {{ row.status }}
                    </span>
                </template>
            </GenericTable>
        </div>

        <Pagination :links="props.animals?.links"/>
    </section>

    <KeepAlive>
        <RightModal v-model="showCreateAnimal " @update:modelValue="showCreateAnimal = $event"   route-key="create"
                    route-value="create-animal">
            <template #header>
                <h2 class="subsubtitle">Ajouter un animal</h2>
            </template>
            <AnimalCreate :species :races :coats :vaccines :can @close="showCreateAnimal = false"/>
        </RightModal>
    </KeepAlive>

    <CenterModal v-model="isShowModalOpen"   route-key="animal"
                 :route-value="selectedRow?.id">
        <AnimalShow :animal="selectedRow" :races :coats :vaccines/>
    </CenterModal>

    <CenterModal v-model="showDeleteConfirm">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <TrashIcon class="w-6 h-6 text-red-600"/>
            </div>

            <h3 class="text-lg font-semibold text-center mb-2">
                Confirmer la suppression
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir supprimer
                <span class="font-semibold">{{ animalToDelete?.name }}</span> ?
                Cette action est irréversible et supprimera également toutes les photos associées.
            </p>

            <div class="flex gap-3 justify-end">
                <button
                    @click="showDeleteConfirm = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    @click="destroyAnimal"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
</template>
