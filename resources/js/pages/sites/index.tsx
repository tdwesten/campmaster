import DataTable from '@/components/data-table/data-table';
import { PageWrapper } from '@/components/page-wrapper';
import Paginator from '@/components/paginator';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, Link, router } from '@inertiajs/react';
import { siteColumns, type SiteItem } from './columns';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface SitesPageProps {
    sites: {
        data: SiteItem[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
        links: PaginationLink[];
    };
    actions?: { create_url: string };
}

export default function SitesIndex({ sites, actions }: SitesPageProps) {
    const { trans } = useLingua();

    const breadcrumbs = [{ title: trans('messages.sites.breadcrumb'), href: '/sites' }];

    function go(url: string | null) {
        if (!url) return;
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.sites.title')} />
            <PageWrapper
                title={trans('messages.sites.title')}
                subtitle={trans('messages.sites.subtitle')}
                actions={
                    <Link href={actions?.create_url ?? route('sites.create')}>
                        <Button>{trans('messages.sites.actions.create')}</Button>
                    </Link>
                }
            >
                <DataTable<SiteItem, unknown>
                    columns={siteColumns(trans)}
                    data={sites.data}
                    searchKeys={['name', 'description', 'category']}
                    placeholder={trans('messages.datatable.search_placeholder')}
                />

                <Paginator
                    currentPage={sites.current_page}
                    lastPage={sites.last_page}
                    total={sites.total}
                    prevUrl={sites.prev_page_url}
                    nextUrl={sites.next_page_url}
                    links={sites.links}
                    onNavigate={go}
                />
            </PageWrapper>
        </AppLayout>
    );
}
