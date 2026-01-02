import { usePage } from '@inertiajs/vue3';

export const useUserHelpers = () => {
    const page = usePage();
    const storageUrl = () =>
        (page.props.storage as { users: string })?.users || '/images';

    const profileImageVariants = {
        xs: '64x64',
        sm: '128x128',
        md: '256x256',
        lg: '512x512',
    } as const;

    const getInitials = (name: string | null | undefined): string => {
        if (!name) return '';
        return name
            .split(' ')
            .filter(Boolean)
            .map((n) => n[0])
            .join('')
            .toUpperCase();
    };

    const getUserImageUrl = (
        picture: string | null | undefined,
        size: keyof typeof profileImageVariants = 'md',
    ): string => {
        if (!picture) return '/images/billy.webp';
        return `${storageUrl()}/users/variants/${profileImageVariants[size]}/${picture}`;
    };

    const getUserImageSrcset = (picture: string | null | undefined): string => {
        if (!picture) return '';
        const base = storageUrl();
        return Object.entries(profileImageVariants)
            .map(
                ([key, size]) =>
                    `${base}/users/variants/${size}/${picture} ${size.split('x')[0]}w`,
            )
            .join(', ');
    };

    return {
        getInitials,
        getUserImageUrl,
        getUserImageSrcset,
        profileImageVariants,
    };
};
