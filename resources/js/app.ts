import '../css/app.css';

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import type {DefineComponent} from 'vue';
import {createApp, h} from 'vue';
import {initializeTheme} from './composables/useAppearance';
import AppLayout from './layouts/AppLayout.vue';
import {createPinia} from "pinia";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        );

        const pagesWithoutLayout = ['Auth/Login', 'Auth/ForgotPassword'];

        if (!pagesWithoutLayout.includes(name) && !page.default.layout) {
            page.default.layout = AppLayout;
        }

        return page;
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
