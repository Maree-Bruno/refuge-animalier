<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useFormatDate} from "@/composables/useFormatDate.ts";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import AdoptionRequestShow from "@/pages/adoptionrequests/AdoptionRequestShow.vue";
import AnimalShow from "@/pages/animals/AnimalShow.vue";

const props = defineProps({
    adoptionRequests: {
        type: Object
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    adopter: {
        type: Object
    },
    animals: Object,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    suitableTypes: Object,
});

let search = ref(props.filters.request_search || '');
let activeTab = ref(props.filters.status || 'all');
let timeout = null;

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            request_search: value,
            status: activeTab.value,
            animal_search: props.filters.animal_search
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const tabs = [
    {value: 'all', label: 'Toutes les demandes'},
    {value: 'submitted', label: 'Soumises'},
    {value: 'pending', label: 'En attente'},
    {value: 'accepted', label: 'Acceptées'},
    {value: 'rejected', label: 'Refusées'}
];

const switchTab = (tabValue) => {
    activeTab.value = tabValue;
    router.get(window.location.pathname, {
        request_search: search.value,
        status: tabValue,
        animal_search: props.filters.animal_search
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const showDeleteConfirm = ref(false);
const requestToDelete = ref(null);
const isShowModalOpen = ref(false);
const selectedRequest = ref(null);

const openShowModal = (request) => {
    selectedRequest.value = request;
    isShowModalOpen.value = true;
};

const openDeleteConfirm = (request) => {
    requestToDelete.value = request;
    showDeleteConfirm.value = true;
};

const destroyRequest = () => {
    if (!requestToDelete.value) return;

    router.delete(`/adoption-requests/${requestToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            requestToDelete.value = null;
        },
        onError: (errors) => {
            console.error('Erreur lors de la suppression:', errors);
        }
    });
}

const requestColumns = [
    {key: 'name', label: 'Nom'},
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Téléphone'},
    {key: 'animal', label: 'Animal'},
    {key: 'status', label: 'Statut'},
    {key: 'request_date', label: 'Date de demande'},
];

const requestActions = [
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
        request_search: search.value,
        status: activeTab.value,
        orderby: key,
        dir: order,
        animal_search: props.filters.animal_search
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleRequestRowSelect = (selected) => {

};

const getStatusLabel = (status) => {
    const statusMap = {
        'submitted': 'Soumise',
        'pending': 'En attente',
        'accepted': 'Acceptée',
        'rejected': 'Refusée'
    };
    return statusMap[status];
}

const {formatDate} = useFormatDate();
</script>
<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
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
                        placeholder="Rechercher une demande..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="request in props.adoptionRequests?.data"
                :key="request.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                @click="openShowModal(request)"
            >
                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="font-semibold text-base">{{ request.adopter?.name }}</h4>
                        <span
                            :class="[
                                'px-2 py-1 rounded-full text-xs font-medium border whitespace-nowrap',
                                request.status === 'accepted' ? 'bg-lightgreenmint/50 text-green-900 border-greenmint' :
                                request.status === 'pending' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                                request.status === 'submitted' ? 'bg-lightblueslate/20 text-blueslate border-blueslate' :
                                'bg-gray-100 text-gray-900 border-gray-300'
                            ]"
                        >
                            {{ getStatusLabel(request.status) }}
                        </span>
                    </div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <p><span class="font-medium">Email:</span> {{ request.adopter?.email }}</p>
                        <p><span class="font-medium">Téléphone:</span> {{ request.adopter?.phone }}</p>
                        <p><span class="font-medium">Animal:</span> {{ request.animal?.name }}</p>
                        <p><span class="font-medium">Date:</span> {{ formatDate(request.request_date) }}</p>
                    </div>
                </div>
            </div>

            <div v-if="!props.adoptionRequests?.data?.length" class="text-center py-8 text-gray-500">
                Aucune demande enregistrée
            </div>
        </div>
        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                v-if="props.adoptionRequests"
                :columns="requestColumns"
                :data="props.adoptionRequests.data"
                :actions="requestActions"
                :selectable="true"
                empty-message="Aucune demande enregistrée"
                @row-select="handleRequestRowSelect"
                @sort="handleSort"
            >
                <template #cell-name="{ row }">
                    {{ row.adopter?.name }}
                </template>

                <template #cell-email="{ row }">
                    {{ row.adopter?.email }}
                </template>

                <template #cell-phone="{ row }">
                    {{ row.adopter?.phone }}
                </template>

                <template #cell-animal="{ row }">
                    <span
                        class="font-medium"
                    >
                        {{ row.animal?.name }}
                    </span>
                </template>

                <template #cell-status="{ row }">
                    <span
                        :class="[
                            'px-2 py-1 rounded-full text-xs font-medium border',
                            row.status === 'accepted' ? 'bg-lightgreenmint/50 text-green-900 border-greenmint' :
                            row.status === 'pending' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                            row.status === 'submitted' ? 'bg-lightblueslate/20 text-blueslate border-blueslate' :
                            'bg-gray-100 text-gray-900 border-gray-300'
                        ]"
                    >
                        {{ getStatusLabel(row.status) }}
                    </span>
                </template>

                <template #cell-request_date="{ row }">
                    {{ formatDate(row.request_date) }}
                </template>
            </GenericTable>
        </div>

        <Pagination :links="props.adoptionRequests?.links"/>
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
                Êtes-vous sûr de vouloir supprimer la demande de
                <span class="font-semibold">{{ requestToDelete?.adopter?.name }}</span> ?
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
                    @click="destroyRequest"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
    <CenterModal v-model="isShowModalOpen"
                 route-key="request"
                 :route-value="selectedRequest?.id">
        <AdoptionRequestShow
            v-model="isShowModalOpen"
            :request="selectedRequest"
            :animal="animals"
            :species="species"
            :races="races"
            :coats="coats"
            :vaccines="vaccines"
            :suitable-types="suitableTypes"
        />
    </CenterModal>
</template>

<style scoped>

</style>
