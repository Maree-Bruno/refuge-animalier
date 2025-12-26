export function useFormatDate() {
    const formatDate = (date: string) => {
        if (!date) return '-';
        return new Date(date).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric'
        });
    }

    return { formatDate };
}
