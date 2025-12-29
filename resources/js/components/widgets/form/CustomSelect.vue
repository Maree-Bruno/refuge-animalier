<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { ChevronDown, X } from 'lucide-vue-next';

const props = defineProps({
    modelValue: [String, Number],
    options: Object,
    label: String,
    placeholder: String,
    error: String,
    displayKey: {
        type: String,
        default: 'name'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchQuery = ref('');
const selectRef = ref(null);

const selectedOption = computed(() =>
    props.options?.find(opt => opt.id === props.modelValue)
);

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options || [];
    return props.options?.filter(opt =>
        opt.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        opt.chip?.toLowerCase().includes(searchQuery.value.toLowerCase())
    ) || [];
});

const selectOption = (option) => {
    emit('update:modelValue', option.id);
    isOpen.value = false;
    searchQuery.value = '';
};

const clearSelection = () => {
    emit('update:modelValue', '');
    searchQuery.value = '';
};

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
    }
};

const handleClickOutside = (event) => {
    if (selectRef.value && !selectRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const getSexIcon = (sex) => {
    if (sex === 'M' || sex === 'male') return '♂';
    if (sex === 'F' || sex === 'female') return '♀';
    return '';
};

const getSexColor = (sex) => {
    if (sex === 'M' || sex === 'male') return 'text-blue-500';
    if (sex === 'F' || sex === 'female') return 'text-pink-500';
    return 'text-gray-500';
};
</script>

<template>
    <div class="flex flex-col gap-2 w-full" ref="selectRef">
        <label v-if="label" class="font-semibold text-gray-700">
            {{ label }}
        </label>

        <div class="relative">
            <div
                @click="toggleDropdown"
                class="flex items-center justify-between w-full px-4 py-3 bg-white border rounded-lg cursor-pointer transition-all"
                :class="[
                    error ? 'border-red-500' : ''
                ]"
            >
                <div v-if="selectedOption" class="flex items-center gap-3 flex-1 min-w-0">
                    <img
                        v-if="selectedOption.image"
                        :src="selectedOption.image"
                        :alt="selectedOption.name"
                        class="w-10 h-10 rounded-full object-cover"
                    >
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-medium truncate">{{ selectedOption.name }}</span>
                            <span
                                v-if="selectedOption.sex"
                                :class="getSexColor(selectedOption.sex)"
                                class="text-lg font-bold"
                            >
                                {{ getSexIcon(selectedOption.sex) }}
                            </span>
                        </div>
                        <span v-if="selectedOption.chip" class="text-xs text-gray-500">
                            Puce: {{ selectedOption.chip }}
                        </span>
                    </div>
                    <button
                        type="button"
                        @click.stop="clearSelection"
                        class="p-1 hover:bg-gray-100 rounded-full transition-colors"
                    >
                        <X :size="16" class="text-gray-500" />
                    </button>
                </div>
                <span v-else class="text-gray-400">
                    {{ placeholder || 'Sélectionner une option' }}
                </span>

                <ChevronDown
                    :size="20"
                    class="text-gray-400 transition-transform ml-2"
                    :class="{ 'rotate-180': isOpen }"
                />
            </div>

            <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div
                    v-if="isOpen"
                    class="absolute z-50 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg max-h-80 overflow-hidden"
                >
                    <div class="p-3 border-b border-gray-200 sticky top-0 bg-white">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Rechercher..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-sweetorange/50"
                            @click.stop
                        >
                    </div>

                    <div class="overflow-y-auto max-h-64">
                        <div
                            v-for="option in filteredOptions"
                            :key="option.id"
                            @click="selectOption(option)"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                            :class="{ 'bg-sweetorange/10': option.id === modelValue }"
                        >
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium truncate">{{ option.name }}</span>
                                    <span
                                        v-if="option.sex"
                                        :class="getSexColor(option.sex)"
                                        class="text-lg font-bold"
                                    >
                                        {{ getSexIcon(option.sex) }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span v-if="option.chip" class="text-gray-600">
                                        {{ option.chip }}
                                    </span>
                                    <span v-if="option.species" class="text-gray-600">
                                        {{ option.species.value }}
                                    </span>
                                    <span v-if="option.race" class="text-gray-600">
                                        {{ option.race.value }}
                                    </span>
                                    <span v-if="option.age" class="text-gray-600">
                                        {{ option.age }} ans
                                    </span>
                                </div>
                            </div>

                            <div v-if="option.status" >
                                <span
                                    class="px-2 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': option.status === 'available',
                                        'bg-yellow-100 text-yellow-800': option.status === 'pending',
                                        'bg-red-100 text-red-800': option.status === 'adopted'
                                    }"
                                >
                                    {{ option.status }}
                                </span>
                            </div>
                        </div>

                        <div v-if="filteredOptions.length === 0" class="px-4 py-8 text-center text-gray-500">
                            Aucun résultat trouvé
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <span v-if="error" class="text-red-500 text-sm">
            {{ error }}
        </span>
    </div>
</template>
