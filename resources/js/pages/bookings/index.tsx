import { AppArchive } from '@/components/app-archive';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

export default function index({}) {
    const breadcrumbs = [
        {
            title: 'bookings',
            href: '/bookings',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings" />

            <AppArchive title="Bookings" subtitle="Manage your bookings and reservations">
                <>table</>
            </AppArchive>
        </AppLayout>
    );
}
