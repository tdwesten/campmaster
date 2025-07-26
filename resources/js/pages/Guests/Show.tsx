import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, PageProps } from '@/types';
import { Guest } from '@/types/models';
import { Head, Link } from '@inertiajs/react';
import { format } from 'date-fns';

interface GuestShowProps extends PageProps {
    guest: Guest;
}

export default function Show({ guest }: GuestShowProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/',
        },
        {
            title: 'Guests',
            href: '/guests',
        },
        {
            title: `${guest.first_name} ${guest.last_name}`,
            href: route('guests.show', guest.id),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Guest: ${guest.first_name} ${guest.last_name}`} />
            <div className="container py-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold">Guest Details</h1>
                    <div className="flex gap-2">
                        <Link href={route('guests.index')}>
                            <Button variant="outline">Back to Guests</Button>
                        </Link>
                        <Link href={route('guests.edit', guest.id)}>
                            <Button>Edit Guest</Button>
                        </Link>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>
                            {guest.first_name} {guest.last_name}
                        </CardTitle>
                        <CardDescription>Guest Information</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h3 className="mb-2 text-lg font-medium">Contact Information</h3>
                                <div className="space-y-2">
                                    <div>
                                        <span className="font-medium">Email:</span> {guest.email || 'Not provided'}
                                    </div>
                                    <div>
                                        <span className="font-medium">Phone:</span> {guest.phone || 'Not provided'}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 className="mb-2 text-lg font-medium">Personal Information</h3>
                                <div className="space-y-2">
                                    <div>
                                        <span className="font-medium">Date of Birth:</span>{' '}
                                        {guest.date_of_birth ? format(new Date(guest.date_of_birth), 'MMMM d, yyyy') : 'Not provided'}
                                    </div>
                                </div>
                            </div>

                            <div className="md:col-span-2">
                                <h3 className="mb-2 text-lg font-medium">Address</h3>
                                <div className="space-y-2">
                                    <div>
                                        <span className="font-medium">Street:</span> {guest.address || 'Not provided'}
                                    </div>
                                    <div>
                                        <span className="font-medium">Postal Code:</span> {guest.postal_code || 'Not provided'}
                                    </div>
                                    <div>
                                        <span className="font-medium">City:</span> {guest.city || 'Not provided'}
                                    </div>
                                    <div>
                                        <span className="font-medium">Country:</span> {guest.country || 'Not provided'}
                                    </div>
                                </div>
                            </div>

                            {guest.notes && (
                                <div className="md:col-span-2">
                                    <h3 className="mb-2 text-lg font-medium">Notes</h3>
                                    <div className="rounded-md bg-gray-50 p-4">{guest.notes}</div>
                                </div>
                            )}
                        </div>
                    </CardContent>
                    <CardFooter className="flex justify-between">
                        <Link href={route('guests.index')}>
                            <Button variant="outline">Back to Guests</Button>
                        </Link>
                        <Link href={route('guests.edit', guest.id)}>
                            <Button>Edit Guest</Button>
                        </Link>
                    </CardFooter>
                </Card>
            </div>
        </AppLayout>
    );
}
