import DataTable from '@/components/data-table/data-table';
import { PageWrapper } from '@/components/page-wrapper';
import Paginator from '@/components/paginator';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Head, Link, router } from '@inertiajs/react';
import { siteCategoryColumns, type CategoryItem } from './columns';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface CategoriesPageProps {
    categories: {
        data: CategoryItem[];
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

export default function SiteCategoriesIndex({ categories, actions }: CategoriesPageProps) {
    const { trans } = useLingua();

    const breadcrumbs = [{ title: trans('messages.site_categories.breadcrumb'), href: '/site-categories' }];

    function go(url: string | null) {
        if (!url) return;
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={trans('messages.site_categories.title')} />
            <PageWrapper
                title={trans('messages.site_categories.title')}
                subtitle={trans('messages.site_categories.subtitle')}
                actions={
                    <Link href={actions?.create_url ?? route('site-categories.create')}>
                        <Button>{trans('messages.site_categories.actions.create')}</Button>
                    </Link>
                }
            >
                <DataTable<CategoryItem, unknown>
                    columns={siteCategoryColumns(trans)}
                    data={categories.data}
                    searchKeys={['name', 'slug']}
                    placeholder={trans('messages.datatable.search_placeholder')}
                />

                <Paginator
                    currentPage={categories.current_page}
                    lastPage={categories.last_page}
                    total={categories.total}
                    prevUrl={categories.prev_page_url}
                    nextUrl={categories.next_page_url}
                    links={categories.links}
                    onNavigate={go}
                />
            </PageWrapper>
        </AppLayout>
    );
}
