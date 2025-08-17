import { Button } from '@/components/ui/button';
import { ColumnDef } from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';

export interface BookingItem {
    id: string;
    guest_id?: string | null;
    site_id?: string | null;
    status: string;
    start_date: string | null;
    end_date: string | null;
    notes?: string | null;
}

export const bookingColumns: ColumnDef<BookingItem>[] = [
    {
        accessorKey: 'status',
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Status
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => row.original.status,
    },
    {
        id: 'start_date',
        accessorFn: (row) => (row.start_date ? new Date(row.start_date) : null),
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                Start
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => (row.original.start_date ? new Date(row.original.start_date).toLocaleDateString() : '-'),
    },
    {
        id: 'end_date',
        accessorFn: (row) => (row.end_date ? new Date(row.end_date) : null),
        header: ({ column }) => (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                End
                <ArrowUpDown className="ml-2 h-4 w-4" />
            </Button>
        ),
        cell: ({ row }) => (row.original.end_date ? new Date(row.original.end_date).toLocaleDateString() : '-'),
    },
    {
        accessorKey: 'notes',
        header: 'Notes',
        cell: ({ row }) => row.original.notes ?? '-',
        enableSorting: false,
    },
];
