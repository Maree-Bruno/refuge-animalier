<script setup>


import {useFormatDate} from "@/composables/useFormatDate";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";

const props = defineProps({
    message: Object
})
const {formatDate} = useFormatDate();

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

const toast = useToasterStore();
const showDeleteConfirm = ref(false);
const openDeleteConfirm = () => {
    showDeleteConfirm.value = true;
};

const destroyMessage = () => {
    if (!props.message) return;

    router.delete(`/admin/contact-messages/${props.message.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'Message archivé avec succès'});
            showDeleteConfirm.value = false;
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est survenue pendant l\'archivage'});
        }
    });
}
</script>

<template>
    <section class="w-full h-full">
        <div class="mx-auto my-8 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden font-sans">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ props.message?.name }}</h2>
                        <div class="mt-2 flex items-center gap-2">
          <span :class="[
                            'px-2 py-1 rounded-full text-xs font-medium',
                            message.type === 'volunteer' ? 'bg-lightsweetorange/40 text-orange-700' :
                            'bg-blue-300/20 text-blueslate'
                        ]">
            {{ getTypeLabel(props.message?.type) }}
          </span>
                            <span
                                :class="[
                            'px-2 py-1 rounded-full text-xs font-medium border',
                            message.status === 'nouveau' ? 'bg-lightgreenmint/20 text-green-900 border-greenmint' :
                            message.status === 'lu' ? 'bg-lightsweetorange/20 text-orange-900 border-sweetorange' :
                            'bg-gray-100 text-gray-900 border-gray-300'
                        ]"
                                >
            {{ getStatusLabel(props.message?.status) }}
          </span>
                        </div>
                    </div>
                    <div class="text-right space-y-2">
                        <p class="text-sm text-gray-500">{{ formatDate(message.send_date) }}</p>
                        <button
                            v-if="message.status !== 'archivé'"
                            @click="openDeleteConfirm"
                            class="px-4 py-2 font-semibold button-orange rounded-lg button-animation"
                        >
                            Archiver
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Email</label>
                        <p class="text-gray-900 font-medium">{{ props.message?.email }}</p>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Téléphone</label>
                        <p class="text-gray-900 font-medium">{{ props.message?.phone }}</p>
                    </div>

                    <div v-if="props.message?.type === 'volunteer'" class="col-span-full">
                        <label class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Adresse</label>
                        <p class="text-gray-900 font-medium">
                            {{ props.message?.address }} {{ props.message?.number }}<br>
                            {{ props.message?.cp }} {{ props.message?.city }}
                        </p>
                    </div>

                    <div class="col-span-full">
                        <label class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Sujet</label>
                        <p class="text-gray-900 font-medium">{{ props.message?.subject || 'Pas de sujet spécifié' }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <label class="text-xs uppercase tracking-wider text-gray-400 font-semibold block mb-3">Message</label>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-700 leading-relaxed whitespace-pre-wrap ring-1 ring-inset ring-gray-200/50">
                        {{ props.message?.message }}
                    </div>
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
                Confirmer l'archivage
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir archiver le message de
                <span class="font-semibold">{{ message?.name }}</span> ?
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
</template>

<style scoped>

</style>
