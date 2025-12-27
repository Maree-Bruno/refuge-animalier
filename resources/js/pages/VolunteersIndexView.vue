<script setup>
import VolunteerIndex from "@/pages/volunteers/VolunteerIndex.vue";

const props = defineProps({
    volunteers: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
    roles: Object,
})


</script>

<template>
   <VolunteerIndex v-bind="props"/>
</template>

