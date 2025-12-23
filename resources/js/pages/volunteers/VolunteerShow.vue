<script setup>
import {computed, ref} from "vue";
import { edit as editAvailability } from '@/routes/availability';
import {useUserHelpers} from "@/composables/useUserHelpers";
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import VolunteerEdit from "@/pages/volunteers/VolunteerEdit.vue";
import {Link} from "@inertiajs/vue3";
import AvailabilityIcon from "@/components/widgets/table/AvailabilityIcon.vue";

const props = defineProps({
    volunteer: Object,
    roles: Object,
    availability: Object,
})

console.log(props.availability);
const selectedVolunteer = computed(() => props.volunteer);
const {getInitials, getUserImageUrl, getUserImageSrcset} = useUserHelpers();
const showEdit = ref(false);

const availabilityColumns = [
    {key: 'period', label: '', sortable: false, class: 'px-4 py-3 text-left text-sm font-semibold border-b-2 border-slate-300 bg-slate-700 text-white'},
    {key: 'monday', label: 'Lundi', sortable: false},
    {key: 'tuesday', label: 'Mardi', sortable: false},
    {key: 'wednesday', label: 'Mercredi', sortable: false},
    {key: 'thursday', label: 'Jeudi', sortable: false},
    {key: 'friday', label: 'Vendredi', sortable: false},
    {key: 'saturday', label: 'Samedi', sortable: false},
    {key: 'sunday', label: 'Dimanche', sortable: false},
];

const availabilityData = computed(() => props.availability || []);

</script>

<template>
    <section v-if="selectedVolunteer" class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-scroll">

            <div class="flex justify-between flex-row gap-3 sm:gap-4 mb-4">
                <h2 class="subtitle text-xl sm:text-2xl">{{ selectedVolunteer.name }}</h2>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <button
                        @click="showEdit = true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Modifier
                    </button>
                    <button
                        class="button-orange button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Supprimer
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-5">
                        <img
                            v-if="selectedVolunteer.picture"
                            :src="getUserImageUrl(selectedVolunteer.picture, 'md')"
                            :alt="selectedVolunteer.name"
                            class="w-full sm:w-auto sm:h-[250px] lg:h-[300px] aspect-square object-cover rounded-2xl"
                        >
                        <div
                            v-else
                            class="w-full sm:w-[250px] lg:w-[300px] sm:h-[250px] lg:h-[300px] aspect-square rounded-2xl flex items-center justify-center bg-sweetorange text-white font-bold text-6xl"
                        >
                            {{ getInitials(selectedVolunteer.name) }}
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                <div class="flex flex-col">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Rôle</span>
                                    <span class="text-xs sm:text-sm">{{ selectedVolunteer.role }}</span>
                                </div>

                                <div class="flex flex-col col-span-2">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Email</span>
                                    <span class="text-xs sm:text-sm">{{ selectedVolunteer.email }}</span>
                                </div>

                                <div class="flex flex-col col-span-2">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Téléphone</span>
                                    <span class="text-xs sm:text-sm">{{
                                            selectedVolunteer.phone || 'Non renseigné'
                                        }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:gap-4">

                                <div class="flex flex-col">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Rue</span>
                                    <span class="text-xs sm:text-sm">{{
                                            selectedVolunteer.address || 'Non renseignée'
                                        }}</span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Numéro</span>
                                    <span class="text-xs sm:text-sm">{{ selectedVolunteer.number || '-' }}</span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Code Postal</span>
                                    <span class="text-xs sm:text-sm">{{ selectedVolunteer.cp || '-' }}</span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="font-quicksand font-bold text-sm sm:text-base">Ville</span>
                                    <span class="text-xs sm:text-sm">{{ selectedVolunteer.city || '-' }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <h3 class="font-quicksand font-bold text-lg sm:text-xl">Disponibilités</h3>

                    <div v-if="!availabilityData || availabilityData.length === 0" class="text-center py-4 text-gray-500">
                        Aucune disponibilité renseignée
                    </div>

                    <GenericTable
                        v-else
                        :columns="availabilityColumns"
                        :data="availabilityData"
                        :selectable="false"
                        :actions="[]"
                        empty-message="Aucune disponibilité"
                    >
                        <template #cell-period="{ value }">
                            <span class="font-semibold">{{ value }}</span>
                        </template>

                        <template #cell-monday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-tuesday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-wednesday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-thursday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-friday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-saturday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>

                        <template #cell-sunday="{ value }">
                            <AvailabilityIcon :value="value" />
                        </template>
                    </GenericTable>
                </div>
            </div>
        </div>
    </section>
    <RightModal v-model="showEdit" @update:modelValue="showEdit = $event">
        <template #header>
            <h2 class="subsubtitle">Modifier {{ volunteer?.name }}</h2>
        </template>
        <VolunteerEdit
            :volunteer="volunteer"
            :roles="roles"
            @close="showEdit = false"
        />
    </RightModal>
</template>

<style scoped>
.overflow-x-auto::-webkit-scrollbar,
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track,
.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb,
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover,
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
