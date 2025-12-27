<script setup>
import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {useForm, router} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import Select from "@/components/widgets/form/Select.vue";
import TabbableTextarea from "@/components/widgets/form/TabbableTextarea.vue";
import SaveIcon from "@/components/widgets/svg/SaveIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";
import InputError from "@/components/InputError.vue";

const props = defineProps({
    animal: Object,
    species: Object,
    coats: Object,
    races: Object,
    vaccines: Object,
    allSuitableTypes: Object,
});
console.log(props.species)

const toast = useToasterStore();
let formAnimal = useForm({
    name: props.animal?.name || '',
    age: props.animal?.age || '',
    chip: props.animal?.chip || '',
    sex: props.animal?.sex || '',
    specie_id: props.animal?.specie?.id
        ? Number(props.animal.specie.id)
        : '',

    race_id: props.animal?.race_id || '',
    coat_id: props.animal?.coat_id || '',
    suitable_type_ids: props.animal?.suitable_types?.map(st => st.id) || [],
    vaccine_id: props.animal?.vaccines?.map(v => v.id) || [],
    status: props.animal?.status || '',
    outside: props.animal?.outside || false,
    published: props.animal?.published || false,
    pictures: [],
    description: props.animal?.description || '',
    _method: 'PATCH'
});

const filteredRaces = computed(() => {
    if (!formAnimal.specie_id || !props.races) {
        return props.races || [];
    }
    return props.races.filter(race => race.specie_id === parseInt(formAnimal.specie_id));
});

const filteredVaccines = computed(() => {
    if (!formAnimal.specie_id || !props.vaccines) {
        return props.vaccines || []
    }
    return props.vaccines.filter(vaccine => vaccine.specie_id === parseInt(formAnimal.specie_id));
});

const selectedVaccines = computed(() => {
    if (!props.vaccines) return [];
    return props.vaccines.filter(vaccine => formAnimal.vaccine_id.includes(vaccine.id));
});

let previewPictures = ref([]);
let existingPictures = ref(props.animal?.pictures || []);
let deletingImage = ref(null);
watch(() => props.animal, (newAnimal) => {
    if (newAnimal) {
        formAnimal.name = newAnimal.name || '';
        formAnimal.age = newAnimal.age || '';
        formAnimal.chip = newAnimal.chip || '';
        formAnimal.sex = newAnimal.sex || '';
        formAnimal.specie_id = newAnimal.specie?.id
            ? Number(newAnimal.specie.id)
            : '';
        formAnimal.race_id = newAnimal.race_id || '';
        formAnimal.coat_id = newAnimal.coat_id || '';
        formAnimal.suitable_type_ids = newAnimal.suitable_types
            ? newAnimal.suitable_types.map(st => st.id)
            : [];
        formAnimal.vaccine_id = newAnimal.vaccines
            ? newAnimal.vaccines.map(vaccine => vaccine.id)
            : [];
        formAnimal.status = newAnimal.status || '';
        formAnimal.outside = newAnimal.outside || false;
        formAnimal.published = newAnimal.published || false;
        formAnimal.description = newAnimal.description || '';
        existingPictures.value = newAnimal.pictures || [];
        previewPictures.value = [];
        formAnimal.pictures = [];
    }
}, {immediate: true, deep: true});

const handlePictures = (event) => {
    const files = Array.from(event.target.files);
    formAnimal.pictures = files;
    previewPictures.value = files.map(file => URL.createObjectURL(file));
}

const getImageUrl = (filename, size = 'sm') => {
    const sizeMap = {
        'sm': '300x300',
        'md': '600x600',
    };
    return `/images/animals/variants/${sizeMap[size]}/${filename}`;
};

const deleteImage = (filename) => {
    deletingImage.value = filename;

    router.delete(`/animals/${props.animal.id}/images`, {
        data: {filename},
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Image supprimée avec succès'});
            existingPictures.value = existingPictures.value.filter(pic => pic !== filename);
            deletingImage.value = null;
        },
        onError: (errors) => {
            toast.error({text: 'Erreur lors de la suppression de l\'image'});
            console.error(errors);
            deletingImage.value = null;
        }
    });
};

const emit = defineEmits(['update', 'close']);
const showEditAnimal = ref(false);

const submitAnimal = () => {
    const updateUrl = `/animals/${props.animal.id}`;
    formAnimal._method = 'PATCH';
    formAnimal.post(updateUrl, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.success({text: 'Animal mis à jour avec succès'});
            formAnimal.preserveState = false;
            previewPictures.value = [];
            showEditAnimal.value = false;
            emit('update');
            emit('close');
        },
        onError: (errors) => {
            toast.error({text: 'Une erreur est apparue lors de la mise à jour'});
            console.error(errors);
        }
    });
}
</script>

<template>
    <form @submit.prevent="submitAnimal" enctype="multipart/form-data" class="p-6">
        <div class="space-y-6 flex flex-col justify-center">
            <InputLabel
                nameId="name-edit"
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
                    nameId="age-edit"
                    type="number"
                    placeholder="2"
                    :message="formAnimal.errors.age"
                    v-model="formAnimal.age"
                    :required="true"
                >
                    Âge de l'animal
                </InputLabel>

                <div class="flex flex-col gap-2 w-full">
                    <Select nameId="sex-edit" v-model="formAnimal.sex" label="Sexe de l'animal">
                        <option value="" selected>-- Choisissez le sexe de l'animal --</option>
                        <option value="male">Mâle</option>
                        <option value="female">Femelle</option>
                    </Select>
                    <InputError :message="formAnimal.errors.sex"/>
                </div>
            </div>

            <InputLabel
                nameId="chip-edit"
                type="text"
                placeholder="250 26 12 12345678"
                :message="formAnimal.errors.chip"
                v-model="formAnimal.chip"
            >
                Puce de l'animal
            </InputLabel>

            <div class="flex gap-4">
                <div class="flex flex-col gap-2 w-full">
                    <Select nameId="specie-edit" v-model="formAnimal.specie_id" label="Espèce de l'animal">
                        <option value="">-- Choisissez une espèce --</option>
                        <option v-for="specie in species" :key="specie.id" :value="specie.id">
                            {{ specie.name }}
                        </option>
                    </Select>
                    <InputError :message="formAnimal.errors.specie_id"/>
                </div>
                <div class="flex flex-col gap-2 w-full" v-if="formAnimal.specie_id">
                    <Select nameId="race-edit"  v-model="formAnimal.race_id" label="Race de l'animal">
                        <option value="">-- Choisissez la race --</option>
                        <option v-for="race in filteredRaces" :key="race.id" :value="race.id">
                            {{ race.name }}
                        </option>
                    </Select>
                    <InputError :message="formAnimal.errors.race_id"/>
                </div>

                <div class="flex flex-col gap-2 w-full" v-if="formAnimal.specie_id">
                    <Select nameId="coat-edit" v-model="formAnimal.coat_id" label="Pelage de l'animal">
                        <option value="">-- Choisissez le pelage --</option>
                        <option v-for="coat in coats" :key="coat.id" :value="coat.id">
                            {{ coat.name }}
                        </option>
                    </Select>
                    <InputError :message="formAnimal.errors.coat_id"/>
                </div>
            </div>

            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Vaccins</p>
                <div class="grid grid-cols-3 gap-5">
                    <label :for="`vaccine-${vaccine.id}`" class="space-x-1"
                           v-for="vaccine in filteredVaccines"
                           :key="vaccine.id">
                        <input
                            :id="`vaccine-${vaccine.id}`"
                            type="checkbox"
                            :value="vaccine.id"
                            v-model="formAnimal.vaccine_id">
                        <span>{{ vaccine.name }}</span>
                    </label>
                    <InputError :message="formAnimal.errors.vaccine_id"/>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Convient pour</p>
                <div class="flex gap-5">
                    <label
                        v-for="suitableType in allSuitableTypes"
                        :key="suitableType.id"
                        :for="`suitable-edit-${suitableType.id}`"
                        class="space-x-1"
                    >
                        <input
                            :id="`suitable-edit-${suitableType.id}`"
                            type="checkbox"
                            :value="suitableType.id"
                            v-model="formAnimal.suitable_type_ids"
                        >
                        {{ suitableType.label }}
                    </label>
                </div>
                <InputError :message="formAnimal.errors.suitable_type_ids"/>
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
                    <InputError :message="formAnimal.errors.status"/>
                </div>
                <div class="space-y-1 w-full">
                    <p class="text-black font-semibold sm:text-lg leading-9">Sortir</p>
                    <label for="outside-edit" class="space-x-1">
                        <input id="outside-edit" name="outside" type="checkbox" v-model="formAnimal.outside">
                        <span v-if="formAnimal.outside">Autorisée</span>
                        <span v-else>Pas autorisée</span>
                    </label>
                    <InputError :message="formAnimal.errors.outside"/>
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
                    <InputError :message="formAnimal.errors.published"/>
                </div>
            </div>

            <div>
                <div v-if="existingPictures.length > 0" class="mb-4">
                    <p class="text-black font-semibold sm:text-lg leading-9 mb-2">Images actuelles</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="(picture, index) in existingPictures" :key="index" class="relative group">
                            <img
                                :src="getImageUrl(picture, 'sm')"
                                :alt="`Image ${index + 1}`"
                                class="w-full h-32 object-cover rounded-lg border-2 border-honeyyellow"
                                :class="{ 'opacity-50': deletingImage === picture }"
                            >
                            <button
                                type="button"
                                @click="deleteImage(picture)"
                                :disabled="deletingImage === picture"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-all opacity-0 group-hover:opacity-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                title="Supprimer cette image"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="pictures-edit" class="text-black font-semibold sm:text-lg leading-9">
                        {{ existingPictures.length > 0 ? 'Ajouter de nouvelles photo(s)' : 'Photo(s) de l\'animal' }}
                    </label>
                    <input
                        id="pictures-edit"
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
                    <p class="text-black font-semibold text-sm mb-2">Nouvelles images à ajouter :</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="(preview, index) in previewPictures" :key="index" class="relative">
                            <img :src="preview" :alt="`Preview ${index + 1}`"
                                 class="w-full h-32 object-cover rounded-lg border-2 border-green-500">
                        </div>
                    </div>
                </div>
            </div>

            <TabbableTextarea
                v-model="formAnimal.description"
                nameId="description-edit"
                classTextarea="h-[200px]"
                :message="formAnimal.errors.description"
            >
                Description
            </TabbableTextarea>
        </div>

        <div class="mt-6 flex justify-center">
            <button type="submit"
                    :disabled="formAnimal.processing"
                    @click="close()"
                    class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md button-animation w-fit font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                <SaveIcon class="w-6 h-6 svg-strokeblack"/>
                {{ formAnimal.processing ? 'Mise à jour...' : `Mettre à jour ${formAnimal.name || 'l\'animal'}` }}
            </button>
        </div>
    </form>
</template>

<style scoped>
</style>
