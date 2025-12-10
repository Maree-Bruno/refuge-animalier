<script setup>
import {Link, router} from "@inertiajs/vue3";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import {ref, watch} from "vue";
import MarsIcon from "@/components/widgets/svg/MarsIcon.vue";
import VenusIcon from "@/components/widgets/svg/VenusIcon.vue";

const props = defineProps({
    animals: {
        type: Object
    },
    filters: {
        type: Object,
        default: () => ({})
    }
})

let search = ref(props.filters.search || '');

let timeout = null;
watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/dashboard', {search: value}, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});
</script>

<template>
    <section class="flex flex-col gap-5">
        <h3 class="subsubtitle">Statistiques</h3>
        <div class="gap-10 self-stretch flex justify-start items-start flex-wrap">
            <div
                class="flex-1 self-stretch rounded-2xl border-4 border-honeyyellow bg-lighthoneyyellow p-4 max-w-[300px] gap-10 flex flex-col justify-between">
                <p class="font-bold text-2xl">Animaux adoptés</p>
                <p class="text-4xl font-bold place-self-center">43</p>
            </div>
            <div
                class="flex-1 self-stretch rounded-2xl border-4 border-greenmint bg-lightgreenmint p-4 max-w-[300px] gap-10 flex flex-col justify-between">
                <p class="font-bold text-2xl">Animaux Recueillis</p>
                <p class="text-4xl font-bold place-self-center">22</p>
            </div>
            <div
                class="flex-1 self-stretch rounded-2xl border-4 border-sweetorange bg-lightsweetorange p-4 max-w-[300px] gap-10 flex flex-col justify-between">
                <p class="font-bold text-2xl">Animaux en cours d'adoption</p>
                <p class="text-4xl font-bold place-self-center">5</p>
            </div>
            <div
                class="flex-1 self-stretch rounded-2xl border-4 border-gray-500 bg-lightgray p-4 max-w-[300px] gap-10 flex flex-col justify-between">
                <p class="font-bold text-2xl">Animaux dans le refuge</p>
                <p class="text-4xl font-bold place-self-center">64</p>
            </div>

        </div>
    </section>
    <section class="flex flex-col gap-5">
        <div class="flex justify-between">
            <h3 class="subsubtitle">Animaux</h3>

            <div class="flex gap-4">
                <p class="button-yellow button-animation rounded-md p-2 font-semibold">Ajouter un animal</p>
                <p class="button-green button-animation rounded-md p-2 font-semibold">Ajouter une note</p>
            </div>
        </div>
        <div class="flex  justify-between items-center">
            <div class="flex gap-4">
                <p>Tous les animaux</p>
                <p>Animaux adoptés</p>
                <p>Animaux au refuge</p>
            </div>
            <div
                class="flex gap-4 justify-between items-center md:border-2 md:border-lightblueslate/50 p-2 rounded-lg">
                <LoupeIcon class="svg-strokeblue w-6 h-6 place-self-center min-w-4 min-h-4"/>
                <input type="text" placeholder="Search... " v-model="search" class="flex-1 outline-none">
            </div>
        </div>
        <table class="">
            <thead class="bg-blueslate text-white border border-transparent">
            <tr class="font-semibold">
                <td class="p-2"><input type="checkbox" name="all" id="all"> <label for="all"></label></td>
                <td class="p-2"><span>Photo</span></td>
                <td class="p-2"><span>Nom</span></td>
                <td class="p-2"><span>Date d'admission</span></td>
                <td class="p-2"><span>Puce</span></td>
                <td class="p-2"><span>Sexe</span></td>
                <td class="p-2"><span>Age</span></td>
                <td class="p-2"><span>Status</span></td>
                <td class="p-2"><span>Action</span></td>
            </tr>
            </thead>
            <tbody v-if="animals">
            <tr v-for="animal in animals.data" :key="animal.id" class="font-monospace">
                <td class="p-2"><input type="checkbox" :name="animal.name" :id="animal.name">
                    <label :for="animal.name"></label></td>
                <td
                    class="p-2"><img src="/images/billy.webp" :alt="animal.name"
                                     class="w-8 h-8 object-cover rounded-full"></td>
                <td class="p-2"><span>{{ animal.name }}</span></td>
                <td class="p-2"><span>{{ animal.admission_date }}</span></td>
                <td class="p-2"><span class="">{{ animal.chip }}</span></td>
                <td class="p-2">
                    <span v-if="animal.sex === 'male'">
                        <MarsIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/>
                    </span>
                    <span v-else>
                        <VenusIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/>
                    </span>
                </td>
                <td class="p-2"><span>{{ animal.age }}</span></td>
                <td class="p-2 font-sans"><span>{{ animal.status }}</span></td>
                <td class="p-2">
                    <div>
                        <span>Modifier</span>
                        <span>Archiver</span>
                    </div>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>Aucun animaux enregistré</tr>
            </tbody>
        </table>
        <div class="mt-6 flex gap-2">
            <template v-for="(link, index) in animals.links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border rounded"
                    preserve-scroll
                    :class="{ 'bg-blueslate text-white': link.active }"
                />
                <span
                    v-else
                    v-html="link.label"
                    class="px-3 py-1 border rounded opacity-50 cursor-not-allowed"
                />
            </template>
        </div>
    </section>
    <section class="flex flex-col gap-5">
        <h3 class="subsubtitle">Demande d'adoption</h3>
        <table class="">
            <thead class="bg-blueslate text-white border border-transparent">
            <tr class="font-semibold">
                <td class="p-2"><input type="checkbox" name="all" id="all"> <label for="all"></label></td>
                <td class="p-2"><span>Nom</span></td>
                <td class="p-2"><span>Email</span></td>
                <td class="p-2"><span>Téléphone</span></td>
                <td class="p-2"><span>Animal</span></td>
                <td class="p-2"><span>Date</span></td>
                <td class="p-2"><span>Actions</span></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="p-2"><input type="checkbox" name="billy" id="billy"> <label for="billy"></label></td>
                <td class="p-2"><span>Billy Doe</span></td>
                <td class="p-2"><span>email@example.com</span></td>
                <td class="p-2"><span>0499999999</span></td>
                <td class="p-2"><span>Billy</span></td>
                <td class="p-2"><span>23/12/2026</span></td>
                <td class="p-2">
                    <div>
                        <span>Modifier</span>
                        <span>Archiver</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </section>
    <section class="flex flex-col gap-5">
        <h3 class="subsubtitle">Emails</h3>
        <table class="">
            <thead class="bg-blueslate text-white border border-transparent font-semibold">
            <tr class="">
                <td class="p-2"><input type="checkbox" name="all" id="all"> <label for="all"></label></td>
                <td class="p-2"><span>Nom</span></td>
                <td class="p-2"><span>Email</span></td>
                <td class="p-2"><span>Téléphone</span></td>
                <td class="p-2"><span>Sujet</span></td>
                <td class="p-2"><span>Date</span></td>
                <td class="p-2"><span>Actions</span></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="p-2"><input type="checkbox" name="billy" id="billy"> <label for="billy"></label></td>
                <td class="p-2"><span>Billy Doe</span></td>
                <td class="p-2"><span>email@example.com</span></td>
                <td class="p-2"><span>0499999999</span></td>
                <td class="p-2"><span>Billy</span></td>
                <td class="p-2"><span>23/12/2026</span></td>
                <td class="p-2">
                    <div>
                        <span>Modifier</span>
                        <span>Archiver</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </section>
</template>
