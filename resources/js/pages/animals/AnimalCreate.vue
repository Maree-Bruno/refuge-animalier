<script setup>
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {store} from "@/routes/animals/index.ts";
import {computed, ref} from "vue";
import Select from "@/components/widgets/form/Select.vue";
import TabbableTextarea from "@/components/widgets/form/TabbableTextarea.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    species: Object,
    coats: Object,
    races: Object,
    vaccines: Object,
});

const emit = defineEmits(['close']);
const races = props.races;
const vaccines = props.vaccines;


const page = usePage();
const animals = computed(() => page.props.animals);

let formAnimal = useForm({
    name: '',
    age: '',
    chip: '',
    sex: '',
    specie_id: '',
    race_id: '',
    coat_id: '',
    vaccine_id: [],
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

const filteredRaces = computed(() => {
    if (!formAnimal.specie_id || !races) {
        return races || []
    }
    return races.filter(race => race.specie_id === parseInt(formAnimal.specie_id));
});

const filteredVaccines = computed(() => {
    if (!formAnimal.specie_id || !vaccines) {
        return vaccines || []
    }
    return vaccines.filter(vaccine => vaccine.specie_id === parseInt(formAnimal.specie_id));
});
const removePreviewImage = (index) => {
    const dt = new DataTransfer();
    const files = Array.from(formAnimal.pictures);

    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });

    formAnimal.pictures = Array.from(dt.files);
    previewPictures.value = previewPictures.value.filter((_, i) => i !== index);
};

const submitAnimal = () => {
    formAnimal.post(store(), {
        forceFormData: true,
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'Animal ajouté avec succès'});
            formAnimal.reset();
            previewPictures.value = [];
            emit('close');
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
            toast.error({text: 'Une erreur est apparue lors de la création'});
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

                <Select nameId="race" v-model="formAnimal.race_id" label="Race de l'animal" v-if="formAnimal.specie_id">
                    <option value="">-- Choisissez la race --</option>
                    <option v-for="race in filteredRaces" :key="race.id" :value="race.id">
                        {{ race.name }}
                    </option>
                </Select>

                <Select nameId="coat" v-model="formAnimal.coat_id" label="Pelage de l'animal" v-if="formAnimal.specie_id">
                    <option value="">-- Choisissez le pelage --</option>
                    <option v-for="coat in coats" :key="coat.id" :value="coat.id">
                        {{ coat.name }}
                    </option>
                </Select>
            </div>

            <div class="space-y-1" v-if="formAnimal.specie_id">
                <p class="text-black font-semibold sm:text-lg leading-9">Vaccins</p>
                <div class="grid grid-cols-3 gap-5">
                    <label :for="`vaccine-${vaccine.id}`" class="space-x-1" v-for="vaccine in filteredVaccines" :key="vaccine.id">
                        <input
                            :id="`vaccine-${vaccine.id}`"
                            type="checkbox"
                            :value="vaccine.id"
                            v-model="formAnimal.vaccine_id">
                        <span>{{ vaccine.name }}</span>
                    </label>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Convient pour</p>
                <div class="flex gap-5">
                    <label for="dog" class="space-x-1">
                        <input id="dog" type="checkbox" value="dog" v-model="formAnimal.suitable">
                        Chien
                    </label>
                    <label for="cat" class="space-x-1">
                        <input id="cat" type="checkbox" value="cat" v-model="formAnimal.suitable">
                        Chat
                    </label>
                    <label for="kid" class="space-x-1">
                        <input id="kid" type="checkbox" value="kid" v-model="formAnimal.suitable">
                        Enfant
                    </label>
                    <label for="baby" class="space-x-1">
                        <input id="baby" type="checkbox" value="baby" v-model="formAnimal.suitable">
                        Bébé
                    </label>
                </div>
            </div>

            <div class="flex justify-between gap-5">
                <div class="w-full space-y-1">
                    <Select nameId="status-edit" v-model="formAnimal.status" label="Status de l'animal"
                            :modelValue="formAnimal.status">
                        <option value="">-- Choisissez un statut --</option>
                        <option value="Adopted">Adopté</option>
                        <option value="In progress">En cours</option>
                        <option value="Validated">Validé</option>
                    </Select>
                </div>
                <div class="space-y-1 w-full">
                    <p class="text-black font-semibold sm:text-lg leading-9">Sortir</p>
                    <label for="outside-edit" class="space-x-1">
                        <input id="outside-edit" name="outside" type="checkbox" v-model="formAnimal.outside">
                        <span v-if="formAnimal.outside">Autorisée</span>
                        <span v-else>Pas autorisée</span>
                    </label>
                </div>
                <div class="space-y-1 w-full" v-if="formAnimal.status !== 'Adopted'">
                    <p class="text-black font-semibold sm:text-lg leading-9">Publié</p>
                    <label for="published-edit" class="space-x-1"
                           :class="formAnimal.status === 'Validated' ?'':'cursor-not-allowed' ">
                        <input id="published-edit" name="published" type="checkbox" v-model="formAnimal.published"
                               :disabled="formAnimal.status !== 'Validated'"
                               :class="formAnimal.status === 'Validated' ?'':'cursor-not-allowed' "
                        >
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

                <div v-if="previewPictures.length > 0" class="mt-4">
                    <p class="text-black font-semibold text-sm mb-2">Images à ajouter :</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="(preview, index) in previewPictures" :key="index" class="relative group">
                            <img :src="preview" :alt="`Preview ${index + 1}`"
                                 class="w-full h-32 object-cover rounded-lg border-2 border-green-500">
                            <button
                                type="button"
                                @click="removePreviewImage(index)"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-all opacity-0 group-hover:opacity-100"
                                title="Supprimer cette image"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
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
        <button
            type="submit"
            :disabled="formAnimal.processing"
            class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit place-self-center font-bold disabled:opacity-50 disabled:cursor-not-allowed mt-6">
            <SaveIcon class="w-6 h-6 svg-strokeblack"/>
            {{ formAnimal.processing ? 'Création...' : `Créer ${formAnimal.name || 'l\'animal'}` }}
        </button>
    </form>
</template>
