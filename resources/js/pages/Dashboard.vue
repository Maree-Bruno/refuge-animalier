<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import DogIcon from "@/components/widgets/svg/DogIcon.vue";
</script>

<template>
    <AppLayout title="Dashboard">
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
                    <p>Ajouter un animal</p>
                    <p>Ajouter une note</p>
                </div>
            </div>
            <div class="flex gap-4">
                <p>Tous les animaux</p>
                <p>Animaux adoptés</p>
                <p>Animaux au refuge</p>
            </div>
            <table class="">
                <thead class="bg-blueslate text-white border border-transparent">
                <tr class="">
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
                <tbody>
                <tr>
                    <td class="p-2"><input type="checkbox" name="billy" id="billy"> <label for="billy"></label></td>
                    <td class="p-2"><img src="/public/images/billy.webp" alt="Billy"></td>
                    <td class="p-2"><span>Billy</span></td>
                    <td class="p-2"><span>12/12/2026</span></td>
                    <td class="p-2"><span>250 26 12 12345678</span></td>
                    <td class="p-2"><span>Male</span></td>
                    <td class="p-2"><span>3 ans</span></td>
                    <td class="p-2"><span>Validé</span></td>
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
            <h3 class="subsubtitle">Demande d'adoption</h3>
            <table class="">
                <thead class="bg-blueslate text-white border border-transparent">
                <tr class="">
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
                <thead class="bg-blueslate text-white border border-transparent">
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

    </AppLayout>
</template>
