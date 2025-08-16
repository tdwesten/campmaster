import { AppArchive } from '@/components/app-archive';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';

interface GuestItem {
    id: string;
    firstname: string;
    lastname: string;
    email: string;
    city: string | null;
    created_at: string | null;
}

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
    const breadcrumbs = [
        {
            title: 'guests',
            href: '/guests',
        },
    ];

    function go(url: string | null) {
        if (!url) return;
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Guests" />

            <AppArchive title="Guests" subtitle="Browse and manage your guests">
                <div className="overflow-hidden rounded-xl border border-gray-200">
                    <table className="w-full table-auto">
                        <thead className="bg-gray-50">
                            <tr className="text-left text-sm text-gray-600">
                                <th className="px-4 py-3">Name</th>
                                <th className="px-4 py-3">Email</th>
                                <th className="px-4 py-3">City</th>
                                <th className="px-4 py-3">Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            {guests.data.length === 0 && (
                                <tr>
                                    <td className="px-4 py-6 text-center text-sm text-gray-500" colSpan={4}>
                                        No guests found.
                                    </td>
                                </tr>
                            )}
                            {guests.data.map((g) => (
                                <tr key={g.id} className="border-t text-sm">
                                    <td className="px-4 py-3 font-medium">
                                        {g.firstname} {g.lastname}
                                    </td>
                                    <td className="px-4 py-3">{g.email}</td>
                                    <td className="px-4 py-3">{g.city ?? '-'}</td>
                                    <td className="px-4 py-3">{g.created_at ? new Date(g.created_at).toLocaleString() : '-'}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="mt-4 flex items-center justify-between text-sm">
                    <div className="text-gray-600">
                        Page {guests.current_page} of {guests.last_page} · {guests.total} total
                    </div>
                    <div className="flex gap-2">
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.prev_page_url)}
                            disabled={!guests.prev_page_url}
                        >
                            Previous
                        </button>
                        <button
                            type="button"
                            className="rounded-md border px-3 py-1 disabled:opacity-50"
                            onClick={() => go(guests.next_page_url)}
                            disabled={!guests.next_page_url}
                        >
                            Next
                        </button>
                    </div>
                </div>
            </AppArchive>
        </AppLayout>
    );
}
