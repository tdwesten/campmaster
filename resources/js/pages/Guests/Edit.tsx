import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types';
import { Guest } from '@/types/models';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';

interface EditProps {
  guest: Guest;
}

export default function Edit({ guest }: EditProps) {
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
    {
      title: 'Edit',
      href: route('guests.edit', guest.id),
    },
  ];

  const form = useForm({
    first_name: guest.first_name,
    last_name: guest.last_name,
    email: guest.email || '',
    phone: guest.phone || '',
    address: guest.address || '',
    postal_code: guest.postal_code || '',
    city: guest.city || '',
    country: guest.country || '',
    date_of_birth: guest.date_of_birth || '',
    notes: guest.notes || '',
  });

  function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    form.put(route('guests.update', guest.id));
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title={`Edit Guest: ${guest.first_name} ${guest.last_name}`} />
      <div className="container py-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-bold">Edit Guest</h1>
          <div className="flex gap-2">
            <Link href={route('guests.index')}>
              <Button variant="outline">Back to Guests</Button>
            </Link>
            <Link href={route('guests.show', guest.id)}>
              <Button variant="outline">View Guest</Button>
            </Link>
          </div>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Guest Information</CardTitle>
            <CardDescription>
              Update the details of {guest.first_name} {guest.last_name}.
            </CardDescription>
          </CardHeader>
          <form onSubmit={onSubmit}>
            <CardContent className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Personal Information */}
                <div className="space-y-4">
                  <div>
                    <Label htmlFor="first_name" className="text-base">
                      First Name *
                    </Label>
                    <Input
                      id="first_name"
                      value={form.data.first_name}
                      onChange={(e) => form.setData('first_name', e.target.value)}
                      className="mt-1"
                      required
                    />
                    {form.errors.first_name && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.first_name}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="last_name" className="text-base">
                      Last Name *
                    </Label>
                    <Input
                      id="last_name"
                      value={form.data.last_name}
                      onChange={(e) => form.setData('last_name', e.target.value)}
                      className="mt-1"
                      required
                    />
                    {form.errors.last_name && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.last_name}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="date_of_birth" className="text-base">
                      Date of Birth
                    </Label>
                    <Input
                      id="date_of_birth"
                      type="date"
                      value={form.data.date_of_birth}
                      onChange={(e) => form.setData('date_of_birth', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.date_of_birth && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.date_of_birth}
                      </div>
                    )}
                  </div>
                </div>

                {/* Contact Information */}
                <div className="space-y-4">
                  <div>
                    <Label htmlFor="email" className="text-base">
                      Email
                    </Label>
                    <Input
                      id="email"
                      type="email"
                      value={form.data.email}
                      onChange={(e) => form.setData('email', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.email && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.email}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="phone" className="text-base">
                      Phone
                    </Label>
                    <Input
                      id="phone"
                      value={form.data.phone}
                      onChange={(e) => form.setData('phone', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.phone && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.phone}
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* Address Information */}
              <div className="space-y-4">
                <h3 className="text-lg font-medium">Address</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <Label htmlFor="address" className="text-base">
                      Street Address
                    </Label>
                    <Input
                      id="address"
                      value={form.data.address}
                      onChange={(e) => form.setData('address', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.address && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.address}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="postal_code" className="text-base">
                      Postal Code
                    </Label>
                    <Input
                      id="postal_code"
                      value={form.data.postal_code}
                      onChange={(e) => form.setData('postal_code', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.postal_code && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.postal_code}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="city" className="text-base">
                      City
                    </Label>
                    <Input
                      id="city"
                      value={form.data.city}
                      onChange={(e) => form.setData('city', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.city && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.city}
                      </div>
                    )}
                  </div>

                  <div>
                    <Label htmlFor="country" className="text-base">
                      Country
                    </Label>
                    <Input
                      id="country"
                      value={form.data.country}
                      onChange={(e) => form.setData('country', e.target.value)}
                      className="mt-1"
                    />
                    {form.errors.country && (
                      <div className="text-red-500 text-sm mt-1">
                        {form.errors.country}
                      </div>
                    )}
                  </div>
                </div>
              </div>

              {/* Notes */}
              <div>
                <Label htmlFor="notes" className="text-base">
                  Notes
                </Label>
                <Textarea
                  id="notes"
                  value={form.data.notes}
                  onChange={(e) => form.setData('notes', e.target.value)}
                  className="mt-1"
                  rows={4}
                />
                {form.errors.notes && (
                  <div className="text-red-500 text-sm mt-1">
                    {form.errors.notes}
                  </div>
                )}
              </div>
            </CardContent>
            <CardFooter className="flex justify-between">
              <Link href={route('guests.show', guest.id)}>
                <Button variant="outline" type="button">
                  Cancel
                </Button>
              </Link>
              <Button type="submit" disabled={form.processing}>
                {form.processing ? 'Saving...' : 'Update Guest'}
              </Button>
            </CardFooter>
          </form>
        </Card>
      </div>
    </AppLayout>
  );
}
