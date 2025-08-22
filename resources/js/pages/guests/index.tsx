import { PageWrapper } from '@/components/page-wrapper';
import DataTable from '@/components/data-table/data-table';
import Paginator from '@/components/paginator';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, router } from '@inertiajs/react';
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

            <PageWrapper title={trans('messages.guests.title')} subtitle={trans('messages.guests.subtitle')}>
                <DataTable<GuestItem, unknown>
                    columns={columns}
                    data={guests.data}
                    searchKeys={['firstname', 'lastname', 'email']}
                    placeholder={trans('messages.guests.search_placeholder')}
                />

                <Paginator
                    currentPage={guests.current_page}
                    lastPage={guests.last_page}
                    total={guests.total}
                    prevUrl={guests.prev_page_url}
                    nextUrl={guests.next_page_url}
                    links={guests.links}
                    onNavigate={go}
                />
            </PageWrapper>
        </AppLayout>
    );
}
