import { Button } from '@/components/ui/button';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';

export interface SiteItem {
    id: string;
    name: string;
    description: string | null;
}

export const siteColumns: ColumnDef<SiteItem>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Name
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => <span className="font-medium">{row.original.name}</span>,
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: ({ row }) => row.original.description ?? '-',
        enableSorting: false,
    },
];
