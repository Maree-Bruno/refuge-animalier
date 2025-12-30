<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    note: {
        type: Object,
        required: true
    },
    animals: Array,
    adoptionRequests: Array,
});

const emit = defineEmits(['close']);

const toast = useToasterStore();

const form = useForm({
    title: props.note?.title || '',
    content: props.note?.content || '',
});

const submit = () => {
    form.put(`/notes/${props.note.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Note modifiée avec succès'});
            emit('close');
        },
        onError: (errors) => {
            toast.error({text: 'Erreur lors de la modification de la note'});
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col gap-4 p-4 sm:p-6">
        <div class="flex flex-col gap-2">
            <label for="title" class="text-sm font-medium text-gray-700">
                Titre <span class="text-red-500">*</span>
            </label>
            <input
                id="title"
                v-model="form.title"
                type="text"
                placeholder="Titre de la note"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                :class="{'border-red-500': form.errors.title}"
            />
            <span v-if="form.errors.title" class="text-sm text-red-500">
                {{ form.errors.title }}
            </span>
        </div>

        <div class="flex flex-col gap-2">
            <label for="content" class="text-sm font-medium text-gray-700">
                Contenu <span class="text-red-500">*</span>
            </label>
            <textarea
                id="content"
                v-model="form.content"
                rows="6"
                placeholder="Contenu de la note..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent resize-none"
                :class="{'border-red-500': form.errors.content}"
            ></textarea>
            <span v-if="form.errors.content" class="text-sm text-red-500">
                {{ form.errors.content }}
            </span>
        </div>

        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm text-gray-600">
                <span class="font-medium">Lié à:</span>
                {{ note?.notable?.name || note?.notable_id }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                Type: {{ note?.notable_type?.split('\\').pop() }}
            </p>
        </div>

        <div class="flex gap-3 justify-end pt-4 border-t">
            <button
                type="button"
                @click="emit('close')"
                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                :disabled="form.processing"
            >
                Annuler
            </button>
            <button
                type="submit"
                class="button-yellow button-animation rounded-lg px-4 py-2 font-semibold"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Modification...' : 'Modifier la note' }}
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
