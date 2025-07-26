import * as React from 'react';

interface AppArchiveProps extends React.ComponentProps<'main'> {
    children: React.ReactNode;
    title?: string;
    subtitle?: string;
}

export function AppArchive({ children, ...props }: AppArchiveProps) {
    return (
        <main className="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl p-4" {...props}>
            <div className="flex">
                <div className="flex-1">
                    <h1 className="text-2xl font-bold">{props.title}</h1>
                    {props.subtitle && <p className="text-sm text-gray-500">{props.subtitle}</p>}
                </div>
            </div>
            {children}
        </main>
    );
}
