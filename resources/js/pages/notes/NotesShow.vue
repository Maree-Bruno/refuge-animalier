<script setup>
import {useFormatDate} from "@/composables/useFormatDate";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";
import VolunteerEdit from "@/pages/volunteers/VolunteerEdit.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import NoteEdit from "@/pages/notes/NoteEdit.vue";

const props = defineProps({
    note: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
    animals: Array,
    adoptionRequests: Array,
})
const toast = useToasterStore();


const getNotableLabel = (notable) => {
    if (!notable) return 'N/A';
    return ` ${notable.name || notable.id}`;
}

const {formatDate} = useFormatDate();
const showDeleteConfirm = ref(false);
const showEdit = ref(false);

const destroyNote = () => {
    if (!props.note) return;

    router.delete(`/notes/${props.note.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'La note a bien été supprimée'});
            showDeleteConfirm.value = false;
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est survenue pendant la suppression de la note'})
        }
    });
}
</script>

<template>
    <section class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-scroll">
            <div class="flex justify-between">
                <h2 class="text-2xl font-bold mb-4">{{ note?.title }}</h2>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <button
                        @click="showEdit = true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Modifier
                    </button>
                    <button
                        @click="showDeleteConfirm = true"
                        class="button-orange button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Supprimer
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Contenu</p>
                    <p class="text-gray-800 mt-1">{{ note?.content }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Lié à</p>
                    <p class="text-gray-800 mt-1">{{ note?.notable_name }}</p>

                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de création</p>
                    <p class="text-gray-800 mt-1">{{ formatDate(note?.created_at) }}</p>
                </div>
            </div>
        </div>
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
                <span class="font-semibold">{{ note?.title }}</span> ?
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
    <RightModal v-model="showEdit" @update:modelValue="showEdit = $event">
        <template #header>
            <h2 class="subsubtitle">Modifier {{ note?.title }}</h2>
        </template>
        <NoteEdit
        :note="note"
        :filters="filters"
        :adoptioRequest="adoptionRequest"
        :animals="animals"
        />
    </RightModal>
</template>

<style scoped>

</style>
