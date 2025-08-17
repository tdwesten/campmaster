import { AppArchive } from '@/components/app-archive';
import DataTable from '@/components/data-table/data-table';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { guestColumns, type GuestItem } from './columns';
import useLingua from '@cyberwolf.studio/lingua-react';

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
    const { trans } = useLingua();

    const breadcrumbs = [
        {
            title: trans('messages.guests.breadcrumb'),
            href: '/guests',
        },
    ];

    const columns = guestColumns(trans);

    function go(url: string | null) {
        if (!url) {
            return;
        }
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.guests.title')} />

            <AppArchive title={trans('messages.guests.title')} subtitle={trans('messages.guests.subtitle')}>
                <DataTable<GuestItem, unknown>
                    columns={columns}
                    data={guests.data}
                    searchKeys={['firstname', 'lastname', 'email']}
                    placeholder={trans('messages.guests.search_placeholder')}
                    className="overflow-hidden border-gray-200"
                />

                <div className="mt-4 flex items-center justify-between text-sm">
                    <div className="text-gray-600">
                        {trans('messages.pagination.page')} {guests.current_page} {trans('messages.pagination.of')} {guests.last_page} · {guests.total} {trans('messages.pagination.total')}
                    </div>
                    <div className="flex gap-2">
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.prev_page_url)}
                            disabled={!guests.prev_page_url}
                        >
                            {trans('messages.pagination.previous')}
                        </button>
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.next_page_url)}
                            disabled={!guests.next_page_url}
                        >
                            {trans('messages.pagination.next')}
                        </button>
                    </div>
                </div>
            </AppArchive>
        </AppLayout>
    );
}
