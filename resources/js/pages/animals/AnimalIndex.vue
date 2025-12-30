<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import AnimalShow from "@/pages/animals/AnimalShow.vue";
import AnimalCreate from "@/pages/animals/AnimalCreate.vue";
import MarsIcon from "@/components/widgets/svg/MarsIcon.vue";
import VenusIcon from "@/components/widgets/svg/VenusIcon.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import {ref} from "vue";
import {useAnimalImage} from "@/composables/useAnimalImage";
import {useAnimalFilters} from "@/composables/useAnimalFilters";
import {useFormatDate} from "@/composables/useFormatDate";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import AnimalCard from "@/components/widgets/animals/AnimalCard.vue";
import AnimalStatusBadge from "@/components/widgets/animals/AnimalStatusBadge.vue";
import {router} from "@inertiajs/vue3";
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    animals: Object,
    filters: Object,
    showTitle: Boolean,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    allSuitableTypes: Object,
    can: Object
});

const {search, activeTab, switchTab, updateRoute} =
    useAnimalFilters(props.filters);
const toast = useToasterStore();
const {getUrl, getSrcset} = useAnimalImage();
const {formatDate} = useFormatDate();
const tabs = [
    {value: 'all', label: 'Tous les animaux'},
    {value: 'Adopted', label: 'Animaux adoptés'},
    {value: 'refuge', label: 'Animaux au refuge'}
];

const selectedRow = ref(null);
const isShowModalOpen = ref(false);
const showCreateAnimal = ref(false);
const showDeleteConfirm = ref(false);
const animalToDelete = ref(null);

const openShowModal = animal => {
    selectedRow.value = animal;
    isShowModalOpen.value = true;
};

const openDeleteConfirm = animal => {
    animalToDelete.value = animal;
    showDeleteConfirm.value = true;
};

const destroyAnimal = () => {
    if (!animalToDelete.value) return;

    router.delete(`/animals/${animalToDelete.value.id}`, {
        onSuccess: () => {
            toast.success({text: 'Animal supprimé avec succès'})
            showDeleteConfirm.value = false;
            animalToDelete.value = null;
        }
    });
};

const animalColumns = [
    {key: "photo", label: "Photo"},
    {key: "name", label: "Nom"},
    {key: "admission_date", label: "Admission"},
    {key: "chip", label: "Puce"},
    {key: "sex", label: "Sexe"},
    {key: "age", label: "Age"},
    {key: "status", label: "Statut"}
];

const animalActions = [
    {label: "Ouvrir", icon: ExternalIcon, handler: openShowModal},
    {
        label: "Supprimer",
        icon: ArchiveIcon,
        handler: openDeleteConfirm,
        class: "text-red-600"
    }
];

const handleSort = ({key, order}) => {
    updateRoute({orderby: key, dir: order});
};
</script>


<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <h3 class="subsubtitle" :class="showTitle ? 'not-sr-only': 'sr-only'">Animaux</h3>
        <div class="flex flex-col gap-3 sm:gap-4 lg:gap-5">
            <div class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:justify-between lg:items-start">
                <div
                    class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-1 overflow-x-auto pb-2 lg:pb-0">
                    <button
                        v-for="tab in tabs"
                        :key="tab.value"
                        @click="switchTab(tab.value)"
                        :class="[
                            'px-3 py-2 sm:px-4 rounded-lg transition-colors duration-200 text-xs sm:text-sm whitespace-nowrap flex-shrink-0',
                            activeTab === tab.value
                                ? 'bg-blueslate text-white font-semibold'
                                : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        {{ tab.label }}
                    </button>
                </div>

                <div
                    class="flex gap-2 sm:gap-3 items-center border-2 border-lightblueslate/50 p-2 rounded-lg lg:order-2 lg:min-w-[200px]">
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0"/>
                    <input
                        type="text"
                        placeholder="Rechercher un animal..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>

                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-3">
                    <button
                        @click="showCreateAnimal=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter un animal
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden space-y-3">
            <AnimalCard
                v-for="animal in animals?.data"
                :key="animal.id"
                :animal="animal"
                @click="openShowModal"
            />

            <p v-if="!animals?.data?.length" class="text-center text-gray-500 py-8">
                Aucun animal enregistré
            </p>
        </div>


        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                :columns="animalColumns"
                :data="animals.data"
                :actions="animalActions"
                @sort="handleSort"
            >
                <template #cell-photo="{ row }">
                    <img
                        :src="getUrl(row)"
                        :srcset="getSrcset(row)"
                        class="w-8 h-8 object-cover rounded-full"
                        alt="photo de {{row.name}}"
                        loading="lazy"
                    />
                </template>

                <template #cell-sex="{ row }">
                    <span v-if="row.sex === 'male'"> <MarsIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/> </span>
                    <span v-else> <VenusIcon class="svg-strokeblue w-6 h-6" stroke-width="2"/> </span>
                </template>
                <template #cell-admission_date="{ row }">
                    <span>{{ formatDate(row.admission_date) }}</span>
                </template>

                <template #cell-status="{ row }">
                    <AnimalStatusBadge :status="row.status"/>
                </template>
            </GenericTable>

        </div>

        <Pagination :links="props.animals?.links"/>
    </section>

    <KeepAlive>
        <RightModal v-model="showCreateAnimal" route-key="create" route-value="create-animal">
            <template #header>
                <h2 class="subsubtitle">Ajouter un animal</h2>
            </template>
            <AnimalCreate :species="species"
                          :races="races"
                          :coats="coats"
                          :vaccines="vaccines"
                          :can="can"
                          @close="showCreateAnimal = false"
                          :allSuitableTypes="allSuitableTypes"/>
        </RightModal>
    </KeepAlive>

    <CenterModal v-model="isShowModalOpen"
                 v-if="selectedRow"
                 route-key="animal"
                 :route-value="selectedRow?.id"
    >
        <AnimalShow :animal="selectedRow"
                    :species="species"
                    :races="races"
                    :coats="coats"
                    :vaccines="vaccines"
                    :can="can"
                    @close="showCreateAnimal = false"
                    :allSuitableTypes="allSuitableTypes"/>
    </CenterModal>

    <CenterModal v-model="showDeleteConfirm">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <TrashIcon class="w-6 h-6 text-red-600"/>
            </div>

            <h3 class="text-lg font-semibold text-center mb-2">
                Confirmer la suppression
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir supprimer
                <span class="font-semibold">{{ animalToDelete?.name }}</span> ?
                Cette action est irréversible et supprimera également toutes les photos associées.
            </p>

            <div class="flex gap-3 justify-end">
                <button
                    @click="showDeleteConfirm = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    @click="destroyAnimal"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
</template>
