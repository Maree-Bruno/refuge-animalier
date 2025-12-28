import { computed, type Ref, unref } from 'vue';
import { index as Dashboard } from "@/actions/App/Http/Controllers/DashboardController";
import { index as AnimalsIndexView } from "@/actions/App/Http/Controllers/AnimalController";
import { index as AdoptionRequestsIndexView } from "@/actions/App/Http/Controllers/AdoptionRequestController";
import { index as NotesIndexView } from "@/actions/App/Http/Controllers/NoteController";
import { index as ReportsIndexView } from "@/actions/App/Http/Controllers/ReportController";
import { index as DatabaseIndexView } from "@/actions/App/Http/Controllers/DatabaseController";
import { index as ContactMessageIndexView } from "@/actions/App/Http/Controllers/ContactMessageController";
import { index as VolunteersIndexView } from "@/actions/App/Http/Controllers/VolunteerController";
import HomeIcon from "@/components/widgets/svg/HomeIcon.vue";
import DogIcon from "@/components/widgets/svg/DogIcon.vue";
import FormInputIcon from "@/components/widgets/svg/FormInputIcon.vue";
import NotesIcon from "@/components/widgets/svg/NotesIcon.vue";
import ReportsIcon from "@/components/widgets/svg/ReportsIcon.vue";
import DatabaseIcon from "@/components/widgets/svg/DatabaseIcon.vue";
import EmailsIcon from "@/components/widgets/svg/EmailsIcon.vue";
import VolunteerIcon from "@/components/widgets/svg/VolunteerIcon.vue";
import {usePage} from "@inertiajs/vue3";


export interface NavigationItem {
    title: string;
    href: () => string;
    icon: any;
    component: string;
}

export interface NavigationSection {
    label: string | null;
    items: NavigationItem[];
    requiresRole?: string
}


const translations = {
    fr: {
        dashboard: "Dashboard",
        refuge: "Refuge",
        animals: "Animaux",
        adoptionRequests: "Demande d'adoption",
        notes: "Notes",
        admin: "Admin",
        reports: "Rapports",
        database: "Base de données",
        contactmessages: "Message de contact",
        volunteers: "Bénévoles"
    },
    en: {
        dashboard: "Dashboard",
        refuge: "Shelter",
        animals: "Animals",
        adoptionRequests: "Adoption Requests",
        notes: "Notes",
        admin: "Admin",
        reports: "Reports",
        database: "Database",
        contactmessages: "Contact messages ",
        volunteers: "Volunteers"
    },
    nl: {
        dashboard: "Dashboard",
        refuge: "Opvang",
        animals: "Dieren",
        adoptionRequests: "Adoptieaanvragen",
        notes: "Notities",
        admin: "Beheer",
        reports: "Rapporten",
        database: "Database",
        contactmessages: "E-mails",
        volunteers: "Vrijwilligers"
    }
};

export type Locale = keyof typeof translations;

function useNavigation(locale: Locale | Ref<Locale> = 'fr') {
    const t = computed(() => translations[unref(locale)]);
    const page = usePage();
    const user = computed(() => page.props.auth?.user);

/*@ts-ignore*/
    const navigation = computed<NavigationSection[]>(() => {
        const allSections = [
            {
                label: null,
                items: [
                    {
                        title: t.value.dashboard,
                        href: Dashboard,
                        icon: HomeIcon,
                        component: "Dashboard"
                    }
                ]
            },
            {
                label: t.value.refuge,
                items: [
                    {
                        title: t.value.animals,
                        href: AnimalsIndexView,
                        icon: DogIcon,
                        component: "AnimalsIndexView"
                    },
                    {
                        title: t.value.adoptionRequests,
                        href: AdoptionRequestsIndexView,
                        icon: FormInputIcon,
                        component: "AdoptionRequestsIndexView"
                    },
                    {
                        title: t.value.notes,
                        href: NotesIndexView,
                        icon: NotesIcon,
                        component: "NotesIndexView"
                    }
                ]
            },
            {
                label: t.value.admin,
                items: [
                    {
                        title: t.value.reports,
                        href: ReportsIndexView,
                        icon: ReportsIcon,
                        component: "ReportsIndexView",
                        requiresRole: 'admin'
                    },
                    {
                        title: t.value.database,
                        href: DatabaseIndexView,
                        icon: DatabaseIcon,
                        component: "DatabaseIndexView",
                        requiresRole: 'admin'
                    },
                    {
                        title: t.value.contactmessages,
                        href: ContactMessageIndexView,
                        icon: EmailsIcon,
                        component: "ContactMessageIndexView",
                        requiresRole: 'admin'
                    },
                    {
                        title: t.value.volunteers,
                        href: VolunteersIndexView,
                        icon: VolunteerIcon,
                        component: "VolunteersIndexView",
                        requiresRole: 'admin'
                    }
                ]
            }
        ];

        return allSections.map(section => ({
            ...section,
            items: section.items.filter(item => {
                if (!item.requiresRole) return true;

                const userRole = user.value?.role;
                if (!userRole) return false;

                if (Array.isArray(item.requiresRole)) {
                    return item.requiresRole.includes(userRole);
                }

                return userRole === item.requiresRole;
            })
        })).filter(section => section.items.length > 0);
    });

    return {
        navigation
    };
}

export default useNavigation
