import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, router } from '@inertiajs/react';

import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

export default function Appearance() {
    const { trans, locale } = useLingua();

    function handleLocaleChange(e: React.ChangeEvent<HTMLSelectElement>) {
        const locale = e.target.value;
        router.post('/settings/appearance/locale', { locale });
    }

    return (
        <AppLayout breadcrumbs={[{ title: trans('messages.settings.appearance.title'), href: '/settings/appearance' }]}>
            <Head title={trans('messages.settings.appearance.title')} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title={trans('messages.settings.appearance.title')}
                        description={trans('messages.settings.appearance.description')}
                    />
                    <AppearanceTabs />

                    <div className="space-y-2">
                        <HeadingSmall
                            title={trans('messages.settings.appearance.language.title')}
                            description={trans('messages.settings.appearance.language.description')}
                        />
                        <div>
                            <label htmlFor="locale" className="block text-sm font-medium">
                                {trans('messages.settings.appearance.language.select_label')}
                            </label>
                            <select
                                id="locale"
                                className="mt-1 block w-full rounded-md border px-3 py-2"
                                defaultValue={locale}
                                onChange={handleLocaleChange}
                            >
                                <option value="en">{trans('messages.settings.appearance.language.en')}</option>
                                <option value="nl">{trans('messages.settings.appearance.language.nl')}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
