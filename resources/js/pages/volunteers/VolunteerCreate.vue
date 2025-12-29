<script setup>

import InputLabel from "@/components/widgets/form/InputLabel.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed, onUnmounted, ref} from "vue";
import {useToasterStore} from "@/stores/useToasterStore";
import {store} from "@/routes/volunteers";
import {useImagePreview} from "@/composables/useImagePreview.ts";
import Select from "@/components/widgets/form/Select.vue";
import { faker } from '@faker-js/faker';

faker.seed(123);

const emit = defineEmits(['close']);
const { previewUrl, handleSingleImage, removeSinglePreview, cleanup } = useImagePreview();

let formVolunteer = useForm({
    name:'',
    picture: null,
    phone: '',
    email: '',
    address: '',
    city: '',
    number: '',
    cp: '',
    role: '',
    preserveState: false,
});

const props = defineProps({
    roles: Object
})

const availableRoles = computed(() => props.roles);

const toast = useToasterStore();
const seedForm = () => {
    formVolunteer.name = faker.person.fullName();
    formVolunteer.email = faker.internet.email();
    formVolunteer.phone = faker.phone.number();
    formVolunteer.address = faker.location.streetAddress();
    formVolunteer.number = faker.location.buildingNumber();
    formVolunteer.city = faker.location.city();
    formVolunteer.cp = faker.location.zipCode();
    if (availableRoles.value && availableRoles.value.length > 0) {
        const randomRole = availableRoles.value[Math.floor(Math.random() * availableRoles.value.length)];
        formVolunteer.role = randomRole.value;
    }
};

const handlePicture = (event) => {
    const file = handleSingleImage(event);
    if (file) {
        formVolunteer.picture = file;
    }
};

const removePicture = () => {
    removeSinglePreview();
    formVolunteer.picture = null;
};

const submitVolunteer = () => {
    formVolunteer.post(store(), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Bénévole ajouté avec succès'});
            formVolunteer.reset();
            removeSinglePreview();
            emit('close');
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            toast.error({text: 'Une erreur est apparue lors de la création'});
        }
    });
};

onUnmounted(() => {
    cleanup();
});
</script>

<template>
    <form @submit.prevent="submitVolunteer" enctype="multipart/form-data">
        <div class="space-y-6 flex flex-col justify-center">
            <button
                type="button"
                @click="seedForm"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md w-fit self-end text-sm"
            >
                🎲 Remplir avec Faker
            </button>

            <InputLabel
                nameId="name"
                type="text"
                placeholder="Nom du bénévole"
                :message="formVolunteer.errors.name"
                v-model="formVolunteer.name"
                :required="true"
                maxlength="30"
            >
                Nom du bénévole
            </InputLabel>

            <InputLabel
                nameId="email"
                type="email"
                placeholder="Email du bénévole"
                :message="formVolunteer.errors.email"
                v-model="formVolunteer.email"
                :required="true"
            >
                Email
            </InputLabel>

            <InputLabel
                nameId="phone"
                type="tel"
                placeholder="Téléphone du bénévole"
                :message="formVolunteer.errors.phone"
                v-model="formVolunteer.phone"
                :required="true"
            >
                Téléphone
            </InputLabel>
            <div class="space-y-8">
                <div class="flex gap-8">
                    <InputLabel
                        nameId="address"
                        type="text"
                        placeholder="Rue de la paix"
                        :message="formVolunteer.errors.address"
                        v-model="formVolunteer.address"
                    >
                        Adresse
                    </InputLabel>
                    <InputLabel
                        nameId="number"
                        type="text"
                        placeholder="23"
                        :message="formVolunteer.errors.number"
                        v-model="formVolunteer.number"
                    >
                        Numéro
                    </InputLabel>
                </div>
                <div class="flex gap-8">
                    <InputLabel
                        nameId="city"
                        type="text"
                        placeholder="Liège"
                        :message="formVolunteer.errors.city"
                        v-model="formVolunteer.city"
                    >
                        Localité
                    </InputLabel>
                    <InputLabel
                        nameId="cp"
                        type="text"
                        placeholder="4000"
                        :message="formVolunteer.errors.cp"
                        v-model="formVolunteer.cp"
                    >
                        Code Postale
                    </InputLabel>
                </div>

            </div>

            <div class="flex flex-col gap-2">
                <Select nameId="role" v-model="formVolunteer.role" label="Rôle du bénévole"
                        :convertToNumber="false"
                >
                    <option value="" selected>Sélectionner un rôle</option>
                    <option
                        v-for="role in availableRoles"
                        :key="role.value"
                        :value="role.value"
                    >
                        {{ role.label }}
                    </option>

                </Select>
                <span v-if="formVolunteer.errors.role" class="text-red-500 text-sm">
                    {{ formVolunteer.errors.role }}
                </span>
            </div>

            <div class="flex flex-col gap-2">
                <label for="picture" class="font-semibold text-gray-700">
                    Photo (optionnelle)
                </label>
                <div v-if="previewUrl" class="mb-2 inline-block w-fit relative">
                    <img
                        :src="previewUrl"
                        alt="Preview"
                        class="w-32 h-32 object-cover rounded-lg border-2 border-sweetorange"
                    >
                    <button
                        type="button"
                        @click="removePicture"
                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center transition-colors"
                        title="Supprimer la photo"
                    >
                        ×
                    </button>
                </div>

                <input
                    id="picture"
                    type="file"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    @change="handlePicture"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2"
                    :class="{'border-red-500': formVolunteer.errors.picture}"
                >
                <span v-if="formVolunteer.errors.picture" class="text-red-500 text-sm">
                    {{ formVolunteer.errors.picture }}
                </span>
            </div>
        </div>

        <button
            type="submit"
            :disabled="formVolunteer.processing"
            class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit place-self-center font-bold disabled:opacity-50 disabled:cursor-not-allowed mt-6">
            <SaveIcon class="w-6 h-6 svg-strokeblack"/>
            {{ formVolunteer.processing ? 'Création...' : `Créer ${formVolunteer.name || 'le bénévole'}` }}
        </button>
    </form>
</template>
