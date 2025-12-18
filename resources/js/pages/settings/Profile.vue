<script setup lang="ts">
import {ref, computed} from 'vue'
import {Head, Link, usePage, useForm} from '@inertiajs/vue3'
import {send} from '@/routes/verification'

import DeleteUser from '@/components/DeleteUser.vue'
import HeadingSmall from '@/components/HeadingSmall.vue'
import InputError from '@/components/InputError.vue'
import {Button} from '@/components/ui/button'
import {Input} from '@/components/ui/input'
import {Label} from '@/components/ui/label'
import SettingsLayout from '@/layouts/settings/Layout.vue'

interface Props {
    mustVerifyEmail: boolean
    status?: string
}

defineProps<Props>()

const page = usePage()
const user = page.props.auth.user
const picture = page.props.picture

const previewPicture = ref<string | null>(null)

const form = useForm<{
    name: string
    email: string
    picture: File | null
}>({
    name: user.name,
    email: user.email,
    picture: null,
})

const handlePicture = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (!target.files || !target.files[0]) return

    const file: File = target.files[0]
    form.picture = file
    previewPicture.value = URL.createObjectURL(file)
}

const removePicture = () => {
    form.picture = null
    previewPicture.value = null
}

const submit = () => {
    form.patch('/settings/profile', {
        forceFormData: true,
        preserveScroll: true,
    })
}
const profileImageVariants = {
    xs: '64x64',
    sm: '128x128',
    md: '256x256',
    lg: '512x512',
};

const getProfileImageUrl = (size: 'xs' | 'sm' | 'md' | 'lg' = 'md') => {
    if (previewPicture.value) return previewPicture.value;
    if (!picture) return '/images/billy.webp';

    return `/images/users/variants/${profileImageVariants[size]}/${picture}`;
};

const getProfileImageSrcset = () => {
    if (previewPicture.value || !picture) return '';

    return Object.entries(profileImageVariants)
        .map(([key, size]) => `/images/users/variants/${size}/${picture} ${size.split('x')[0]}w`)
        .join(', ');
};

</script>


<template>
    <Head title="Profile settings"/>

    <SettingsLayout>
        <div class="flex flex-col space-y-6">
            <HeadingSmall
                title="Information du profil"
                description="Changer votre nom, adresse mail et photo de profil"
            />

            <Form
                :form="form"
                enctype="multipart/form-data"
                class="space-y-6"
                @submit.prevent="submit"
            >
                <div class="space-y-2">
                    <Label>Photo de profil</Label>
                    <div class="flex gap-4 items-center justify-center">
                        <div class="relative w-32 h-28">
                            <img
                                :src="getProfileImageUrl('md')"
                                :srcset="getProfileImageSrcset()"
                                sizes="(max-width: 640px) 150px, 300px"
                                class="w-32 h-28 object-cover aspect-square rounded-lg border"
                                :alt="`Photo de profil de ${form.name}`"
                            />
                            <button
                                v-if="previewPicture"
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
                <div class="grid gap-2">
                    <Label for="name">Nom</Label>
                    <Input id="name" name="name" v-model="form.name"/>
                    <InputError :message="form.errors.name"/>
                </div>

                <div class="grid gap-2">
                    <Label for="email">Adresse email</Label>
                    <Input id="email" name="email" v-model="form.email"/>
                    <InputError :message="form.errors.email"/>
                </div>

                <div v-if="mustVerifyEmail && !user.email_verified_at">
                    <p class="text-sm">
                        Email not verified.
                        <Link :href="send()" as="button" class="underline ml-1">
                            Resend
                        </Link>
                    </p>
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

