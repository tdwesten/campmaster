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
            accessorKey: 'description',
            header: ({ column }) => trans('messages.site_categories.columns.description'),
            cell: ({ row }) => row.original.description || '-',
        },
    ];
}
