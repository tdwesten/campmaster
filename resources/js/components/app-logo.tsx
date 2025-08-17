import { useLingua } from '@cyberwolf.studio/lingua-react';
import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    const { trans, locale } = useLingua();
    console.log('AppLogo rendered with locale:', locale);
    return (
        <>
            <div className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <AppLogoIcon className="size-6 fill-current text-white dark:text-black" />
            </div>
            <div className="ml-1 grid flex-1 text-left">
                <span className="text-primary mb-0.5 truncate leading-none font-semibold">{trans('messages.app_name')}</span>
            </div>
        </>
    );
}
