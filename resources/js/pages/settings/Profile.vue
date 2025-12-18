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
                title="Profile information"
                description="Update your name and email address"
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
                        <div class="relative w-32">
                            <img
                                :src="getProfileImageUrl('md')"
                                :srcset="getProfileImageSrcset()"
                                sizes="(max-width: 640px) 150px, 300px"
                                class="w-32 h-32 object-cover aspect-square rounded-lg border"
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
                </div>


                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" name="name" v-model="form.name"/>
                    <InputError :message="form.errors.name"/>
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
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
                    <Button :disabled="form.processing">Save</Button>
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600">
                        Saved.
                    </p>
                </div>
            </Form>
        </div>

        <DeleteUser/>
    </SettingsLayout>
</template>

