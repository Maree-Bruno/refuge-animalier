<script setup lang="ts">
import {ref, computed, onUnmounted} from 'vue'
import {Head, Link, usePage, useForm} from '@inertiajs/vue3'
import {send} from '@/routes/verification'

import DeleteUser from '@/components/DeleteUser.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import InputError from '@/components/InputError.vue'
import {Button} from '@/components/ui/button'
import {Input} from '@/components/ui/input'
import {Label} from '@/components/ui/label'
import SettingsLayout from '@/layouts/settings/Layout.vue'
import {useUserHelpers} from "@/composables/useUserHelpers";
import {useImagePreview} from "@/composables/useImagePreview";
import InputLabel from "@/components/widgets/form/InputLabel.vue";

interface Props {
    mustVerifyEmail: boolean
    status?: string
}

const {previewUrl, handleSingleImage, removeSinglePreview, cleanup} = useImagePreview();
const {getInitials, getUserImageUrl, getUserImageSrcset} = useUserHelpers();

const page = usePage();
const user = page.props.auth.user;
const picture = page.props.picture;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone,
    address: user.address,
    city: user.city,
    number: user.number,
    cp: user.cp,
    availability: user.availability ?? null,
    picture: null,
});
const handlePicture = (event: Event) => {
    const file = handleSingleImage(event);
    if (file) {
        form.picture = file;
    }
};

const removePicture = () => {
    removeSinglePreview();
    form.picture = null;
};

const getProfileImageUrl = (size: 'xs' | 'sm' | 'md' | 'lg' = 'md') => {
    if (previewUrl.value) return previewUrl.value;
    return getUserImageUrl(picture, size);
};

const getProfileImageSrcset = () => {
    if (previewUrl.value || !picture) return '';
    return getUserImageSrcset(picture);
};

const submit = () => {
    form.patch('/admin/settings/profile', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            removeSinglePreview();
        }
    });
};

onUnmounted(() => {
    cleanup();
});
</script>


<template>
    <Head title="Profile settings"/>

    <SettingsLayout>
        <div class="flex flex-col space-y-6">
            <HeadingSmall
                title="Information du profil"
                description="Changer votre nom, adresse mail, adresse postale, photo de profil"
            />

            <Form
                :form="form"
                enctype="multipart/form-data"
                class="space-y-6"
                @submit.prevent="submit"
            >
                <div class="space-y-2">
                    <Label class="font-semibold text-gray-700 text-sm sm:text-lg">Photo de profil</Label>
                    <div class="flex gap-4 items-center justify-center">
                        <div class="relative w-32 h-28">
                            <img
                                v-if="previewUrl || picture"
                                :src="getProfileImageUrl('md')"
                                :srcset="getProfileImageSrcset()"
                                sizes="(max-width: 640px) 150px, 300px"
                                class="w-32 h-28 object-cover aspect-square rounded-lg border"
                                :alt="`Photo de profil de ${form.name}`"
                            />
                            <span
                                v-else
                                class="w-32 h-28 rounded-lg border flex items-center justify-center bg-sweetorange text-white font-semibold text-4xl"
                            >
                        {{ getInitials(form.name) }}
                    </span>
                            <button
                                v-if="previewUrl"
                                type="button"
                                @click="removePicture"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full px-2"
                            >
                                &times;
                            </button>
                        </div>
                        <input
                            type="file"
                            name="picture"
                            accept="image/png,image/jpg,image/jpeg,image/webp"
                            @change="handlePicture"
                            class="block w-full text-sm border rounded-lg p-2"
                        />
                    </div>
                    <InputError :message="form.errors.picture"/>
                    <p class="text-sm text-muted-foreground">
                        Les formats supportés sont le PNG, JPEG et WEBp.
                    </p>
                </div>
                <InputLabel
                    nameId="name"
                    type="text"
                    placeholder="John Doe"
                    :message="form.errors.name"
                    v-model="form.name"
                >
                    Nom
                </InputLabel>
                <InputLabel
                    nameId="email"
                    type="email"
                    placeholder="John Doe"
                    :message="form.errors.email"
                    v-model="form.email"
                >
                    Adresse email
                </InputLabel>

                <div v-if="mustVerifyEmail && !user.email_verified_at">
                    <p class="text-sm">
                        Email not verified.
                        <Link :href="send()" as="button" class="underline ml-1">
                            Resend
                        </Link>
                    </p>
                </div>

                <InputLabel
                    nameId="phone"
                    type="tel"
                    placeholder="Téléphone"
                    :message="form.errors.phone"
                    v-model="form.phone"
                >
                    Téléphone
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

                <div class="flex items-center gap-4">
                    <button
                        class="flex items-center gap-2.5 rounded-md cursor-pointer text-blueslate py-2 px-4 transition-all button-animation button-yellow w-full justify-center"
                        :disabled="form.processing">Sauvegarder
                    </button>
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                        Saved.
                    </p>
                </div>
            </Form>
        </div>

        <DeleteUser/>
    </SettingsLayout>
</template>

