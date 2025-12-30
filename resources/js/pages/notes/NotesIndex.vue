<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useFormatDate} from "@/composables/useFormatDate.ts";
import {useToasterStore} from "@/stores/useToasterStore.ts";
import NoteCreate from "@/pages/notes/NoteCreate.vue";
import NotesShow from "@/pages/notes/NotesShow.vue";

const props = defineProps({
    notes: {
        type: Object
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    animals: Array,
    adoptionRequests: Array,
});

const toast = useToasterStore();
let search = ref(props.filters.search || '');
let activeTab = ref(props.filters.type || 'all');
let timeout = null;

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            search: value,
            type: activeTab.value,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const tabs = [
    {value: 'all', label: 'Toutes les notes'},
    {value: 'App\\Models\\Animal', label: 'Notes animaux'},
    {value: 'App\\Models\\AdoptionRequest', label: 'Notes requêtes d\'adoption'},
];

const switchTab = (tabValue) => {
    activeTab.value = tabValue;
    router.get(window.location.pathname, {
        search: search.value,
        type: tabValue,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const showDeleteConfirm = ref(false);
const noteToDelete = ref(null);
const isShowModalOpen = ref(false);
const selectedNote = ref(null);
const showCreateNote = ref(false);

const openShowModal = (note) => {
    selectedNote.value = note;
    isShowModalOpen.value = true;
};

const openDeleteConfirm = (note) => {
    noteToDelete.value = note;
    showDeleteConfirm.value = true;
};

const destroyNote = () => {
    if (!noteToDelete.value) return;

    router.delete(`/notes/${noteToDelete.value.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'La note a bien été supprimée'});
            showDeleteConfirm.value = false;
            noteToDelete.value = null;
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est survenue pendant la suppression de la note'})
        }
    });
}

const noteColumns = [
    {key: 'title', label: 'Titre'},
    {key: 'content', label: 'Contenu'},
    {key: 'notable', label: 'Lié à'},
    {key: 'created_at', label: 'Date de création'},
];

const noteActions = [
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
        search: search.value,
        type: activeTab.value,
        orderby: key,
        dir: order,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleNoteRowSelect = (selected) => {

};

const getNotableLabel = (notable) => {
    if (!notable) return 'N/A';
    return ` ${notable.name || notable.id}`;
}

const {formatDate} = useFormatDate();
</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <h3 class="sr-only">Notes</h3>
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
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6"/>
                    <input
                        type="text"
                        placeholder="Rechercher une note..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>

                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-3">
                    <button
                        @click="showCreateNote=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter une note
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="note in props.notes?.data"
                :key="note.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
                @click="openShowModal(note)"
            >
                <div class="flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="font-semibold text-base">{{ note.title }}</h4>
                    </div>

                    <div class="space-y-1 text-xs text-gray-600">
                        <p class="line-clamp-2">{{ note.content }}</p>
                        <p><span class="font-medium">Lié à:</span>   {{ note.notable_name ?? '—' }}</p>
                        <p><span class="font-medium">Date:</span> {{ formatDate(note.created_at) }}</p>
                    </div>
                </div>
            </div>

            <div v-if="!props.notes?.data?.length" class="text-center py-8 text-gray-500">
                Aucune note enregistrée
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                v-if="props.notes"
                :columns="noteColumns"
                :data="props.notes.data"
                :actions="noteActions"
                :selectable="true"
                empty-message="Aucune note enregistrée"
                @row-select="handleNoteRowSelect"
                @sort="handleSort"
            >
                <template #cell-title="{ row }">
                    <span class="font-medium">{{ row.title }}</span>
                </template>

                <template #cell-content="{ row }">
                    <span class="line-clamp-2">{{ row.content }}</span>
                </template>

                <template #cell-notable="{ row }">
                    <div class="flex flex-col">
        <span class="text-sm text-gray-700 font-medium">
            {{ row.notable_name ?? '—' }}
        </span>
                        <span class="text-xs text-gray-400">
            {{ row.notable_type_label }}
        </span>
                    </div>
                </template>

                <template #cell-created_at="{ row }">
                    {{ formatDate(row.created_at) }}
                </template>
            </GenericTable>
        </div>

        <Pagination :links="props.notes?.links"/>
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
                Êtes-vous sûr de vouloir supprimer la note
                <span class="font-semibold">{{ noteToDelete?.title }}</span> ?
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
                    @click="destroyNote"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
    <CenterModal v-model="isShowModalOpen"
                 route-key="note"
                 :route-value="selectedNote?.id">
        <NotesShow
            :note="selectedNote"
            :filters="filters"
            :animals="animals"
            :adoptionRequests="adoptionRequests"
        />
    </CenterModal>

    <KeepAlive>
        <RightModal v-model="showCreateNote" route-key="create" route-value="create-note">
            <template #header>
                <h2 class="subsubtitle">Nouvelle note</h2>
            </template>
            <NoteCreate
                :animals="animals"
                :adoptionRequests="adoptionRequests"
                @close="showCreateNote = false"
            />
        </RightModal>
    </KeepAlive>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
