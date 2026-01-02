<script setup>
import VenusIcon from "@/components/widgets/svg/VenusIcon.vue";
import MarsIcon from "@/components/widgets/svg/MarsIcon.vue";
import { computed } from "vue";
import { useFormatDate } from "@/composables/useFormatDate";
import { useAnimalImage } from "@/composables/useAnimalImage";

const props = defineProps({
    animal: { type: Object, required: true }
});

const emit = defineEmits(["click"]);

const { formatDate } = useFormatDate();
const { getUrl, getSrcset } = useAnimalImage();

const statusClass = computed(() => {
    switch (props.animal.status) {
        case "Adopted":
            return "bg-lightgreenmint/50 text-green-900 border-greenmint";
        case "Validated":
            return "bg-lightsweetorange/20 text-orange-900 border-sweetorange";
        default:
            return "bg-lightblueslate/20 text-blueslate border-blueslate";
    }
});

const imageUrl = computed(() => getUrl(props.animal, "sm"));

const imageSrcset = computed(() => getSrcset(props.animal));
</script>

<template>
    <div
        class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
        @click="emit('click', animal)"
    >
        <div class="flex gap-3">
            <img
                :src="imageUrl"
                :srcset="imageSrcset"
                sizes="64px"
                :alt="animal.name"
                class="w-16 h-16 object-cover rounded-full"
                loading="lazy"
            />

            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h4 class="font-semibold truncate">{{ animal.name }}</h4>

                    <MarsIcon
                        v-if="animal.sex === 'male'"
                        class="svg-strokeblue w-5 h-5"
                    />
                    <VenusIcon
                        v-else
                        class="svg-strokeblue w-5 h-5"
                    />
                </div>

                <div class="space-y-1 text-xs text-gray-600">
                    <p><strong>Age :</strong> {{ animal.age }}</p>
                    <p><strong>Puce :</strong> {{ animal.chip }}</p>
                    <p><strong>Admission :</strong> {{ formatDate(animal.admission_date) }}</p>
                </div>

                <span
                    class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-medium border"
                    :class="statusClass"
                >
          {{ animal.status }}
        </span>
            </div>
        </div>
    </div>
</template>
