import './bootstrap';
import '../css/app.css';

import {createApp, h, onMounted} from 'vue';
import {createInertiaApp, usePage} from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Notifications, {notify} from 'notiwind'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Notifications)
            .use(ZiggyVue, Ziggy)
            .mount(el);
        window.Echo
            .private(`App.Models.User.${usePage().props.auth.user.id}`)
            .listen(".parse.failed", (e) => {
                notify({
                    group: 'error',
                    title: 'Parse Failed',
                    text: e.message,
                })
            });
        return app
    },
    progress: {
        color: '#4B5563',
    },
});

