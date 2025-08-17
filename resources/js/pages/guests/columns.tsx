import { Button } from '@/components/ui/button';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';

export interface GuestItem {
    id: string;
    firstname: string;
    lastname: string;
    email: string;
    city: string | null;
    created_at: string | null;
}

export const guestColumns: ColumnDef<GuestItem>[] = [
    {
        id: 'name',
        accessorFn: (row) => `${row.firstname} ${row.lastname}`.trim(),
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Name
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ getValue }) => <span className="font-medium">{String(getValue())}</span>,
        enableHiding: false,
    },
    {
        accessorKey: 'email',
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Email
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => row.original.email,
    },
    {
        accessorKey: 'city',
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                City
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => row.original.city ?? '-',
    },
    {
        id: 'created_at',
        accessorFn: (row) => (row.created_at ? new Date(row.created_at) : null),
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Added
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => (row.original.created_at ? new Date(row.original.created_at).toLocaleString() : '-'),
    },
];
