import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';

export interface CategoryItem {
    id: string;
    name: string;
    description: string | null;
    slug: string;
    sites_count: number;
    created_at?: string | null;
}

export function siteCategoryColumns(trans: (key: string) => string): ColumnDef<CategoryItem>[] {
    return [
        {
            accessorKey: 'name',
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.site_categories.columns.name')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => (
                <Link href={route('site-categories.edit', row.original.id)} className="font-medium hover:underline">
                    {row.original.name}
                </Link>
            ),
            enableHiding: false,
        },
        {
            accessorKey: 'slug',
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.site_categories.columns.slug')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => row.original.slug,
        },
        {
            accessorKey: 'sites_count',
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.site_categories.columns.sites')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => row.original.sites_count,
        },
        {
            id: 'created_at',
            accessorFn: (row) => (row.created_at ? new Date(row.created_at) : null),
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.site_categories.columns.added')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => (row.original.created_at ? new Date(row.original.created_at).toLocaleString() : '-'),
        },
    ];
}
