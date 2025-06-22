import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Guest } from '@/types/models';
import { Button } from '@/components/ui/button';
import { format } from 'date-fns';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';

interface GuestShowProps extends PageProps {
  guest: Guest;
}

export default function Show({ guest }: GuestShowProps) {
  return (
    <>
      <Head title={`Guest: ${guest.first_name} ${guest.last_name}`} />
      <div className="container py-8">
        <div className="flex justify-between items-center mb-6">
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
            <CardTitle>{guest.first_name} {guest.last_name}</CardTitle>
            <CardDescription>Guest Information</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 className="text-lg font-medium mb-2">Contact Information</h3>
                <div className="space-y-2">
                  <div>
                    <span className="font-medium">Email:</span>{' '}
                    {guest.email || 'Not provided'}
                  </div>
                  <div>
                    <span className="font-medium">Phone:</span>{' '}
                    {guest.phone || 'Not provided'}
                  </div>
                </div>
              </div>

              <div>
                <h3 className="text-lg font-medium mb-2">Personal Information</h3>
                <div className="space-y-2">
                  <div>
                    <span className="font-medium">Date of Birth:</span>{' '}
                    {guest.date_of_birth
                      ? format(new Date(guest.date_of_birth), 'MMMM d, yyyy')
                      : 'Not provided'}
                  </div>
                </div>
              </div>

              <div className="md:col-span-2">
                <h3 className="text-lg font-medium mb-2">Address</h3>
                <div className="space-y-2">
                  <div>
                    <span className="font-medium">Street:</span>{' '}
                    {guest.address || 'Not provided'}
                  </div>
                  <div>
                    <span className="font-medium">Postal Code:</span>{' '}
                    {guest.postal_code || 'Not provided'}
                  </div>
                  <div>
                    <span className="font-medium">City:</span>{' '}
                    {guest.city || 'Not provided'}
                  </div>
                  <div>
                    <span className="font-medium">Country:</span>{' '}
                    {guest.country || 'Not provided'}
                  </div>
                </div>
              </div>

              {guest.notes && (
                <div className="md:col-span-2">
                  <h3 className="text-lg font-medium mb-2">Notes</h3>
                  <div className="p-4 bg-gray-50 rounded-md">
                    {guest.notes}
                  </div>
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
    </>
  );
}
