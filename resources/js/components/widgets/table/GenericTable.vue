<script setup>
import {ref, computed, watch} from 'vue';
import ArrowUpIcon from "@/components/widgets/svg/ArrowUpIcon.vue";

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        default: () => []
    },
    actions: {
        type: Array,
        default: () => []
    },
    selectable: {
        type: Boolean,
        default: true
    },
    emptyMessage: {
        type: String,
        default: 'Aucune donnée disponible'
    }
});

const emit = defineEmits(['row-select', 'select-all', 'sort']);

const selectedRows = ref([]);
const selectAll = ref(false);
const sortKey = ref(null);
const sortOrder = ref('asc');

const columnCount = computed(() => {
    let count = props.columns.length;
    if (props.selectable) count++;
    if (props.actions.length > 0) count++;
    return count;
});

const sortedData = computed(() => {
    if (!sortKey.value) return props.data;

    return [...props.data].sort((a, b) => {
        let aValue = a[sortKey.value];
        let bValue = b[sortKey.value];

        // Conversion si nécessaire
        if (sortKey.value.includes('date')) {
            aValue = new Date(aValue).getTime();
            bValue = new Date(bValue).getTime();
        } else if (typeof aValue === 'string') {
            aValue = aValue.toLowerCase();
            bValue = bValue.toLowerCase();
        }

        const result = aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
        return sortOrder.value === 'asc' ? result : -result;
    });
});

const handleSort = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortKey.value = key;
        sortOrder.value = 'asc';
    }

    emit('sort', {key: sortKey.value, order: sortOrder.value});
};

const handleSelectAll = () => {
    if (selectAll.value) {
        selectedRows.value = sortedData.value.map(row => row.id);
    } else {
        selectedRows.value = [];
    }
    emit('select-all', selectedRows.value);
};

const handleRowSelect = () => {
    selectAll.value = selectedRows.value.length === sortedData.value.length && sortedData.value.length > 0;
    emit('row-select', selectedRows.value);
};

watch(() => props.data, () => {
    selectedRows.value = [];
    selectAll.value = false;
}, {deep: true});
</script>
<template>
    <div class="w-full overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full min-w-max">
            <thead class="bg-blueslate text-white border border-transparent">
            <tr class="font-semibold">
                <td v-if="selectable" class="p-2">
                    <input
                        type="checkbox"
                        id="select-all"
                        v-model="selectAll"
                        @change="handleSelectAll"
                    />
                    <label for="select-all"></label>
                </td>
                <td
                    v-for="column in columns"
                    :key="column.key"
                    class="px-4 py-3 text-left text-sm font-semibold border-b-2 border-slate-300 bg-slate-700 text-white"
                    :class="[column.class, column.sortable !== false ? 'cursor-pointer select-none hover:bg-slate-600 transition-colors' : '']"
                    @click="column.sortable !== false ? handleSort(column.key) : null"
                >
                    <div class="flex items-center gap-2">
                        <span>{{ column.label }}</span>
                        <span v-if="column.sortable !== false" class="flex flex-col">
                <ArrowUpIcon class="svg-strokewhite w-4 h-4"
                             :class="sortKey === column.key && sortOrder === 'asc' ?'not-sr-only' : 'sr-only'"/>
                <ArrowUpIcon class="svg-strokewhite w-4 h-4 rotate-180"
                             :class="sortKey === column.key && sortOrder === 'desc' ? 'not-sr-only' : 'sr-only'"/>
              </span>
                    </div>
                </td>
                <td v-if="actions.length > 0" class="p-2">
                    <span>Actions</span>
                </td>
            </tr>
            </thead>
            <tbody v-if="sortedData.length > 0">
            <tr v-for="row in sortedData" :key="row.id"
                class="hover:bg-gray-50 transition-colors border-b border-gray-200">
                <td v-if="selectable" class="p-2">
                    <input
                        type="checkbox"
                        :id="`row-${row.id}`"
                        :value="row.id"
                        v-model="selectedRows"
                        @change="handleRowSelect"
                    />
                    <label :for="`row-${row.id}`"></label>
                </td>
                <td v-for="column in columns" :key="column.key" class="p-2" :class="column.class">
                    <slot :name="`cell-${column.key}`" :row="row" :value="row[column.key]">
                        <span>{{ row[column.key] }}</span>
                    </slot>
                </td>
                <td v-if="actions.length > 0" class="p-2">
                    <div class="flex gap-2">
                        <button
                            v-for="(action, index) in actions"
                            :key="index"
                            @click="action.handler(row)"
                            :class="action.class || 'text-gray-600 hover:bg-gray-100'"
                            class="p-2 rounded transition-colors"
                            :title="action.label"
                        >
                            <component v-if="action.icon" :is="action.icon" class="w-4 h-4 svg-strokeblue"/>
                            <span v-else>{{ action.label }}</span>
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td :colspan="columnCount" class="p-8 text-center text-gray-500">
                    {{ emptyMessage }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>


<style scoped>


</style>
