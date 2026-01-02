<script setup>

import InputLabel from "@/components/widgets/form/InputLabel.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, onUnmounted, ref, watch} from "vue";
import {useToasterStore} from "@/stores/useToasterStore";
import {useImagePreview} from "@/composables/useImagePreview.ts";
import Select from "@/components/widgets/form/Select.vue";
import {useUserHelpers} from "@/composables/useUserHelpers.ts";

const emit = defineEmits(['close', 'update']);
const {previewUrl, handleSingleImage, removeSinglePreview, cleanup} = useImagePreview();
const {
    profileImageVariants,
    getUserImageUrl,
    getUserImageSrcset,
} = useUserHelpers();

let formVolunteer = useForm({
    name: '',
    picture: null,
    phone: '',
    email: '',
    address: '',
    city: '',
    number: '',
    cp: '',
    role: '',
    _method: 'PATCH',
    preserveState: false,
});

const props = defineProps({
    roles: Object,
    volunteer: Object
});

const availableRoles = computed(() => props.roles);
const toast = useToasterStore();

const existingPicture = ref('');


const handlePicture = (event) => {
    const file = handleSingleImage(event);
    if (file) {
        formVolunteer.picture = file;
        existingPicture.value = '';
    }
};

const removePicture = () => {
    removeSinglePreview();
    formVolunteer.picture = null;
    existingPicture.value = '';
};
const imageVariantsArray = computed(() =>
    Object.entries(profileImageVariants).map(([key, size]) => ({
        key,
        size,
        path: `/storage/users/variants/${size}`,
    }))
);
watch(() => props.volunteer, (newVolunteer) => {
    if (!newVolunteer) return;

    formVolunteer.name = newVolunteer.name || '';
    formVolunteer.email = newVolunteer.email || '';
    formVolunteer.phone = newVolunteer.phone || '';
    formVolunteer.address = newVolunteer.address || '';
    formVolunteer.number = newVolunteer.number || '';
    formVolunteer.city = newVolunteer.city || '';
    formVolunteer.cp = newVolunteer.cp || '';
    formVolunteer.role = newVolunteer.role || '';

    existingPicture.value = newVolunteer.picture
        ? getUserImageUrl(newVolunteer.picture, 'md')
        : '';

    formVolunteer.picture = null;
    removeSinglePreview();
}, { immediate: true });

const hasImage = computed(() => !!existingPicture.value);
const imageSrcset = computed(() => {
    if (!existingPicture.value) return '';
    return getUserImageSrcset(props.volunteer.picture);
});

const submitVolunteer = () => {
    const updateUrl = `/admin/volunteers/${props.volunteer.id}`;

    formVolunteer.post(updateUrl, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Bénévole mis à jour avec succès'});
            formVolunteer.preserveState = false;
            removeSinglePreview();
            emit('update');
            emit('close');
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            toast.error({text: 'Une erreur est apparue lors de la mise à jour'});
        }
    });
};
let deletingImage = ref(null);
const deleteImage = (filename) => {

    deletingImage.value = filename;

    router.delete(`/volunteers/${props.volunteer.id}/image`, {
        data: {filename},
        preserveScroll: true,
        onSuccess: () => {
            toast.success({ text: 'Image supprimée avec succès' });
            existingPicture.value = '';
            formVolunteer.picture = null;
            removeSinglePreview();
            deletingImage.value = null;
        },
        onError: (errors) => {
            toast.error({text: 'Erreur lors de la suppression de l\'image'});
            console.error(errors);
            deletingImage.value = null;
        }
    });
};
onUnmounted(() => {
    cleanup();
});

const displayedImage = computed(() => {
    if (previewUrl.value) {
        return previewUrl.value;
    }
    if (existingPicture.value) {
        return existingPicture.value;
    }
    return null;
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
                <Select nameId="role" v-model="formVolunteer.role" label="Rôle du bénévole" :convertToNumber="false">
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

                <div v-if="displayedImage" class="mb-2 inline-block w-fit relative">
                    <img
                        v-if="hasImage || previewUrl"
                        :src="displayedImage"
                        :srcset="previewUrl ? '' : imageSrcset"
                        alt="Photo de profil de {{formVolunteer.name}}"
                        class="w-32 h-32 object-cover rounded-lg border-2 border-sweetorange"
                    >
                    <button
                        type="button"
                        @click="deleteImage(props.volunteer.picture)"
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
            {{ formVolunteer.processing ? 'Mise à jour...' : `Modifier ${formVolunteer.name || 'le bénévole'}` }}
        </button>
    </form>
</template>
