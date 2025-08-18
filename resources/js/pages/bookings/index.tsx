import { AppArchive } from '@/components/app-archive';
import Timeline from '@/components/timeline/Timeline';
import AppLayout from '@/layouts/app-layout';
import type { Booking, Resource } from '@/types/timeline';
import { Head } from '@inertiajs/react';
import { addDays } from 'date-fns';
import { Home, Plug, Tent, Trees } from 'lucide-react';

export default function BookingsIndex() {
    const breadcrumbs = [
        {
            title: 'bookings',
            href: '/bookings',
        },
    ];

    // Mock resources and bookings until backend wiring is ready
    const resources: Resource[] = [
        { id: 'r1', name: 'Site 1', groupId: 'g1', groupName: 'RV Sites', amenities: '30A • 40ft • 6 max', icon: Plug },
        { id: 'r2', name: 'Site 2', groupId: 'g1', groupName: 'RV Sites', amenities: '50A • 45ft • 6 max', icon: Plug },
        { id: 'r3', name: 'Site 3', groupId: 'g1', groupName: 'RV Sites', amenities: '30A • 35ft • 6 max', icon: Plug },
        { id: 'r4', name: 'Site 4', groupId: 'g1', groupName: 'RV Sites', amenities: '50A • 45ft • 6 max', icon: Plug },
        { id: 'r5', name: 'Tent A', groupId: 'g2', groupName: 'Tent Sites', amenities: 'No power • Fire ring', icon: Tent },
        { id: 'r6', name: 'Tent B', groupId: 'g2', groupName: 'Tent Sites', amenities: 'No power • Fire ring', icon: Tent },
        { id: 'r7', name: 'Cabin 1', groupId: 'g3', groupName: 'Cabins', amenities: '2BR • Kitchenette', icon: Home },
        { id: 'r8', name: 'Cabin 2', groupId: 'g3', groupName: 'Cabins', amenities: '1BR • Kitchenette', icon: Home },
        { id: 'r9', name: 'Group Site', groupId: 'g4', groupName: 'Group', amenities: 'Large area • Pavilion', icon: Trees },
        { id: 'r10', name: 'Premium 1', groupId: 'g5', groupName: 'Premium', amenities: 'Waterfront • 50A', icon: Trees },
    ];

    const anchor = new Date();
    const mk = (
        id: string,
        resourceId: string,
        startOffset: number,
        nights: number,
        name: string,
        variant: Booking['variant'] = 'confirmed',
    ): Booking => {
        const start = addDays(anchor, startOffset);
        const end = addDays(start, Math.max(0, nights - 1));
        return { id, resourceId, start, end, name, variant };
    };

    const bookings: Booking[] = [
        mk('b1', 'r1', 1, 3, 'Smith Family'),
        mk('b2', 'r2', 5, 5, 'Johnson'),
        mk('b3', 'r3', -2, 2, 'Tentative Miller', 'tentative'),
        mk('b4', 'r4', 8, 7, 'Anderson'),
        mk('b5', 'r5', 0, 2, 'Campfire Night', 'group'),
        mk('b6', 'r6', 12, 3, 'Nguyen'),
        mk('b7', 'r7', 3, 4, 'Cabin - Lee'),
        mk('b8', 'r8', 10, 6, 'Cabin - Patel'),
        mk('b9', 'r9', 6, 10, 'Maintenance', 'blocked'),
        mk('b10', 'r10', -5, 4, 'Premium - Garcia'),
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Bookings" />

            <AppArchive title="Bookings" subtitle="Manage your bookings and reservations" fullWidth={true}>
                <div className="space-y-4 overflow-hidden">
                    <Timeline
                        resources={resources}
                        bookings={bookings}
                        anchorDate={anchor}
                        defaultView="monthly"
                        onCreate={(resourceId, start, end) => {
                            // Placeholder create action; wire to backend later
                            console.log('Create reservation', { resourceId, start, end });
                        }}
                        liveUpdates={false}
                    />
                </div>
            </AppArchive>
        </AppLayout>
    );
}
