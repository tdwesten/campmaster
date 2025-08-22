import { useLingua } from '@cyberwolf.studio/lingua-react';
import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    const { trans } = useLingua();
    return (
        <>
            <div className="text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
                <AppLogoIcon className="size-10" />
            </div>
            <div className="ml-1 grid flex-1 text-left">
                <span className="text-primary mb-0.5 truncate text-lg font-bold">{trans('messages.app_name')}</span>
            </div>
        </>
    );
}
