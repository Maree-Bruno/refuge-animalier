import { ref } from 'vue';

export const useImagePreview = () => {
    const previewUrl = ref<string | null>(null);
    const handleSingleImage = (event: Event): File | null => {
        const target = event.target as HTMLInputElement;
        if (!target.files || !target.files[0]) return null;

        const file = target.files[0];
        previewUrl.value = URL.createObjectURL(file);
        return file;
    };

    const handleMultipleImages = (event: Event): { files: File[], previews: string[] } => {
        const target = event.target as HTMLInputElement;
        if (!target.files || target.files.length === 0) {
            return { files: [], previews: [] };
        }

        const files = Array.from(target.files);
        const previews = files.map(file => URL.createObjectURL(file));

        return { files, previews };
    };

    const removeSinglePreview = () => {
        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
            previewUrl.value = null;
        }
    };

    const removePreviewFromArray = (
        index: number,
        files: File[],
        previews: string[]
    ): { files: File[], previews: string[] } => {
        if (previews[index]) {
            URL.revokeObjectURL(previews[index]);
        }

        const dt = new DataTransfer();
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });

        return {
            files: Array.from(dt.files),
            previews: previews.filter((_, i) => i !== index)
        };
    };

    const cleanup = (previews?: string[]) => {
        if (previews && previews.length > 0) {
            previews.forEach(preview => URL.revokeObjectURL(preview));
        }
        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
            previewUrl.value = null;
        }
    };

    return {
        previewUrl,
        handleSingleImage,
        handleMultipleImages,
        removeSinglePreview,
        removePreviewFromArray,
        cleanup
    };
};
