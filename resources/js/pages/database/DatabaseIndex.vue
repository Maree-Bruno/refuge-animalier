<script setup>

import {useToasterStore} from "@/stores/useToasterStore";
import {computed, ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import DatabaseCreate from "@/pages/database/DatabaseCreate.vue";
import AdoptionRequestShow from "@/pages/adoptionrequests/AdoptionRequestShow.vue";
import DatabaseShow from "@/pages/database/DatabaseShow.vue";

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({})
    },
    vaccines: Object,
    species: Object,
    races: Object,
    coats: Object,
    suitableTypes: Object,
})

const allResources = computed(() => {
    if (activeTab.value === 'all') {
        return [
            ...props.vaccines?.data.map(v => ({...v, type: 'Vaccin'})) || [],
            ...props.species?.data.map(s => ({...s, type: 'Espèce'})) || [],
            ...props.races?.data.map(r => ({...r, type: 'Race'})) || [],
            ...props.coats?.data.map(c => ({...c, type: 'Pelage'})) || [],
            ...props.suitableTypes?.data.map(s => ({...s, type: 'Convient pour'})) || [],
        ];
    }

    switch (activeTab.value) {
        case 'vaccines':
            return props.vaccines?.data.map(v => ({...v, type: 'Vaccin'})) || [];
        case 'species':
            return props.species?.data.map(s => ({...s, type: 'Espèce'})) || [];
        case 'races':
            return props.races?.data.map(r => ({...r, type: 'Race'})) || [];
        case 'coats':
            return props.coats?.data.map(c => ({...c, type: 'Pelage'})) || [];
        case 'suitableTypes':
            return props.suitableTypes?.data.map(s => ({...s, type: 'Convient pour'})) || [];
        default:
            return [];
    }
});

const currentData = computed(() => {
    switch (activeTab.value) {
        case 'vaccines':
            return props.vaccines;
        case 'species':
            return props.species;
        case 'races':
            return props.races;
        case 'coats':
            return props.coats;
        case 'suitableTypes':
            return props.suitableTypes;
        default:
            return null;
    }
})

const toast = useToasterStore();
let search = ref(props.filters.database_search || '');
let activeTab = ref(props.filters.status || 'all');
let timeout = null;

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            database_search: value,
            status: activeTab.value
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const tabs = [
    {value: 'all', label: 'Toutes les ressources'},
    {value: 'species', label: 'Espèces'},
    {value: 'races', label: 'Races'},
    {value: 'vaccines', label: 'Vaccins'},
    {value: 'suitableTypes', label: 'Convient pour'},
    {value: 'coats', label: 'Pelages'},
];

const switchTab = (tabValue) => {
    activeTab.value = tabValue;
    router.get(window.location.pathname, {
        database_search: search.value,
        status: tabValue
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const showDeleteConfirm = ref(false);
const valueToDelete = ref(null);
const isShowModalOpen = ref(false);
const selectedRessource = ref(null);
const showCreateRessource = ref(false);

const openShowModal = (ressource) => {
    selectedRessource.value = ressource;
    isShowModalOpen.value = true;
};

const openDeleteConfirm = (ressource) => {
    valueToDelete.value = ressource;
    showDeleteConfirm.value = true;
};

const destroyRessource = () => {
    if (!valueToDelete.value) return;

    router.delete(`/database/${valueToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'La ressource a bien été supprimée'});
            showDeleteConfirm.value = false;
            valueToDelete.value = null;
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est survenue pendant la suppression de la ressource'})
        }
    });
}

const databaseColumns = [
    {key: 'type', label: 'Type'},
    {key: 'name', label: 'Nom'},
];

const databaseActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => openShowModal(row)
    },
    {
        label: 'Supprimer',
        icon: TrashIcon,
        handler: (row) => openDeleteConfirm(row),
        class: 'text-red-600 hover:text-red-700'
    },
];

const handleSort = ({key, order}) => {
    router.get(window.location.pathname, {
        database_search: search.value,
        status: activeTab.value,
        orderby: key,
        dir: order
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleDatabaseRowSelect = (row) => {
}

const handlePaginationClick = (url) => {
    if (!url) return;

    router.get(url, {
        status: activeTab.value,
        database_search: search.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};
</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <h3 class="sr-only">Base de donnée</h3>
        <div class="flex flex-col gap-3 sm:gap-4 lg:gap-5">
            <div class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:justify-between lg:items-start">
                <div
                    class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-1 overflow-x-auto pb-2 lg:pb-0">
                    <button
                        v-for="tab in tabs"
                        :key="tab.value"
                        @click="switchTab(tab.value)"
                        :class="[
                            'px-3 py-2 sm:px-4 rounded-lg transition-colors duration-200 text-xs sm:text-sm whitespace-nowrap',
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
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6"/>
                    <input
                        type="text"
                        placeholder="Rechercher une ressource..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>
                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-3">
                    <button
                        @click="showCreateRessource=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter une ressource
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="ressource in allResources"
                :key="ressource.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                @click="openShowModal(ressource)"
            >
                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="font-semibold text-base">{{ ressource?.name }}</h4>
                        <small>{{ ressource?.type }}</small>
                    </div>
                </div>
            </div>

            <div v-if="!allResources.length" class="text-center py-8 text-gray-500">
                Aucune ressource disponible
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                :columns="databaseColumns"
                :data="allResources"
                :actions="databaseActions"
                :selectable="true"
                empty-message="Aucune ressources disponible"
                @row-select="handleDatabaseRowSelect"
                @sort="handleSort"
            >
                <template #cell-name="{ row }">
                    {{ row.name }}
                </template>
                <template #cell-type="{ row }">
                    {{ row.type }}
                </template>
            </GenericTable>
        </div>
        <Pagination
            v-if="currentData"
            :links="currentData.links"
            @navigate="handlePaginationClick"
        />

    </section>

    <CenterModal v-model="showDeleteConfirm">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <TrashIcon class="w-6 h-6 text-red-600"/>
            </div>

            <h3 class="text-lg font-semibold text-center mb-2">
                Confirmer la suppression
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir supprimer la ressource
                <span class="font-semibold">{{ valueToDelete?.name }}</span> ?
                Cette action est irréversible.
            </p>

            <div class="flex gap-3 justify-end">
                <button
                    @click="showDeleteConfirm = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    @click="destroyRessource"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
    <KeepAlive>
        <RightModal v-model="showCreateRessource" route-key="create" route-value="create-ressource">
            <template #header>
                <h2 class="subsubtitle">Nouvelle ressource</h2>
            </template>
            <DatabaseCreate
                :species="species"
                :races="races"
                :coats="coats"
                :suitableTypes="suitableTypes"
                :vaccines="vaccines"
            />
        </RightModal>
    </KeepAlive>
    <CenterModal v-model="isShowModalOpen"
                 route-key="ressource"
                 :route-value="selectedRessource?.id">
        <DatabaseShow
            :species="species"
            :races="races"
            :coats="coats"
            :suitableTypes="suitableTypes"
            :vaccines="vaccines"
        />
    </CenterModal>
</template>

<style scoped>

</style>
