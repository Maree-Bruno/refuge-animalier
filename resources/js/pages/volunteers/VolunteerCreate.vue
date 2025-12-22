<script setup>

import InputLabel from "@/components/widgets/form/InputLabel.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed, onUnmounted, ref} from "vue";
import {useToasterStore} from "@/stores/useToasterStore";
import {store} from "@/routes/volunteers";
import {useImagePreview} from "@/composables/useImagePreview.ts";
import Select from "@/components/widgets/form/Select.vue";

const emit = defineEmits(['close']);
const { previewUrl, handleSingleImage, removeSinglePreview, cleanup } = useImagePreview();

let formVolunteer = useForm({
    name: '',
    picture: null,
    phone: '',
    email: '',
    role: '',
    preserveState: false,
});
const props = defineProps({
    roles: Object
})
console.log(props.roles)
const availableRoles = computed(() => props.roles);


const toast = useToasterStore();

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

            <div class="flex flex-col gap-2">
                <Select nameId="role" v-model="formVolunteer.role" label="Rôle du bénévole"
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
