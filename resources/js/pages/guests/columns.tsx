import { Button } from '@/components/ui/button';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';
import { Link } from '@inertiajs/react';

export interface GuestItem {
    id: string;
    firstname: string;
    lastname: string;
    email: string;
    city: string | null;
    created_at: string | null;
}

export function guestColumns(trans: (key: string) => string): ColumnDef<GuestItem>[] {
    return [
        {
            id: 'name',
            accessorFn: (row) => `${row.firstname} ${row.lastname}`.trim(),
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.guests.columns.name')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row, getValue }) => (
                <Link href={route('guests.edit', row.original.id)} className="font-medium hover:underline">
                    {String(getValue())}
                </Link>
            ),
            enableHiding: false,
        },
        {
            accessorKey: 'email',
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.guests.columns.email')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => row.original.email,
        },
        {
            accessorKey: 'city',
            header: ({ column }) => (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    {trans('messages.guests.columns.city')}
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
                    {trans('messages.guests.columns.added')}
                    <ArrowUpDown className="ml-2 h-4 w-4" />
                </Button>
            ),
            cell: ({ row }) => (row.original.created_at ? new Date(row.original.created_at).toLocaleString() : '-'),
        },
    ];
}
