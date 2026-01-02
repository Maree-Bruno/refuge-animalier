<script setup>
import {ref, computed} from 'vue';
import {useForm} from '@inertiajs/vue3';
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    animals: {
        type: Array,
        default: () => []
    },
    adoptionRequests: {
        type: Array,
        default: () => []
    }
});
const emit = defineEmits(['close']);

const toast = useToasterStore();

const form = useForm({
    title: '',
    content: '',
    notable_type: '',
    notable_id: null,
});

const notableTypes = [
    {value: 'App\\Models\\Animal', label: 'Animal'},
    {value: 'App\\Models\\AdoptionRequest', label: 'Requête d\'adoption'},
];

const availableNotables = computed(() => {
    if (!form.notable_type) return [];

    if (form.notable_type === 'App\\Models\\Animal') {
        return props.animals || [];
    } else if (form.notable_type === 'App\\Models\\AdoptionRequest') {
        return props.adoptionRequests || [];
    }

    return [];
});

const getNotableLabel = (item) => {
    if (form.notable_type === 'App\\Models\\Animal') {
        return item.name || `Animal #${item.id}`;
    } else if (form.notable_type === 'App\\Models\\AdoptionRequest') {
        const adopterName = item.adopter?.name || 'Adoptant inconnu';
        const animalName = item.animal?.name || 'Animal inconnu';
        return `${adopterName} - ${animalName}`;
    }
    return `#${item.id}`;
};

const submit = () => {
    form.post('/admin/notes', {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Note créée avec succès'});
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            toast.error({text: 'Erreur lors de la création de la note'});
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
            <label for="notable_type" class="text-sm font-medium text-gray-700">
                Type d'entité <span class="text-red-500">*</span>
            </label>
            <select
                id="notable_type"
                v-model="form.notable_type"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                :class="{'border-red-500': form.errors.notable_type}"
                @change="form.notable_id = null"
            >
                <option value="">Sélectionner un type</option>
                <option
                    v-for="type in notableTypes"
                    :key="type.value"
                    :value="type.value"
                >
                    {{ type.label }}
                </option>
            </select>
            <span v-if="form.errors.notable_type" class="text-sm text-red-500">
                {{ form.errors.notable_type }}
            </span>
        </div>

        <div v-if="form.notable_type" class="flex flex-col gap-2">
            <label for="notable_id" class="text-sm font-medium text-gray-700">
                Sélectionner <span class="text-red-500">*</span>
            </label>
            <select
                id="notable_id"
                v-model="form.notable_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                :class="{'border-red-500': form.errors.notable_id}"
            >
                <option :value="null">Sélectionner</option>
                <option
                    v-for="item in availableNotables"
                    :key="item.id"
                    :value="item.id"
                >
                    {{ getNotableLabel(item) }}
                </option>
            </select>
            <span v-if="form.errors.notable_id" class="text-sm text-red-500">
                {{ form.errors.notable_id }}
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
                {{ form.processing ? 'Création...' : 'Créer la note' }}
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
