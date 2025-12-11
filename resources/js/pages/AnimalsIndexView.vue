<script setup>

  import AnimalIndex from "@/pages/animals/AnimalIndex.vue";
  import {usePage} from "@inertiajs/vue3";
  import {computed} from "vue";

  const page = usePage();
  const animals = computed(() => page.props.animals);
  const filters = computed(() => page.props.filters || {});
</script>

<template>
    <AnimalIndex
        :animals="animals"
        :filters="filters"
    />
</template>
