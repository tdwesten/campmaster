import '../css/app.css';

import { LinguaProvider } from '@cyberwolf.studio/lingua-react';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import { Lingua } from './lingua'; // Your generated translations

console.log('Lingua translations:', Lingua);

const appName = import.meta.env.VITE_APP_NAME || 'Campmaster';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        const currentLocale = (props.initialPage.props.locale as string) || 'en';

        root.render(
            <LinguaProvider locale={currentLocale} Lingua={Lingua}>
                <App {...props} />)
            </LinguaProvider>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
