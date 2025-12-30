<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useFormatDate} from "@/composables/useFormatDate.ts";
import {useToasterStore} from "@/stores/useToasterStore.ts";
import ContactMessagesShow from "@/pages/contactmessages/ContactMessagesShow.vue";

const props = defineProps({
    messages: {
        type: Object
    },
    filters: {
        type: Object,
        default: () => ({})
    },
});

let search = ref(props.filters.message_search || '');
let activeTypeTab = ref(props.filters.type || 'all');
let activeStatusTab = ref(props.filters.status || 'all');
let timeout = null;

const toast = useToasterStore();

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            message_search: value,
            type: activeTypeTab.value,
            status: activeStatusTab.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const typeTabs = [
    {value: 'all', label: 'Tous les messages'},
    {value: 'contact', label: 'Contact'},
    {value: 'volunteer', label: 'Volontariat'}
];

const statusTabs = [
    {value: 'all', label: 'Tous'},
    {value: 'nouveau', label: 'Nouveaux'},
    {value: 'lu', label: 'Lus'},
    {value: 'archivé', label: 'Archivés'}
];

const switchTypeTab = (tabValue) => {
    activeTypeTab.value = tabValue;
    router.get(window.location.pathname, {
        message_search: search.value,
        type: tabValue,
        status: activeStatusTab.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const switchStatusTab = (tabValue) => {
    activeStatusTab.value = tabValue;
    router.get(window.location.pathname, {
        message_search: search.value,
        type: activeTypeTab.value,
        status: tabValue,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const showDeleteConfirm = ref(false);
const messageToDelete = ref(null);
const isShowModalOpen = ref(false);
const selectedMessage = ref(null);

const openShowModal = (message) => {
    selectedMessage.value = message;
    isShowModalOpen.value = true;

    if (message.status === 'nouveau') {
        router.patch(`/contact-messages/${message.id}/status`, {
            status: 'lu'
        }, {
            preserveScroll: true,
            preserveState: true,
        });
    }
};


const openDeleteConfirm = (message) => {
    messageToDelete.value = message;
    showDeleteConfirm.value = true;
};

const destroyMessage = () => {
    if (!messageToDelete.value) return;

    router.delete(`/contact-messages/${messageToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'Message archivé avec succès'});
            showDeleteConfirm.value = false;
            messageToDelete.value = null;
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est survenue pendant l\'archivage'});
        }
    });
}

const messageColumns = [
    {key: 'type', label: 'Type'},
    {key: 'name', label: 'Nom'},
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Téléphone'},
    {key: 'subject', label: 'Sujet'},
    {key: 'status', label: 'Statut'},
    {key: 'send_date', label: 'Date'},
];

const messageActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => openShowModal(row)
    },
    {
        label: 'Archiver',
        icon: ArchiveIcon,
        handler: (row) => openDeleteConfirm(row),
        class: 'text-red-600 hover:text-red-700',
        show: (row) => row.status !== 'archivé'
    },
];

const handleSort = ({key, order}) => {
    router.get(window.location.pathname, {
        message_search: search.value,
        type: activeTypeTab.value,
        status: activeStatusTab.value,
        orderby: key,
        dir: order,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const getStatusLabel = (status) => {
    const statusMap = {
        'nouveau': 'Nouveau',
        'lu': 'Lu',
        'archivé': 'Archivé'
    };
    return statusMap[status] || status;
}

const getTypeLabel = (type) => {
    const typeMap = {
        'contact': 'Contact',
        'volunteer': 'Volontariat'
    };
    return typeMap[type] || type;
}


const {formatDate} = useFormatDate();
</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <h3 class="sr-only">Messages</h3>

        <div class="flex flex-col gap-3 sm:gap-4 lg:gap-5">
            <div class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:justify-between lg:items-start">
                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-1 overflow-x-auto pb-2 lg:pb-0">
                    <button
                        v-for="tab in typeTabs"
                        :key="tab.value"
                        @click="switchTypeTab(tab.value)"
                        :class="[
                            'px-3 py-2 sm:px-4 rounded-lg transition-colors duration-200 text-xs sm:text-sm whitespace-nowrap flex-shrink-0',
                            activeTypeTab === tab.value
                                ? 'bg-blueslate text-white font-semibold'
                                : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        {{ tab.label }}
                    </button>
                </div>

                <div class="flex gap-2 sm:gap-3 items-center border-2 border-lightblueslate/50 p-2 rounded-lg lg:order-2 lg:min-w-[200px]">
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0"/>
                    <input
                        type="text"
                        placeholder="Rechercher un message..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2">
                <button
                    v-for="tab in statusTabs"
                    :key="tab.value"
                    @click="switchStatusTab(tab.value)"
                    :class="[
                        'px-3 py-1.5 rounded-md transition-colors duration-200 text-xs sm:text-sm whitespace-nowrap',
                        activeStatusTab === tab.value
                            ? 'bg-blueslate/10 text-blueslate font-medium border border-blueslate'
                            : 'text-gray-500 hover:bg-gray-50'
                    ]"
                >
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="message in props.messages?.data"
                :key="message.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                @click="openShowModal(message)"
            >
                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-col gap-1">
                            <h4 class="font-semibold text-base">{{ message.name }}</h4>
                            <span class="text-xs text-gray-500">{{ getTypeLabel(message.type) }}</span>
                        </div>
                        <span
                            :class="[
                                'px-2 py-1 rounded-full text-xs font-medium border whitespace-nowrap',
                                message.status === 'nouveau' ? 'bg-lightblueslate/20 text-blueslate border-blueslate' :
                                message.status === 'lu' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                                'bg-gray-100 text-gray-900 border-gray-300'
                            ]"
                        >
                            {{ getStatusLabel(message.status) }}
                        </span>
                    </div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <p><span class="font-medium">Email:</span> {{ message.email }}</p>
                        <p><span class="font-medium">Téléphone:</span> {{ message.phone }}</p>
                        <p v-if="message.subject"><span class="font-medium">Sujet:</span> {{ message.subject }}</p>
                        <p><span class="font-medium">Date:</span> {{ formatDate(message.send_date) }}</p>
                    </div>
                </div>
            </div>

            <div v-if="!props.messages?.data?.length" class="text-center py-8 text-gray-500">
                Aucun message enregistré
            </div>
        </div>


        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                v-if="props.messages"
                :columns="messageColumns"
                :data="props.messages.data"
                :actions="messageActions"
                :selectable="false"
                empty-message="Aucun message enregistré"
                @sort="handleSort"
            >
                <template #cell-type="{ row }">
                    <span
                        :class="[
                            'px-2 py-1 rounded-full text-xs font-medium',
                            row.type === 'volunteer' ? 'bg-lightsweetorange/40 text-orange-700' :
                            'bg-blue-300/20 text-blueslate'
                        ]"
                    >
                        {{ getTypeLabel(row.type) }}
                    </span>
                </template>

                <template #cell-name="{ row }">
                    {{ row.name }}
                </template>

                <template #cell-email="{ row }">
                    {{ row.email }}
                </template>

                <template #cell-phone="{ row }">
                    {{ row.phone }}
                </template>

                <template #cell-subject="{ row }">
                    <span class="truncate max-w-xs block">
                        {{ row.subject || '-' }}
                    </span>
                </template>

                <template #cell-status="{ row }">
                    <span
                        :class="[
                            'px-2 py-1 rounded-full text-xs font-medium border',
                            row.status === 'nouveau' ? 'bg-lightgreenmint/20 text-green-900 border-greenmint' :
                            row.status === 'lu' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                            'bg-gray-100 text-gray-900 border-gray-300'
                        ]"
                    >
                        {{ getStatusLabel(row.status) }}
                    </span>
                </template>

                <template #cell-send_date="{ row }">
                    {{ formatDate(row.send_date) }}
                </template>
            </GenericTable>
        </div>

        <Pagination :links="props.messages?.links"/>
    </section>

    <CenterModal v-model="showDeleteConfirm">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <TrashIcon class="w-6 h-6 text-red-600"/>
            </div>

            <h3 class="text-lg font-semibold text-center mb-2">
                Confirmer l'archivage
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir archiver le message de
                <span class="font-semibold">{{ messageToDelete?.name }}</span> ?
            </p>

            <div class="flex gap-3 justify-end">
                <button
                    @click="showDeleteConfirm = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    @click="destroyMessage"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Archiver
                </button>
            </div>
        </div>
    </CenterModal>

    <CenterModal
        v-model="isShowModalOpen"
        route-key="message"
        :route-value="selectedMessage?.id"
        v-if="selectedMessage"

    >
        <ContactMessagesShow :message="selectedMessage" />
    </CenterModal>
</template>

<style scoped>
</style>
