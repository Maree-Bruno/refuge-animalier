<script setup lang="ts">
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { store } from "@/routes/database";
import Select from "@/components/widgets/form/Select.vue";
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import InputError from "@/components/InputError.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import { useToasterStore } from "@/stores/useToasterStore";

const props = defineProps({
    vaccines: Object,
    species: Object,
    races: Object,
    coats: Object,
    suitableTypes: Object,
});

const emit = defineEmits(['close']);
const toast = useToasterStore();

const form = useForm({
    model_type: '',
    name: '',
    key: '',
    specie_id: null
});

const modelTypes = [
    { value: 'vaccine', label: 'Vaccin' },
    { value: 'specie', label: 'Espèce' },
    { value: 'race', label: 'Race' },
    { value: 'coat', label: 'Pelage' },
    { value: 'suitable_type', label: 'Convient pour' }
];

const requiresKey = computed(() => {
    return form.model_type === 'suitable_type';
});

const requiresSpecies = computed(() => {
    return form.model_type === 'vaccine' || form.model_type === 'race';
});

const speciesList = computed(() => {
    return props.species?.data || [];
});


const generateKey = (name: string): string => {
    return name
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');
};


watch(() => form.name, (newName) => {
    if (requiresKey.value && newName) {
        form.key = generateKey(newName);
    }
});


watch(() => form.model_type, () => {
    form.key = '';
});

const submit = () => {
    form.post(store(), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({ text: 'Ressource créée avec succès' });
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            toast.error({ text: 'Une erreur est apparue lors de la création' });
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="space-y-10 flex flex-col justify-center">
            <div class="flex flex-col gap-2 w-full">
                <Select
                    nameId="model_type"
                    v-model="form.model_type"
                    label="Type d'entrée *"
                    :convertToNumber="false"
                >
                    <option value="">-- Choisir un type --</option>
                    <option
                        v-for="type in modelTypes"
                        :key="type.value"
                        :value="type.value"
                    >
                        {{ type.label }}
                    </option>
                </Select>
                <InputError :message="form.errors.model_type" />
            </div>

            <InputLabel
                nameId="name"
                type="text"
                placeholder="Nom de la ressource"
                :message="form.errors.name"
                v-model="form.name"
                :required="true"
                maxlength="255"
            >
                Nom *
            </InputLabel>

            <div v-if="requiresSpecies" class="flex flex-col gap-2 w-full">
                <Select
                    nameId="specie_id"
                    v-model="form.specie_id"
                    label="Espèce *"
                >
                    <option :value="null">-- Choisir une espèce --</option>
                    <option
                        v-for="specie in speciesList"
                        :key="specie.id"
                        :value="specie.id"
                    >
                        {{ specie.name }}
                    </option>
                </Select>
                <InputError :message="form.errors.specie_id" />
                <p class="text-gray-500 text-sm">
                    Les {{ form.model_type === 'vaccine' ? 'vaccins' : 'races' }} doivent être liés à une espèce
                </p>
            </div>

            <div v-if="requiresKey" class="flex flex-col gap-2 w-full">
                <InputLabel
                    nameId="key"
                    type="text"
                    placeholder="cle_automatique"
                    :message="form.errors.key"
                    v-model="form.key"
                    :required="true"
                    maxlength="50"
                >
                    Clé d'identification *
                </InputLabel>
                <p class="text-gray-500 text-sm">
                    La clé est générée automatiquement à partir du nom. Vous pouvez la modifier si nécessaire.
                    <br>
                    <span class="text-xs italic">Format recommandé : minuscules, sans accents, séparés par des underscores (_)</span>
                </p>
                <InputError :message="form.errors.key" />
            </div>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit place-self-center font-bold disabled:opacity-50 disabled:cursor-not-allowed mt-6"
        >
            <SaveIcon class="w-6 h-6 svg-strokeblack"/>
            {{ form.processing ? 'Création...' : 'Créer la ressource' }}
        </button>
    </form>
</template>

<style scoped>
</style>
