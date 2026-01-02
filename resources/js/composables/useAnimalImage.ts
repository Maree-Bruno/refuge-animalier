import { usePage } from '@inertiajs/vue3';

export function useAnimalImage() {
    const page = usePage();
    const storageUrl = () => {
        const url =
            (page.props.storage as { animals: string })?.animals || '/images';
        return url.endsWith('/') ? url.slice(0, -1) : url;
    };

    const getUrl = (
        animal: { pictures?: string[] },
        size: 'sm' | 'md' | 'lg' = 'sm',
    ) => {
        const pic = animal.pictures?.[0];
        if (!pic) return '/images/billy.webp';

        const map = {
            sm: '300x300',
            md: '600x600',
            lg: '900x900',
        };

        return `${storageUrl()}/animals/variants/${map[size]}/${pic}`;
    };

    const getSrcset = (animal: { pictures?: string[] }) => {
        const pic = animal.pictures?.[0];
        if (!pic) return '';

        const base = storageUrl();
        return `
      ${base}/animals/variants/300x300/${pic} 300w,
      ${base}/animals/variants/600x600/${pic} 600w,
      ${base}/animals/variants/900x900/${pic} 900w
    `;
    };

    return { getUrl, getSrcset };
}
