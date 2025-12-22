<script setup>
import Pagination from "@/components/widgets/pagination/Pagination.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import ArchiveIcon from "@/components/widgets/svg/ArchiveIcon.vue";
import {router} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import VolunteerCreate from "@/pages/volunteers/VolunteerCreate.vue";
import {useUserHelpers} from "@/composables/useUserHelpers.ts";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import VolunteerShow from "@/pages/volunteers/VolunteerShow.vue";
import {useToasterStore} from "@/stores/useToasterStore.ts";

const props = defineProps({
    volunteers: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
    roles: Object,
})

let search = ref(props.filters.search || '');
let timeout = null;
const toast = useToasterStore();
const showCreateVolunteer = ref(false);
const showVolunteerDetail = ref(false);
const selectedVolunteer = ref(null);
const {getInitials, getUserImageUrl, getUserImageSrcset} = useUserHelpers();

watch(search, value => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(window.location.pathname, {
            search: value,
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
    }, 300);
});

const volunteerColumns = [
    {key: 'picture', label: 'Photo', sortable: false},
    {key: 'name', label: 'Nom'},
    {key: 'email', label: 'Email'},
    {key: 'phone', label: 'Téléphone', sortable: false},
    {key: 'role', label: 'Role'},
];

const volunteerActions = [
    {
        label: 'Ouvrir',
        icon: ExternalIcon,
        handler: (row) => openShowModal(row)
    },
    {
        label: 'Archiver',
        icon: ArchiveIcon,
        handler: (row) => openDeleteConfirm(row),
        class: 'text-red-600 hover:text-red-700'
    },
];

const handleSort = ({key, order}) => {
    router.get(window.location.pathname, {
        search: search.value,
        orderby: key,
        dir: order
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleVolunteerRowSelect = (selectedIds) => {
    console.log('Selected volunteers:', selectedIds);
}

const openShowModal = (volunteer) => {
    selectedVolunteer.value = volunteer;
    showVolunteerDetail.value = true;
}

const openDeleteConfirm = (volunteer) => {
    if (confirm(`Êtes-vous sûr de vouloir archiver ${volunteer.name} ?`)) {
        toast.success({text: 'Suppression effectuée'});
        router.delete(`/volunteers/${volunteer.id}`);
    }
}

</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <div class="flex flex-col gap-3 sm:gap-4 lg:gap-5">
            <div class="flex flex-col gap-3 sm:gap-4 lg:flex-row lg:justify-between lg:items-start">
                <div
                    class="flex gap-2 sm:gap-3 items-center border-2 border-lightblueslate/50 p-2 rounded-lg lg:order-2 lg:min-w-[200px]">
                    <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6"/>
                    <input
                        type="text"
                        placeholder="Rechercher un bénévole..."
                        v-model="search"
                        class="flex-1 outline-none text-sm sm:text-base"
                    >
                </div>

                <div class="flex flex-col lg:flex-row xs:flex-row gap-2 sm:gap-3 lg:order-3">
                    <button
                        @click="showCreateVolunteer=true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter un bénévole
                    </button>
                </div>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-3">
            <div
                v-for="volunteer in props.volunteers?.data"
                :key="volunteer.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                @click="openShowModal(volunteer)"
            >
                <div class="flex gap-3">
                    <img
                        v-if="volunteer.picture"
                        :src="getUserImageUrl(volunteer.picture, 'sm')"
                        :srcset="getUserImageSrcset(volunteer.picture)"
                        sizes="64px"
                        :alt="volunteer.name"
                        class="w-16 h-16 object-cover rounded-full"
                        loading="lazy"
                    >
                    <span
                        v-else
                        class="w-16 h-16 rounded-full flex items-center justify-center bg-sweetorange text-white font-semibold text-lg"
                    >
                        {{ getInitials(volunteer.name) }}
                    </span>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ volunteer.name }}</h3>
                        <p class="text-sm text-gray-600">{{ volunteer.email }}</p>
                        <p class="text-sm text-gray-600">{{ volunteer.phone }}</p>
                        <span class="inline-block mt-1 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                            {{ volunteer.role }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!props.volunteers?.data?.length" class="text-center py-8 text-gray-500">
            Aucun bénévole enregistré
        </div>

        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                v-if="props.volunteers?.data?.length"
                :columns="volunteerColumns"
                :data="props.volunteers.data"
                :actions="volunteerActions"
                :selectable="true"
                empty-message="Aucun bénévole enregistré"
                @row-select="handleVolunteerRowSelect"
                @sort="handleSort"
            >
                <template #cell-picture="{ row }">
                    <img
                        v-if="row.picture"
                        :src="getUserImageUrl(row.picture, 'sm')"
                        :srcset="getUserImageSrcset(row.picture)"
                        sizes="32px"
                        :alt="row.name"
                        class="w-8 h-8 object-cover rounded-full"
                        loading="lazy"
                    >
                    <span
                        v-else
                        class="w-8 h-8 rounded-full flex items-center justify-center bg-sweetorange text-white font-semibold text-xs"
                    >
                        {{ getInitials(row.name) }}
                    </span>
                </template>
            </GenericTable>
        </div>

        <Pagination :links="props.volunteers?.links"/>

        <RightModal v-model="showCreateVolunteer"
                    @update:modelValue="showCreateVolunteer = $event">
            <template #header>
                <h2 class="subsubtitle">Ajouter un bénévole</h2>
            </template>
            <VolunteerCreate :volunteers="volunteers" :roles="roles" @close="showCreateVolunteer = false"/>
        </RightModal>

        <CenterModal v-model="showVolunteerDetail"
                     @update:modelValue="showVolunteerDetail = $event">
            <VolunteerShow :volunteer="selectedVolunteer" :roles="roles" @close="showVolunteerDetail = false"/>
        </CenterModal>
    </section>
</template>
<style scoped>
th {
    background: rgba(73, 73, 73, 0.1);
}

table * {
    padding: 10px;
    border: 1px lightgrey solid;
}
</style>
