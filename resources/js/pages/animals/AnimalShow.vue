<script setup lang="ts">
import {ref, computed} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import RightModal from "@/components/widgets/modals/RightModal.vue";
import CenterModal from "@/components/widgets/modals/CenterModal.vue";
import AnimalEdit from "@/pages/animals/AnimalEdit.vue";
import AnimalCreate from "@/pages/animals/AnimalCreate.vue";
import TrashIcon from "@/components/widgets/svg/TrashIcon.vue";
import EditIcon from "@/components/widgets/svg/EditIcon.vue";
import {useToasterStore} from "@/stores/useToasterStore";

const props = defineProps({
    animal: {type: Object},
    species: {type: Object},
    races: {type: Object},
    coats: {type: Object},
    vaccines: {type: Object},
    allSuitableTypes: {type: Object},
    can: Object,
});
const toast = useToasterStore();
const showCreate = ref(false);
const showEdit = ref(false);
const showNotes = ref(false);
const selectedImageIndex = ref(0);
const animalNotes = computed(() => {
    return props.animal?.notes ?? [];
});
const getImageUrl = (filename: string, size: 'sm' | 'md' | 'lg' = 'md'): string => {
    if (!filename) return '/images/billy.webp';

    const sizeMap = {
        sm: '300x300',
        md: '600x600',
        lg: '900x900'
    };

    return `/images/animals/variants/${sizeMap[size]}/${filename}`;
};

const getImageSrcset = (filename: string): string => {
    if (!filename) return '';

    return [
        `/images/animals/variants/300x300/${filename} 300w`,
        `/images/animals/variants/600x600/${filename} 600w`,
        `/images/animals/variants/900x900/${filename} 900w`
    ].join(', ');
};

const mainImage = computed(() => {
    if (!props.animal?.pictures || props.animal.pictures.length === 0) {
        return {
            src: '/images/billy.webp',
            srcset: '',
            alt: props.animal?.name || 'Animal'
        };
    }

    const filename = props.animal.pictures[selectedImageIndex.value];
    return {
        src: getImageUrl(filename, 'lg'),
        srcset: getImageSrcset(filename),
        alt: props.animal.name
    };
});

const thumbnails = computed(() => {
    if (!props.animal?.pictures || props.animal.pictures.length === 0) {
        return [];
    }

    return props.animal.pictures.map((filename: string) => ({
        src: getImageUrl(filename, 'sm'),
        srcset: getImageSrcset(filename),
        filename
    }));
});

const suitableText = computed(() => {
    if (!props.animal?.suitable_types || props.animal.suitable_types.length === 0) {
        return 'Non spécifié';
    }

    return props.animal.suitable_types
        .map((type: any) => type.name)
        .join(', ');
});


const outsideText = computed(() => {
    return props.animal?.outside
        ? 'Les sorties ne posent pas de problèmes'
        : 'Les sorties sont restreintes';
});

const statusClass = computed(() => {
    const statusMap: Record<string, string> = {
        Adopted: 'bg-lightgreenmint/50 text-green-900 border-greenmint',
        Validated: 'bg-lightsweetorange/20 text-orange-900 border-sweetorange',
        default: 'bg-lightblueslate/20 text-blueslate border-blueslate'
    };

    return statusMap[props.animal?.status as string] || statusMap.default;
});

const selectImage = (index: number) => {
    selectedImageIndex.value = index;
};
const noteForm = useForm({
    title: '',
    content: '',
});

const submitNote = () => {
    router.post('/notes', {
        title: noteForm.value.title,
        content: noteForm.value.content,
        notable_type: 'App\\Models\\Animal',
        notable_id: props.animal.id,
    }, {
        onSuccess: () => {
            toast.success({text: 'Note créée avec succès'});
            noteForm.value.title = '';
            noteForm.value.content = '';
            showCreate.value = false;
        }
    });
};
const showDeleteConfirm = ref(false);
const noteToDelete = ref(null);

const openDeleteConfirm = (note) => {
    noteToDelete.value = note;
    showDeleteConfirm.value = true;
};


const deleteNote = (note) => {
    if (!note) return;

    router.delete(`/notes/${note.id}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({ text: 'Note supprimée avec succès' });
            showDeleteConfirm.value = false;
        },
        onError: () => {
            toast.error({ text: 'Impossible de supprimer la note.' });
        }
    });
};

const editNoteForm = useForm({
    id: null,
    title: '',
    content: '',
});

const showEditNote = ref(false);

const openEditNote = (note) => {
    editNoteForm.id = note.id;
    editNoteForm.title = note.title;
    editNoteForm.content = note.content;
    showEditNote.value = true;
};

const submitEditNote = () => {
    router.patch(`/notes/${editNoteForm.id}`, {
        title: editNoteForm.title,
        content: editNoteForm.content,
    }, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.success({text: 'Note éditée avec succès'});
            showEditNote.value = false;
        }
    });
};

</script>

<template>
    <section v-if="animal" class="w-full h-full">
        <div class="flex flex-col gap-4 sm:gap-6 p-4 sm:p-6 max-h-[80vh] overflow-y-scroll">
            <div class="flex flex-col gap-3 sm:gap-4">
                <h2 class="subtitle text-xl sm:text-2xl">{{ animal.name }}</h2>

                <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                    <button
                        @click="showCreate = true"
                        class="button-green button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Ajouter une note
                    </button>
                    <button
                        @click="showNotes = true"
                        class="button-blue button-animation rounded-md p-2 text-sm sm:text-base font-semibold whitespace-nowrap"
                    >
                        Toutes les notes
                    </button>
                    <button
                        @click="showEdit = true"
                        class="button-yellow button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Modifier
                    </button>
                    <button
                        class="button-orange button-animation rounded-md p-2 text-sm sm:text-base font-semibold"
                    >
                        Archiver
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-6 sm:gap-8">
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-10">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-5">
                        <img
                            :src="mainImage.src"
                            :srcset="mainImage.srcset"
                            sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 300px"
                            :alt="mainImage.alt"
                            class="w-full sm:w-auto sm:h-[250px] lg:h-[300px] aspect-square object-cover rounded-2xl"
                            loading="eager"
                        />

                        <div
                            v-if="thumbnails.length > 0"
                            class="flex sm:flex-col gap-2 sm:gap-3 overflow-x-auto sm:overflow-y-auto sm:max-h-[250px] lg:max-h-[300px] pb-2 sm:pb-0"
                        >
                            <button
                                v-for="(thumb, index) in thumbnails"
                                :key="index"
                                @click="selectImage(index)"
                                :class="[
                                    'w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden transition-all border-2',
                                    selectedImageIndex === index
                                        ? 'border-blueslate ring-2 ring-blueslate/30'
                                        : 'border-transparent hover:border-gray-300'
                                ]"
                            >
                                <img
                                    :src="thumb.src"
                                    :srcset="thumb.srcset"
                                    sizes="80px"
                                    :alt="`${animal.name} - Image ${index + 1}`"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                            </button>
                        </div>

                        <div v-else class="flex sm:flex-col gap-2 sm:gap-3">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-gray-100 flex items-center justify-center">
                                <span class="text-xs text-gray-400">Aucune photo</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Age</span>
                                <span class="text-xs sm:text-sm">{{ animal.age }} an(s)</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Espèce</span>
                                <span class="text-xs sm:text-sm">{{ animal.specie?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Race</span>
                                <span class="text-xs sm:text-sm">{{ animal.race?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Pelage</span>
                                <span class="text-xs sm:text-sm">{{ animal.coat?.name || 'Non spécifié' }}</span>
                            </div>

                            <div class="flex flex-col col-span-2">
                                <span class="font-quicksand font-bold text-sm sm:text-base">Puce</span>
                                <span class="text-xs sm:text-sm font-mono">{{ animal.chip }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Convient pour</p>
                                <span class="text-xs sm:text-sm">{{ suitableText }}</span>
                            </div>

                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Sorties</p>
                                <p class="text-xs sm:text-sm">{{ outsideText }}</p>
                            </div>

                            <div>
                                <p class="font-quicksand font-bold text-sm sm:text-base">Status</p>
                                <span
                                    :class="[
                                        'inline-block px-3 py-1 rounded-full text-xs font-medium border mt-1',
                                        statusClass
                                    ]"
                                >
                                    {{ animal.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <h3 class="font-quicksand font-bold text-lg sm:text-xl">Description</h3>
                    <p class="text-sm sm:text-base leading-relaxed wrap-break-word overflow-hidden">
                        {{ animal.description || 'Aucune description disponible.' }}
                    </p>
                </div>
            </div>
        </div>

    </section>
    <CenterModal v-model="showNotes">
        <div class="space-y-5">
            <h2 class="subsubtitle">Notes – {{ animal.name }}</h2>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto p-4">
                <div v-if="animalNotes.length === 0" class="text-center text-gray-500 text-sm">
                    Aucune note pour cet animal.
                </div>

                <div v-for="note in animalNotes" :key="note.id" class="border rounded-lg p-4 bg-white shadow-sm"
                     @click="">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-sm">{{ note.title }}</h3>
                        <span class="text-xs text-gray-400">{{ new Date(note.created_at).toLocaleDateString() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm mt-2 whitespace-pre-line">{{ note.content }}</p>
                        <div class="space-x-2.5">
                            <button @click="openEditNote(note)" class="ml-2">
                                <EditIcon class="w-4 h-4 svg-strokeblue"/>
                            </button>

                            <button @click="openDeleteConfirm(note)">
                                <TrashIcon class="w-4 h-4 svg-strokeblue"/>
                            </button>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </CenterModal>

    <RightModal v-model="showCreate">
        <template #header>
            <h2 class="subsubtitle">Ajouter une note – {{ animal.name }}</h2>
        </template>

        <form class="p-4 space-y-4" @submit.prevent="submitNote">
            <div class="flex flex-col gap-2">
                <label for="title" class="text-sm font-medium text-gray-700">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="noteForm.title"
                    type="text"
                    placeholder="Titre de la note"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                />
                <span v-if="noteForm.errors.title" class="text-sm text-red-500">
                {{ noteForm.errors.title }}
            </span>
            </div>

            <div class="flex flex-col gap-2">
                <label for="content" class="text-sm font-medium text-gray-700">
                    Contenu <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="content"
                    v-model="noteForm.content"
                    rows="6"
                    placeholder="Contenu de la note..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent resize-none"
                ></textarea>
                <span v-if="noteForm.errors.content" class="text-sm text-red-500">
                {{ noteForm.errors.content }}
            </span>
            </div>

            <button
                type="submit"
                class="button-yellow button-animation rounded-lg px-4 py-2 font-semibold"
                :disabled="noteForm.processing"
            >
                {{ noteForm.processing ? 'Création...' : 'Création la note' }}
            </button>
        </form>
    </RightModal>


    <RightModal v-model="showEdit">
        <template #header>
            <h2 class="subsubtitle">Modifier {{ animal?.name }}</h2>
        </template>
        <AnimalEdit
            :animal="animal"
            :species="species"
            :races="races"
            :coats="coats"
            :vaccines="vaccines"
            :allSuitableTypes="allSuitableTypes"
            :can="can"
            @close="showEdit = false"
        />
    </RightModal>
    <RightModal v-model="showEditNote">
        <template #header>
            <h2 class="subsubtitle">Modifier la note – {{ editNoteForm.title }}</h2>
        </template>
        <form @submit.prevent="submitEditNote" class="p-4 space-y-4">
            <div class="flex flex-col gap-2">
                <label for="title" class="text-sm font-medium text-gray-700">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="editNoteForm.title"
                    type="text"
                    placeholder="Titre de la note"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent"
                />
                <span v-if="editNoteForm.errors.title" class="text-sm text-red-500">
                {{ editNoteForm.errors.title }}
            </span>
            </div>

            <div class="flex flex-col gap-2">
                <label for="content" class="text-sm font-medium text-gray-700">
                    Contenu <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="content"
                    v-model="editNoteForm.content"
                    rows="6"
                    placeholder="Contenu de la note..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blueslate focus:border-transparent resize-none"
                ></textarea>
                <span v-if="editNoteForm.errors.content" class="text-sm text-red-500">
                {{ editNoteForm.errors.content }}
            </span>
            </div>

            <button
                type="submit"
                class="button-yellow button-animation rounded-lg px-4 py-2 font-semibold"
                :disabled="editNoteForm.processing"
            >
                {{ editNoteForm.processing ? 'Modification...' : 'Modification la note' }}
            </button>
        </form>
    </RightModal>
    <CenterModal v-model="showDeleteConfirm">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <TrashIcon class="w-6 h-6 text-red-600"/>
            </div>

            <h3 class="text-lg font-semibold text-center mb-2">
                Confirmer la suppression
            </h3>

            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir supprimer
                <span class="font-semibold">{{ noteToDelete?.title }}</span> ?
                Cette action est irréversible.
            </p>
            <div class="flex gap-3 justify-end">
                <button
                    @click="showDeleteConfirm = false"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Annuler
                </button>
                <button
                    @click="deleteNote(noteToDelete)"
                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </CenterModal>
</template>

<style scoped>
.overflow-x-auto::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
