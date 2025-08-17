import { AppArchive } from '@/components/app-archive';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import DataTable from '@/components/data-table/data-table';
import { guestColumns, type GuestItem } from './columns';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface GuestsPageProps {
    guests: {
        data: GuestItem[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
        links: PaginationLink[];
    };
}

export default function GuestsIndex({ guests }: GuestsPageProps) {
    const breadcrumbs = [
        {
            title: 'guests',
            href: '/guests',
        },
    ];

    function go(url: string | null) {
        if (!url) {
            return;
        }
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Guests" />

            <AppArchive title="Guests" subtitle="Browse and manage your guests">
                <DataTable<GuestItem, unknown>
                    columns={guestColumns}
                    data={guests.data}
                    searchKeys={['firstname', 'lastname', 'email']}
                    placeholder="Search guests..."
                    className="overflow-hidden rounded-xl border border-gray-200"
                />

                <div className="mt-4 flex items-center justify-between text-sm">
                    <div className="text-gray-600">
                        Page {guests.current_page} of {guests.last_page} · {guests.total} total
                    </div>
                    <div className="flex gap-2">
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.prev_page_url)}
                            disabled={!guests.prev_page_url}
                        >
                            Previous
                        </button>
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.next_page_url)}
                            disabled={!guests.next_page_url}
                        >
                            Next
                        </button>
                    </div>
                </div>
            </AppArchive>
        </AppLayout>
    );
}
