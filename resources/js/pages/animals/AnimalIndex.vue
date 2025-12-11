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
import {router} from "@inertiajs/vue3";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";


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
    }
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
const showCreateNote = ref(false);
let isShowModalOpen = ref(false)
let selectedRow = ref(null)

const openShowModal = (row) => {
    selectedRow.value = row;
    isShowModalOpen.value = true;
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
        handler: (row) => {

        },
    }
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
</script>

<template>
    <section class="flex flex-col gap-5">
        <div class="flex justify-between">
            <h3 class="subsubtitle" :class="showTitle ? 'not-sr-only': 'sr-only'">Animaux</h3>
        </div>
        <div class="flex flex-col gap-5 md:grid md:grid-cols-2 lg:flex lg:flex-row lg:justify-between lg:items-center">
            <div class="flex gap-4 col-span-2 lg:col-span-1 lg:order-3">
                <button @click="showCreateAnimal=true"
                        class="button-yellow button-animation rounded-md p-2 font-semibold">
                    Ajouter un animal
                </button>
                <button @click="showCreateNote=true"
                        class="button-green button-animation rounded-md p-2 font-semibold">
                    Ajouter une note
                </button>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 lg:order-1">
                <button
                    v-for="tab in tabs"
                    :key="tab.value"
                    @click="switchTab(tab.value)"
                    :class="[
                'px-4 py-2 rounded-lg transition-colors duration-200 smalltext',
                activeTab === tab.value
                    ? 'bg-blueslate text-white font-semibold'
                    : 'text-gray-600 hover:bg-gray-100'
            ]"
                >
                    {{ tab.label }}
                </button>
            </div>

            <div
                class="flex gap-4 justify-between items-center md:border-2 md:border-lightblueslate/50 p-2 rounded-lg lg:order-2">
                <LoupeIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
                <input type="text" placeholder="Rechercher un animal... " v-model="search" class="flex-1 outline-none">
            </div>
        </div>
        <div class="sr-only md:not-sr-only">
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
                    <img src="/images/billy.webp" :alt="row.name" class="w-8 h-8 object-cover rounded-full">
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

        <Pagination :links="props.animals.links"/>

    </section>
    <RightModal v-model="showCreateAnimal">
            <template #header>
                <h2 class="subsubtitle">Ajouter un animal</h2>
            </template>

    </RightModal>
    <CenterModal v-model="isShowModalOpen">
        <AnimalShow :animal="selectedRow"/>
    </CenterModal>
    <RightModal v-model="showCreateNote">
        <template #header>
            <h2 class="subsubtitle">Ajouter une note</h2>
        </template>
    </RightModal>
</template>

<style scoped>

</style>
