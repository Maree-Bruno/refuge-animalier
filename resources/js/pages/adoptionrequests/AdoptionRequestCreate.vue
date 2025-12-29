<script setup>
import {router, useForm} from "@inertiajs/vue3";
import {useToasterStore} from "@/stores/useToasterStore";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import TabbableTextarea from "@/components/widgets/form/TabbableTextarea.vue";
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {computed, ref} from "vue";
import CustomSelect from "@/components/widgets/form/CustomSelect.vue";
import {store} from "@/routes/adoption_requests/index.ts";

const props = defineProps({
    animals: {
        type: [Array, Object],
        default: () => [],
    },
    adopter: Object,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    suitableTypes: Object,
});
const availableAnimals = computed(() => {
    const animals = Array.isArray(props.animals)
        ? props.animals
        : props.animals?.data || [];

    return animals.filter(animal =>
        animal.status !== 'Adopted' &&
        animal.status !== 'In progress'
    );
});


let form = useForm({
    name: '',
    email: '',
    tel: '',
    address: '',
    number: '',
    cp: '',
    city: '',
    animal_id: '',
    status: '',
    message: '',
});

const toast = useToasterStore();
const emit = defineEmits(['close']);

const submit = () => {
    form.status = localStatus.value;

    form.post(store(), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: "Demande d'adoption ajoutée avec succès"});
            form.reset();
            emit('close');
        },
    });
};

const localStatus = ref(props.request?.status || '');
const statusOptions = [
    {value: 'submitted', label: 'Soumise', color: 'bg-lightblueslate/20 text-blueslate border-blueslate'},
    {value: 'pending', label: 'En attente', color: 'bg-lightsweetorange/20 text-orange-900 border-sweetorange'},
    {value: 'accepted', label: 'Acceptée', color: 'bg-lightgreenmint/50 text-green-900 border-greenmint'},
    {value: 'rejected', label: 'Refusée', color: 'bg-gray-100 text-gray-900 border-gray-300'}
];

console.log(form.animal_id);

</script>
<template>
    <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="space-y-10 flex flex-col justify-center">
            <InputLabel
                nameId="name"
                type="text"
                placeholder="Billy Joel"
                :message="form.errors.name"
                v-model="form.name"
                :required="true"
                maxlength="30"
            >
                Nom de l'adoptant
            </InputLabel>

            <InputLabel
                nameId="email"
                type="email"
                placeholder="billy@joel.com"
                :message="form.errors.email"
                v-model="form.email"
                :required="true"
            >
                Email de l'adoptant
            </InputLabel>

            <InputLabel
                nameId="tel"
                type="tel"
                placeholder="0478493827"
                :message="form.errors.tel"
                v-model="form.tel"
                :required="true"
            >
                Téléphone de l'adoptant
            </InputLabel>
            <div class="space-y-8">
                <div class="flex gap-8">
                    <InputLabel
                        nameId="address"
                        type="text"
                        placeholder="Rue de la paix"
                        :message="form.errors.address"
                        v-model="form.address"
                    >
                        Adresse
                    </InputLabel>
                    <InputLabel
                        nameId="number"
                        type="text"
                        placeholder="23"
                        :message="form.errors.number"
                        v-model="form.number"
                    >
                        Numéro
                    </InputLabel>
                </div>
                <div class="flex gap-8">
                    <InputLabel
                        nameId="city"
                        type="text"
                        placeholder="Liège"
                        :message="form.errors.city"
                        v-model="form.city"
                    >
                        Localité
                    </InputLabel>
                    <InputLabel
                        nameId="cp"
                        type="text"
                        placeholder="4000"
                        :message="form.errors.cp"
                        v-model="form.cp"
                    >
                        Code Postale
                    </InputLabel>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <CustomSelect
                    v-model="form.animal_id"
                    :options="availableAnimals"
                    label="Animal à adopter"
                    placeholder="Sélectionner un animal"
                    :error="form.errors.animal_id"
                />
            </div>

            <TabbableTextarea
                v-model="form.message"
                nameId="message"
                classTextarea="h-[200px]"
                :message="form.errors.message"
            >
                Message
            </TabbableTextarea>
            <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Statut de la demande</h4>
                <div class="flex items-center gap-3">
                    <select
                        v-model="localStatus"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option
                            v-for="option in statusOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>
                </div>
            </div>
            <button
                type="submit"
                :disabled="form.processing"
                class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit place-self-center font-bold disabled:opacity-50 disabled:cursor-not-allowed mt-6"
            >
                <SaveIcon class="w-6 h-6 svg-strokeblack"/>
                {{ form.processing ? 'Création...' : 'Créer la demande' }}
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
