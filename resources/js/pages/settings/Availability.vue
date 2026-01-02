<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import SettingsLayout from "@/layouts/settings/Layout.vue";
import AvailabilityIcon from "@/components/widgets/table/AvailabilityIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore";

interface AvailabilityRow {
    id: number;
    period: string;
    monday: boolean;
    tuesday: boolean;
    wednesday: boolean;
    thursday: boolean;
    friday: boolean;
    saturday: boolean;
    sunday: boolean;
}

const props = defineProps<{
    availability: AvailabilityRow[];
}>();

const page = usePage();

const availabilityColumns = [
    { key: 'period', label: '' },
    { key: 'monday', label: 'Lundi' },
    { key: 'tuesday', label: 'Mardi' },
    { key: 'wednesday', label: 'Mercredi' },
    { key: 'thursday', label: 'Jeudi' },
    { key: 'friday', label: 'Vendredi' },
    { key: 'saturday', label: 'Samedi' },
    { key: 'sunday', label: 'Dimanche' },
];

const availabilityData = ref<AvailabilityRow[]>(props.availability);
const isSaving = ref(false);

const toggleCell = (rowId: number, columnKey: string) => {
    if (columnKey === 'period') return;

    const row = availabilityData.value.find(r => r.id === rowId);
    if (row) {
        row[columnKey as keyof AvailabilityRow] = !row[columnKey as keyof AvailabilityRow];
    }
};
const toast = useToasterStore();
const saveAvailability = () => {
    isSaving.value = true;

    router.patch('/admin/settings/availability', {
        availability: availabilityData.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success({ text: 'Disponibilité changée avec succès !' });
            isSaving.value = false;
        },
        onError: () => {
            isSaving.value = false;
        }
    });
};
</script>

<template>
    <SettingsLayout>
        <div class="w-full max-w-6xl mx-auto">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Mes disponibilités</h2>
                <button
                    @click="saveAvailability"
                    :disabled="isSaving"
                    class="px-4 py-2 button-yellow font-semibold rounded-lg button-animation disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    {{ isSaving ? 'Enregistrement...' : 'Enregistrer' }}
                </button>
            </div>
            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="w-full border-collapse bg-white">
                    <thead>
                    <tr>
                        <th
                            v-for="col in availabilityColumns"
                            :key="col.key"
                            class="px-4 py-3 text-left text-sm font-semibold border-b-2 border-slate-300 bg-slate-700 text-white"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="row in availabilityData"
                        :key="row.id"
                        class="hover:bg-slate-50"
                    >
                        <td
                            v-for="col in availabilityColumns"
                            :key="col.key"
                            @click="toggleCell(row.id, col.key)"
                            :class="[
                                'px-4 py-3 border-b border-slate-200',
                                col.key === 'period'
                                    ? 'font-semibold bg-slate-700 text-white cursor-default'
                                    : 'cursor-pointer transition-colors hover:bg-slate-100'
                            ]"
                        >
                            <template v-if="col.key === 'period'">
                                {{ row[col.key] }}
                            </template>
                            <template v-else>
                                <div class="flex items-center justify-center">
                                <AvailabilityIcon :value="row[col.key as keyof AvailabilityRow]" />
                                </div>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-sm text-slate-600">
                Cliquez sur une cellule pour basculer entre disponible et non disponible, puis cliquez sur "Enregistrer"
            </p>
        </div>
    </SettingsLayout>
</template>
