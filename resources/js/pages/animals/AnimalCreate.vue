<script setup>

import InputLabel from "@/components/widgets/form/InputLabel.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {store} from "@/routes/animals/index.ts";
import {computed, ref} from "vue";
import Select from "@/components/widgets/form/Select.vue";
import ButtonSvg from "@/components/widgets/button/ButtonSvg.vue";
import TabbableTextarea from "@/components/widgets/form/TabbableTextarea.vue";

const props = defineProps({
    species: Object,
    coats: Object,
    races: Object,
});
const page = usePage();
const animals = computed(() => page.props.animals);
let formAnimal = useForm({
    name: '',
    race_id: [],
    suitable: [],
    sex: '',
    chip: '',
    age: '',
    specie_id: '',
    description: '',
    status: '',
    pictures: '',
    outside: '',
    coat_id: []
});
const showCreateAnimal = ref(false);
const submitAnimal = () => {
    formAnimal.post(store(), {
        onSuccess: () => {
            showCreateAnimal.value = false;
            formAnimal.reset();
        },
        onError: (errors) => {
            console.error('Erreurs de validation:', errors);
        }
    });
}


</script>

<template>
    <form @submit.prevent="submitAnimal" enctype="multipart/form-data">
        <div class="space-y-5 flex flex-col justify-center ">
            <InputLabel
                nameId="name"
                type="text"
                placeholder="Nom de l'animal"
                :message="formAnimal.errors.name"
                v-model="formAnimal.name" :required="true"
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
                    v-model="formAnimal.age" :required="true"
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
                v-model="formAnimal.chip" :required="true"
            >
                Puce de l'animal
            </InputLabel>
            <div class="flex gap-4">
                <Select nameId="specie" v-model="formAnimal.specie_id" label="Espèce de l'animal">
                    <option v-for="specie in species" :value="specie.name">{{ specie.name }}</option>
                </Select>
                <Select nameId="race" v-model="formAnimal.race_id" label="Race(s) de l'animal">
                    <option v-for="specie in species" :value="specie.name">{{ specie.name }}</option>
                </Select>
                <Select nameId="coat" v-model="formAnimal.coat_id" label="Pelage de l'animal">
                    <option v-for="coat in coats" :value="coat.name">{{ coat.name }}</option>
                </Select>
            </div>
            <div class="flex justify-between">
                <div class="space-y-1">
                    <p class="text-black font-semibold sm:text-lg leading-9">Nature</p>
                    <div class="grid grid-cols-3 gap-5">
                        <label for="nature" class="space-x-1">
                            <input id="nature" name="nature" type="checkbox">
                            Amical
                        </label>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-black font-semibold sm:text-lg leading-9">Vaccins</p>
                    <div class="grid grid-cols-3 gap-5">
                        <label for="vaccins" class="space-x-1">
                            <input id="vaccins" name="vaccins" type="checkbox">
                            Rubbéole

                        </label>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Convient pour</p>
                <div class="flex gap-5">
                    <label for="dog" class="space-x-1">
                        <input id="dog" name="dog" type="checkbox" v-model="formAnimal.suitable">
                        Chien
                    </label>
                    <label for="cat" class="space-x-1">
                        <input id="cat" name="cat" type="checkbox" v-model="formAnimal.suitable">
                        Chat
                    </label>
                    <label for="kid" class="space-x-1">
                        <input id="kid" name="kid" type="checkbox" v-model="formAnimal.suitable">
                        Enfant
                    </label>
                    <label for="baby" class="space-x-1">
                        <input id="baby" name="baby" type="checkbox" v-model="formAnimal.suitable">
                        Bébé
                    </label>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-black font-semibold sm:text-lg leading-9">Sortir</p>
                <label for="outside" class="space-x-1">
                    <input id="outside" name="outside" type="checkbox" v-model="formAnimal.outside">
                    <span v-if="formAnimal.outside===true">Autorisée</span>
                    <span v-else>Pas autorisée</span>
                </label>
            </div>
            <div>
                <InputLabel
                    nameId="chip"
                    type="file"
                    placeholder="Sélectionner un ou plusieurs fichier"
                    :message="formAnimal.errors.pictures"
                    v-model="formAnimal.pictures"
                >
                    Photo(s) de l'animal
                </InputLabel>
                <div>
                    <img src="#" alt="">
                </div>
            </div>
            <TabbableTextarea
                v-model="formAnimal.description"
                nameId="Description"
                classTextarea="h-[200px]"
            >
                Description
            </TabbableTextarea>

            <button type="submit" class="button-yellow flex p-3 items-center justify-center gap-2.5 rounded-md
                    button-animation w-fit place-self-center">
                Créer {{ formAnimal.name }}
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>
