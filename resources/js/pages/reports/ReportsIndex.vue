<script setup>
import GenericTable from "@/components/widgets/table/GenericTable.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import {ref, computed} from "vue";
import ExternalIcon from "@/components/widgets/svg/ExternalIcon.vue";
import LoupeIcon from "@/components/widgets/svg/LoupeIcon.vue";
import ExportIcon from "@/components/widgets/svg/ExportIcon.vue";
import ReportsShow from "@/pages/reports/ReportsShow.vue";

const props = defineProps({
    reports: Array,
    species: Object,
    races: Object,
    coats: Object,
    vaccines: Object,
    allSuitableTypes: Object,
    can: Object,
    filters: Object
});

const isShowModalOpen = ref(false);
const selectedReport = ref(null);
const search = ref(props.filters?.search || '');

const filteredReports = computed(() => {
    if (!search.value) return props.reports;

    return props.reports.filter(report =>
        report.label.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openShowModal = (report) => {
    selectedReport.value = report;
    isShowModalOpen.value = true;
};

const exportPdf = (report) => {
    window.location.href = `/reports/${report.month}/${report.year}/export-pdf`;
};

const reportColumns = [
    {key: "label", label: "Période"},
    {key: "refuged_animals", label: "Au refuge"},
    {key: "adopted_animals", label: "Adoptés"},
    {key: "accepted_requests", label: "Demandes acceptées"},
    {key: "in_progress_requests", label: "En cours"},
    {key: "total_animals", label: "Total"}
];

const reportActions = [
    {label: "Voir le rapport", icon: ExternalIcon, handler: openShowModal},
    {
        label: "Exporter PDF",
        icon: ExportIcon,
        handler: exportPdf,
    }
];
</script>

<template>
    <section class="flex flex-col gap-4 sm:gap-5 p-4 sm:p-0">
        <div class="flex flex-col gap-3 sm:gap-4">
            <div
                class="flex gap-2 sm:gap-3 items-center border-2 border-lightblueslate/50 p-2 rounded-lg max-w-md">
                <LoupeIcon class="svg-strokeblue w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0"/>
                <input
                    type="text"
                    placeholder="Rechercher un mois..."
                    v-model="search"
                    class="flex-1 outline-none text-sm sm:text-base"
                >
            </div>
        </div>

        <div class="md:hidden space-y-3">
            <div
                v-for="report in filteredReports"
                :key="report.id"
                class="bg-white rounded-lg shadow p-4 space-y-3 cursor-pointer hover:shadow-md transition-shadow"
                @click="openShowModal(report)"
            >
                <div class="flex justify-between items-start">
                    <h4 class="font-semibold text-lg">{{ report.label }}</h4>
                    <button
                        @click.stop="exportPdf(report)"
                        class="text-blue-600 hover:text-blue-800 text-xl"
                    >
                        <ExportIcon class="w-6 h-6 svg-strokeblack"/>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-gray-600">Au refuge:</span>
                        <span class="font-semibold ml-1">{{ report.refuged_animals }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Adoptés:</span>
                        <span class="font-semibold ml-1">{{ report.adopted_animals }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Demandes acceptées:</span>
                        <span class="font-semibold ml-1">{{ report.accepted_requests }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">En cours:</span>
                        <span class="font-semibold ml-1">{{ report.in_progress_requests }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-bold ml-1 text-lg">{{ report.total_animals }}</span>
                </div>
            </div>

            <p v-if="!filteredReports.length" class="text-center text-gray-500 py-8">
                Aucun rapport trouvé
            </p>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <GenericTable
                :columns="reportColumns"
                :data="filteredReports"
                :actions="reportActions"
            >
                <template #cell-label="{ row }">
                    <span class="">{{ row.label }}</span>
                </template>

                <template #cell-refuged_animals="{ row }">
                    <span class="text-sm">
                        {{ row.refuged_animals }}
                    </span>
                </template>

                <template #cell-adopted_animals="{ row }">
                    <span class="text-sm">
                        {{ row.adopted_animals }}
                    </span>
                </template>

                <template #cell-accepted_requests="{ row }">
                    <span class="text-sm">
                        {{ row.accepted_requests }}
                    </span>
                </template>

                <template #cell-in_progress_requests="{ row }">
                    <span class="text-sm">
                        {{ row.in_progress_requests }}
                    </span>
                </template>

                <template #cell-total_animals="{ row }">
                    <span class="">{{ row.total_animals }}</span>
                </template>
            </GenericTable>
        </div>
    </section>

    <CenterModal v-model="isShowModalOpen">
        <ReportsShow
            v-if="selectedReport"
            :accepted="selectedReport.accepted_requests"
            :adopted-animals="selectedReport.adopted_animals"
            :refuged-animals="selectedReport.refuged_animals"
            :in-progress="selectedReport.in_progress_requests"
            :animals-by-status="selectedReport.animals_by_status"
            :selected-month="selectedReport.month"
            :selected-year="selectedReport.year"
            :month-label="selectedReport.label"
            :species="species"
            :races="races"
            :coats="coats"
            :vaccines="vaccines"
            :can="can"
            :all-suitable-types="allSuitableTypes"
            @export-pdf="exportPdf(selectedReport)"
        />
    </CenterModal>
</template>
