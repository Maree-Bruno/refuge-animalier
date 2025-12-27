<script setup>
import { computed } from "vue";

const props = defineProps({
    status: { type: String, required: true }
});

const classes = computed(() => {
    switch (props.status) {
        case "Adopted":
            return "bg-lightgreenmint/50 text-green-900 border-greenmint";
        case "Validated":
            return "bg-lightsweetorange/20 text-orange-900 border-sweetorange";
        default:
            return "bg-lightblueslate/20 text-blueslate border-blueslate";
    }
});
</script>

<template>
  <span
      class="inline-block px-2 py-1 rounded-full text-xs font-medium border"
      :class="classes"
  >
    {{ status }}
  </span>
</template>
