<script setup>
import {computed, ref, watch} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import {useToasterStore} from '@/stores/useToasterStore';

const props = defineProps({
    species: Object,
    races: Object,
    coats: Object,
    suitableTypes: Object,
    vaccines: Object,
});

const page = usePage();
const toast = useToasterStore();

const ressourceId = computed(() => page.url.split('ressource=')[1]?.split('&')[0]);

const currentRessource = computed(() => {
    if (!ressourceId.value) return null;

    const allResources = [
        ...props.vaccines?.data.map(v => ({...v, type: 'vaccine', typeName: 'Vaccin'})) || [],
        ...props.species?.data.map(s => ({...s, type: 'specie', typeName: 'Espèce'})) || [],
        ...props.races?.data.map(r => ({...r, type: 'race', typeName: 'Race'})) || [],
        ...props.coats?.data.map(c => ({...c, type: 'coat', typeName: 'Pelage'})) || [],
        ...props.suitableTypes?.data.map(s => ({...s, type: 'suitable_type', typeName: 'Convient pour'})) || [],
    ];

    return allResources.find(r => r.id === parseInt(ressourceId.value));
});

const form = ref({
    model_type: '',
    name: '',
    key: '',
    specie_id: null
});

const errors = ref({});
const isEditing = ref(false);

watch(currentRessource, (ressource) => {
    if (ressource) {
        form.value = {
            model_type: ressource.type,
            name: ressource.name,
            key: ressource.key || '',
            specie_id: ressource.specie_id || null
        };
    }
}, {immediate: true});

const needsSpecieSelection = computed(() => {
    return ['vaccine', 'race'].includes(form.value.model_type);
});

const needsKey = computed(() => {
    return form.value.model_type === 'suitable_type';
});

const availableSpecies = computed(() => {
    return props.species?.data || [];
});

const updateRessource = () => {
    errors.value = {};

    router.patch(`/database/${ressourceId.value}`, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'La ressource a été mise à jour avec succès'});
            isEditing.value = false;
        },
        onError: (errs) => {
            errors.value = errs;
            toast.error({text: 'Une erreur est survenue lors de la mise à jour'});
        }
    });
};

const cancelEdit = () => {
    if (currentRessource.value) {
        form.value = {
            model_type: currentRessource.value.type,
            name: currentRessource.value.name,
            key: currentRessource.value.key || '',
            specie_id: currentRessource.value.specie_id || null
        };
    }
    errors.value = {};
    isEditing.value = false;
};

const getSpecieName = (specieId) => {
    const specie = availableSpecies.value.find(s => s.id === specieId);
    return specie?.name || 'Non spécifié';
};
</script>

<template>
    <div v-if="currentRessource" class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ currentRessource.name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ currentRessource.typeName }}</p>
            </div>
            <button
                v-if="!isEditing"
                @click="isEditing = true"
                class="px-4 py-2 bg-blueslate text-white rounded-lg hover:bg-blueslate/90 transition-colors"
            >
                Modifier
            </button>
        </div>

        <div v-if="!isEditing" class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <p class="text-gray-900">{{ currentRessource.name }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <p class="text-gray-900">{{ currentRessource.typeName }}</p>
            </div>

            <div v-if="currentRessource.specie_id" class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Espèce</label>
                <p class="text-gray-900">{{ getSpecieName(currentRessource.specie_id) }}</p>
            </div>

            <div v-if="currentRessource.key" class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Clé</label>
                <p class="text-gray-900 font-mono text-sm">{{ currentRessource.key }}</p>
            </div>
        </div>

        <form v-else @submit.prevent="updateRessource" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de ressource</label>
                <input
                    type="text"
                    :value="currentRessource.typeName"
                    disabled
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom <span class="">*</span>
                </label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                    :class="errors.name ? 'border-red-500' : 'border-gray-300'"
                    placeholder="Nom de la ressource"
                >
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div v-if="needsSpecieSelection">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Espèce <span class="">*</span>
                </label>
                <select
                    v-model="form.specie_id"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                    :class="errors.specie_id ? 'border-red-500' : 'border-gray-300'"
                >
                    <option :value="null">Sélectionner une espèce</option>
                    <option v-for="specie in availableSpecies" :key="specie.id" :value="specie.id">
                        {{ specie.name }}
                    </option>
                </select>
                <p v-if="errors.specie_id" class="mt-1 text-sm text-red-600">{{ errors.specie_id }}</p>
            </div>

            <div v-if="needsKey">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Clé <span class="">*</span>
                </label>
                <input
                    v-model="form.key"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent font-mono text-sm"
                    :class="errors.key ? 'border-red-500' : 'border-gray-300'"
                    placeholder="cle_unique"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Utilisée pour identifier cette ressource dans le système
                </p>
                <p v-if="errors.key" class="mt-1 text-sm text-red-600">{{ errors.key }}</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    @click="cancelEdit"
                    class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    type="submit"
                    class="flex-1 px-4 py-2 text-white bg-blueslate rounded-lg hover:bg-blueslate/90 transition-colors"
                >
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <div v-else class="p-6 text-center text-gray-500">
        <p>Ressource introuvable</p>
    </div>
</template>

<style scoped>
</style>
