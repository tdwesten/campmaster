import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, PageProps } from '@/types';
import { Guest } from '@/types/models';
import { Head, Link, router } from '@inertiajs/react';
import React, { useState } from 'react';

interface GuestsIndexProps extends PageProps {
    guests: {
        data: Guest[];
        links: {
            first: string;
            last: string;
            prev: string | null;
            next: string | null;
        };
        meta: {
            current_page: number;
            from: number;
            last_page: number;
            links: {
                url: string | null;
                label: string;
                active: boolean;
            }[];
            path: string;
            per_page: number;
            to: number;
            total: number;
        };
    };
    filters: {
        search: string;
        sort_field: string;
        sort_direction: string;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/',
    },
    {
        title: 'Guests',
        href: '/guests',
    },
];

export default function Index({ guests, filters = {} }: GuestsIndexProps) {
    const [search, setSearch] = useState(filters?.search || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('guests.index'), { search }, { preserveState: true, replace: true });
    };

    const sortBy = (field: string) => {
        const direction = filters?.sort_field === field && filters?.sort_direction === 'asc' ? 'desc' : 'asc';
        router.get(
            route('guests.index'),
            {
                ...filters,
                sort_field: field,
                sort_direction: direction,
            },
            { preserveState: true, replace: true },
        );
    };

    const getSortIcon = (field: string) => {
        if (filters?.sort_field !== field) return null;
        return filters?.sort_direction === 'asc' ? '↑' : '↓';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Guests" />
            <div className="container py-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold">Guests</h1>
                    <Link href={route('guests.create')}>
                        <Button>Add Guest</Button>
                    </Link>
                </div>

                <div className="mb-6">
                    <form onSubmit={handleSearch} className="flex gap-2">
                        <Input
                            type="text"
                            placeholder="Search guests..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="max-w-sm"
                        />
                        <Button type="submit">Search</Button>
                    </form>
                </div>

                <div className="overflow-hidden rounded-md bg-white shadow">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="cursor-pointer" onClick={() => sortBy('last_name')}>
                                    Name {getSortIcon('last_name')}
                                </TableHead>
                                <TableHead className="cursor-pointer" onClick={() => sortBy('email')}>
                                    Email {getSortIcon('email')}
                                </TableHead>
                                <TableHead className="cursor-pointer" onClick={() => sortBy('phone')}>
                                    Phone {getSortIcon('phone')}
                                </TableHead>
                                <TableHead className="cursor-pointer" onClick={() => sortBy('city')}>
                                    City {getSortIcon('city')}
                                </TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {!guests.data || guests.data.length === 0 ? (
                                <TableRow>
                                    <TableCell colSpan={5} className="py-4 text-center">
                                        No guests found.
                                    </TableCell>
                                </TableRow>
                            ) : (
                                guests.data.map((guest) => (
                                    <TableRow key={guest.id}>
                                        <TableCell>
                                            <Link href={route('guests.show', guest.id)} className="text-blue-600 hover:underline">
                                                {guest.first_name} {guest.last_name}
                                            </Link>
                                        </TableCell>
                                        <TableCell>{guest.email}</TableCell>
                                        <TableCell>{guest.phone}</TableCell>
                                        <TableCell>{guest.city}</TableCell>
                                        <TableCell>
                                            <div className="flex gap-2">
                                                <Link href={route('guests.edit', guest.id)}>
                                                    <Button variant="outline" size="sm">
                                                        Edit
                                                    </Button>
                                                </Link>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    onClick={() => {
                                                        if (confirm('Are you sure you want to delete this guest?')) {
                                                            router.delete(route('guests.destroy', guest.id));
                                                        }
                                                    }}
                                                >
                                                    Delete
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </div>

                {guests.meta && (
                    <Pagination className="mt-6">
                        <PaginationContent>
                            {guests.meta.current_page > 1 && (
                                <PaginationItem>
                                    <PaginationPrevious
                                        href={guests.links.prev || '#'}
                                        onClick={(e) => {
                                            if (guests.links.prev) {
                                                e.preventDefault();
                                                router.get(guests.links.prev);
                                            }
                                        }}
                                    />
                                </PaginationItem>
                            )}

                            {guests.meta.links.slice(1, -1).map((link, i) => (
                                <PaginationItem key={i}>
                                    {link.url ? (
                                        <PaginationLink
                                            href={link.url}
                                            isActive={link.active}
                                            onClick={(e) => {
                                                e.preventDefault();
                                                router.get(link.url as string);
                                            }}
                                        >
                                            {link.label}
                                        </PaginationLink>
                                    ) : (
                                        <PaginationEllipsis />
                                    )}
                                </PaginationItem>
                            ))}

                            {guests.meta.current_page < guests.meta.last_page && (
                                <PaginationItem>
                                    <PaginationNext
                                        href={guests.links.next || '#'}
                                        onClick={(e) => {
                                            if (guests.links.next) {
                                                e.preventDefault();
                                                router.get(guests.links.next);
                                            }
                                        }}
                                    />
                                </PaginationItem>
                            )}
                        </PaginationContent>
                    </Pagination>
                )}
            </div>
        </AppLayout>
    );
}
