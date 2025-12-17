<script setup>
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {store} from "@/routes/animals/index.ts";
import {computed, ref} from "vue";
import Select from "@/components/widgets/form/Select.vue";
import ButtonSvg from "@/components/widgets/button/ButtonSvg.vue";
import TabbableTextarea from "@/components/widgets/form/TabbableTextarea.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    species: Object,
    coats: Object,
    races: Object,
});

const emit = defineEmits(['close']);

const page = usePage();
const animals = computed(() => page.props.animals);

let formAnimal = useForm({
    name: '',
    age: '',
    chip: '',
    sex: '',
    specie_id: '',
    race_id: [],
    coat_id: [],
    suitable: [],
    status: '',
    outside: false,
    published: false,
    pictures: [],
    description: '',
});

const toast = useToasterStore();
let previewPictures = ref([]);

const handlePictures = (event) => {
    const files = Array.from(event.target.files)
    formAnimal.pictures = files
    previewPictures.value = files.map(file => URL.createObjectURL(file))
}

const submitAnimal = () => {
    formAnimal.post(store(), {
        forceFormData: true,
        onSuccess: () => {
            toast.success({ text: 'Animal ajouté avec succès' });
            formAnimal.reset();
            previewPictures.value = [];
            emit('close');
        },
        onError: (errors) => {
            toast.error({ text: 'Une erreur est apparue lors de la création' });
        }
    });
}
</script>

<template>
    <form @submit.prevent="submitAnimal" enctype="multipart/form-data">
        <div class="space-y-10 flex flex-col justify-center">
            <InputLabel
                nameId="name"
                type="text"
                placeholder="Nom de l'animal"
                :message="formAnimal.errors.name"
                v-model="formAnimal.name"
                :required="true"
                maxlength="30"
            >
                Nom de l'animal
            </InputLabel>

            <div class="flex gap-4">
                <InputLabel
                    nameId="age"
                    type="number"
                    placeholder="2"
                    :message="formAnimal.errors.age"
                    v-model="formAnimal.age"
                    :required="true"
                >
                    Âge de l'animal
                </InputLabel>

                <Select nameId="sex" v-model="formAnimal.sex" label="Sexe de l'animal">
                    <option value="" selected>-- Choisissez le sexe de l'animal --</option>
                    <option value="male">Mâle</option>
                    <option value="female">Femelle</option>
                </Select>
            </div>

            <InputLabel
                nameId="chip"
                type="text"
                placeholder="250 26 12 12345678"
                :message="formAnimal.errors.chip"
                v-model="formAnimal.chip"
            >
                Puce de l'animal
            </InputLabel>

            <div class="flex gap-4">
                <Select nameId="specie" v-model="formAnimal.specie_id" label="Espèce de l'animal">
                    <option value="">-- Choisissez une espèce --</option>
                    <option v-for="specie in species" :key="specie.id" :value="specie.id">
                        {{ specie.name }}
                    </option>
                </Select>

                <Select nameId="race" v-model="formAnimal.race_id" label="Race(s) de l'animal">
                    <option value="">-- Choisissez la/les race(s) --</option>
                    <option v-for="race in races" :key="race.id" :value="race.id">
                        {{ race.name }}
                    </option>
                </Select>

                <Select nameId="coat" v-model="formAnimal.coat_id" label="Pelage de l'animal">
                    <option value="">-- Choisissez le/les pelage(s) --</option>
                    <option v-for="coat in coats" :key="coat.id" :value="coat.id">
                        {{ coat.name }}
                    </option>
                </Select>
            </div>

            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Vaccins</p>
                <div class="flex gap-5">
                    <label for="vaccins-rubella" class="space-x-1">
                        <input id="vaccins-rubella" name="vaccins" type="checkbox" value="rubella">
                        Rubéole
                    </label>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Convient pour</p>
                <div class="flex gap-5">
                    <label for="dog" class="space-x-1">
                        <input id="dog" name="suitable" type="checkbox" value="dog"
                               v-model="formAnimal.suitable">
                        Chien
                    </label>
                    <label for="cat" class="space-x-1">
                        <input id="cat" name="suitable" type="checkbox" value="cat"
                               v-model="formAnimal.suitable">
                        Chat
                    </label>
                    <label for="kid" class="space-x-1">
                        <input id="kid" name="suitable" type="checkbox" value="kid"
                               v-model="formAnimal.suitable">
                        Enfant
                    </label>
                    <label for="baby" class="space-x-1">
                        <input id="baby" name="suitable" type="checkbox" value="baby"
                               v-model="formAnimal.suitable">
                        Bébé
                    </label>
                </div>
            </div>
            <div class="flex justify-between gap-5">
                <div class="w-full space-y-1">
                    <Select nameId="status" v-model="formAnimal.status" label="Status de l'animal">
                        <option value="">-- Choisissez un statut --</option>
                        <option value="Adopted">Adopté</option>
                        <option value="In progress">En cours</option>
                        <option value="Validated">Validé</option>
                    </Select>
                </div>
                <div class="space-y-1 w-full">
                    <p class="text-black font-semibold sm:text-lg leading-9">Sortir</p>
                    <label for="outside" class="space-x-1">
                        <input id="outside" name="outside" type="checkbox" v-model="formAnimal.outside">
                        <span v-if="formAnimal.outside">Autorisée</span>
                        <span v-else>Pas autorisée</span>
                    </label>
                </div>
                <div class="space-y-1 w-full">
                    <p class="text-black font-semibold sm:text-lg leading-9">Publié</p>
                    <label for="published" class="space-x-1">
                        <input id="published" name="published" type="checkbox" v-model="formAnimal.published">
                        <span v-if="formAnimal.published">Publié</span>
                        <span v-else>Pas publié</span>
                    </label>
                </div>
            </div>

            <div>
                <div class="space-y-1">
                    <label for="pictures" class="text-black font-semibold sm:text-lg leading-9">
                        Photo(s) de l'animal
                    </label>
                    <input
                        id="pictures"
                        name="pictures"
                        type="file"
                        accept="image/png,image/jpg,image/jpeg,image/webp"
                        multiple
                        @change="handlePictures"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2"
                    >
                    <p v-if="formAnimal.errors.pictures" class="text-red-600 text-sm mt-1">
                        {{ formAnimal.errors.pictures }}
                    </p>
                </div>

                <div v-if="previewPictures.length > 0" class="grid grid-cols-3 gap-4 mt-4">
                    <div v-for="(preview, index) in previewPictures" :key="index" class="relative">
                        <img :src="preview" :alt="`Preview ${index + 1}`"
                             class="w-full h-32 object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <TabbableTextarea
                v-model="formAnimal.description"
                nameId="Description"
                classTextarea="h-[200px]"
            >
                Description
            </TabbableTextarea>

        </div>
        <button type="submit"
                :disabled="formAnimal.processing"
                class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit place-self-center font-bold disabled:opacity-50 disabled:cursor-not-allowed">
            <SaveIcon class="w-6 h-6 svg-strokeblack"/>
            {{ formAnimal.processing ? 'Création...' : `Créer ${formAnimal.name || 'l\'animal'}` }}
        </button>
    </form>
</template>

<style scoped>
</style>
