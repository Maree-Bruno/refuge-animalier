export function useAnimalImage() {
    const getUrl = (animal, size = "sm") => {
        const pic = animal.pictures?.[0];
        if (!pic) return "/images/billy.webp";

        const map = {
            sm: "300x300",
            md: "600x600",
            lg: "900x900"
        };

        return `/images/animals/variants/${map[size]}/${pic}`;
    };

    const getSrcset = (animal) => {
        const pic = animal.pictures?.[0];
        if (!pic) return "";

        return `
      /images/animals/variants/300x300/${pic} 300w,
      /images/animals/variants/600x600/${pic} 600w,
      /images/animals/variants/900x900/${pic} 900w
    `;
    };

    return { getUrl, getSrcset };
}
