<script setup lang="ts">
import {ref} from "vue";
import RightModal from "@/components/widgets/modals/RightModal.vue";

const props = defineProps({
    animal: Object,
});
const showCreate = ref(false);
</script>

<template>
    <section v-if="props.animal" class="flex flex-col gap-6 leading-9 mb-0">
        <div class="flex justify-between items-center">
            <h2 class="subtitle">{{ props.animal.name }}</h2>
            <div class="space-x-5">
                <button @click="showCreate=true"
                        class="button-green button-animation rounded-md p-2 font-semibold">
                    Ajouter une note
                </button>
                <button
                    class="button-blue button-animation rounded-md p-2 font-semibold">
                    Toutes les notes
                </button>
                <button
                    class="button-yellow button-animation rounded-md p-2 font-semibold">
                    Modifier
                </button>
                <button
                    class="button-orange button-animation rounded-md p-2 font-semibold">
                    Archiver
                </button>
            </div>
        </div>
        <div class="2xl:flex flex-col space-y-10 2xl:space-x-10">
            <div class="flex flex-col mx-auto sm:flex-row gap-40">
                <div class="flex flex-col lg:flex-row gap-5">
                    <img
                        src="/images/billy.webp"
                        :alt="props.animal.name"
                        class="w-full h-full object-cover aspect-square md:max-h-[300px] md:max-w-[300px]
                        lg:w-[300px] lg:h-auto rounded-2xl 2xl:max-h-[300px]"
                    />
                    <div
                        class="max-w-[640px] max-h-[300px] overflow-scroll flex flex-col lg:max-w-24 gap-5 md:min-w-[72px]"
                    >
                        <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl"
                        /> <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl"
                        /> <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl"
                        /> <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl"
                        /> <img
                            src="/images/billy.webp"
                            :alt="props.animal.name"
                            class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl"
                        />
                    </div>
                </div>

                <div class="md:max-h-[500px] space-y-2.5">
                    <div class="grid grid-cols-2 gap-5">
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Age</span>
                            <span class="xsmalltext">{{props.animal.age}}</span>
                        </p>

                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Espèce</span>
                            <span class="xsmalltext">{{props.animal.specie?.name}}</span>
                        </p>

                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Race</span>
                            <span class="xsmalltext">{{props.animal.specie.race?.name}}</span>
                        </p>

                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Pelage</span>
                            <span class="xsmalltext">{{props.animal.coat?.name}}</span>
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <p class="font-quicksand font-bold">Convient pour</p>
                            <div class="space-x-2.5">
                               <span>{{props.animal.suitable}}</span>
                            </div>
                        </div>

                        <div>
                            <p class="font-quicksand font-bold">Sorties</p>
                            <p class="xsmalltext">Les sorties ne posent pas de problèmes</p>
                        </div>

                    </div>
                </div>

            </div>

            <div class="md:px-10 mx-auto ">
                <h3 class="subsubtitle">Description</h3>
                <p class="text leading-9 max-w-11/12 whitespace-pre-line">
                    {{props.animal.description}}
                </p>
            </div>
        </div>
    </section>
    <RightModal v-model="showCreate">
        <h2>Create an note</h2>
    </RightModal>
</template>


<style scoped>

</style>
