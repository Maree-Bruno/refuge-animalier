import { computed, type Ref, unref } from 'vue';
import { index as Dashboard } from "@/actions/App/Http/Controllers/DashboardController";
import { index as AnimalsIndexView } from "@/actions/App/Http/Controllers/AnimalController";
import { index as AdoptionRequestsIndexView } from "@/actions/App/Http/Controllers/AdoptionRequestController";
import { index as NotesIndexView } from "@/actions/App/Http/Controllers/NoteController";
import { index as ReportsIndexView } from "@/actions/App/Http/Controllers/ReportController";
import { index as DatabaseIndexView } from "@/actions/App/Http/Controllers/DatabaseController";
import { index as EmailsIndexView } from "@/actions/App/Http/Controllers/EmailController";
import { index as VolunteersIndexView } from "@/actions/App/Http/Controllers/VolunteerController";
import HomeIcon from "@/components/widgets/svg/HomeIcon.vue";
import DogIcon from "@/components/widgets/svg/DogIcon.vue";
import FormInputIcon from "@/components/widgets/svg/FormInputIcon.vue";
import NotesIcon from "@/components/widgets/svg/NotesIcon.vue";
import ReportsIcon from "@/components/widgets/svg/ReportsIcon.vue";
import DatabaseIcon from "@/components/widgets/svg/DatabaseIcon.vue";
import EmailsIcon from "@/components/widgets/svg/EmailsIcon.vue";
import VolunteerIcon from "@/components/widgets/svg/VolunteerIcon.vue";

// Types
export interface NavigationItem {
    title: string;
    href: () => string;
    icon: any;
    component: string;
}

export interface NavigationSection {
    label: string | null;
    items: NavigationItem[];
}

// Translations
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
        emails: "Emails",
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
        emails: "Emails",
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
        emails: "E-mails",
        volunteers: "Vrijwilligers"
    }
};

export type Locale = keyof typeof translations;

export function useNavigation(locale: Locale | Ref<Locale> = 'fr') {
    const t = computed(() => translations[unref(locale)]);

    const navigation = computed<NavigationSection[]>(() => [
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
                    component: "ReportsIndexView"
                },
                {
                    title: t.value.database,
                    href: DatabaseIndexView,
                    icon: DatabaseIcon,
                    component: "DatabaseIndexView"
                },
                {
                    title: t.value.emails,
                    href: EmailsIndexView,
                    icon: EmailsIcon,
                    component: "EmailsIndexView"
                },
                {
                    title: t.value.volunteers,
                    href: VolunteersIndexView,
                    icon: VolunteerIcon,
                    component: "VolunteersIndexView"
                }
            ]
        }
    ]);

    return {
        navigation
    };
}
