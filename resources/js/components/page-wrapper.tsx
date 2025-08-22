import { cn } from '@/lib/utils';
import * as React from 'react';

interface PageWrapperProps extends React.ComponentProps<'main'> {
    children: React.ReactNode;
    title?: string;
    subtitle?: string;
    fullWidth?: boolean;
    actions?: React.ReactNode;
}

export function PageWrapper({ fullWidth, children, actions, ...props }: PageWrapperProps) {
    return (
        <main
            className={cn(
                fullWidth
                    ? 'mx-auto flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 px-6'
                    : 'mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl p-4 px-6',
            )}
            {...props}
        >
            <div className="flex items-center justify-center gap-4">
                <div className="mt-4 flex-1">
                    <h1 className="text-2xl font-bold">{props.title}</h1>
                    {props.subtitle && <p className="text-sm text-gray-500">{props.subtitle}</p>}
                </div>
                {actions && <div className="mt-4 flex items-center">{actions}</div>}
            </div>
            {children}
        </main>
    );
}

export type { PageWrapperProps };
