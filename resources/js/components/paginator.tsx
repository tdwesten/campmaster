import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { cn } from '@/lib/utils';
import useLingua from '@cyberwolf.studio/lingua-react';
import React from 'react';

export interface PaginationLinkItem {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatorProps {
    currentPage: number;
    lastPage: number;
    total: number;
    prevUrl: string | null;
    nextUrl: string | null;
    links?: PaginationLinkItem[]; // Laravel-style links array; if provided, will be used for page numbers
    onNavigate: (url: string | null) => void;
    className?: string;
}

export default function Paginator({
    currentPage,
    lastPage,
    total,
    prevUrl,
    nextUrl,
    links,
    onNavigate,
    className,
}: PaginatorProps): React.ReactElement {
    const { trans } = useLingua();

    const pageLinks = React.useMemo(() => {
        if (links && links.length > 0) {
            // Laravel includes first("Previous") and last("Next") items. We only want the middle numeric/ellipsis items.
            return links.slice(1, -1);
        }
        // Fallback: generate a compact set of pagination links around the current page.
        const items: PaginationLinkItem[] = [];
        const add = (n: number) => items.push({ url: `?page=${n}` /* fallback query param */, label: String(n), active: n === currentPage });

        const window = 1; // neighbors on each side
        const pagesToShow = new Set<number>();
        pagesToShow.add(1);
        pagesToShow.add(lastPage);
        for (let i = currentPage - window; i <= currentPage + window; i++) {
            if (i >= 1 && i <= lastPage) {
                pagesToShow.add(i);
            }
        }
        const sorted = Array.from(pagesToShow).sort((a, b) => a - b);
        let prev = 0;
        for (const p of sorted) {
            if (prev !== 0 && p - prev > 1) {
                items.push({ url: null, label: '…', active: false });
            }
            add(p);
            prev = p;
        }
        return items;
    }, [links, currentPage, lastPage]);

    function handleClick(e: React.MouseEvent, url: string | null) {
        e.preventDefault();
        if (!url) {
            return;
        }
        onNavigate(url);
    }

    const previousLabel = trans('messages.pagination.previous');
    const nextLabel = trans('messages.pagination.next');

    return (
        <div className={cn('mt-4 flex items-center justify-between text-sm', className)}>
            <div className="flex-shrink-0 text-gray-600">
                {trans('messages.pagination.page')} {currentPage} {trans('messages.pagination.of')} {lastPage} · {total}{' '}
                {trans('messages.pagination.total')}
            </div>
            <Pagination className="justify-end">
                <PaginationContent>
                    <PaginationItem>
                        <PaginationPrevious
                            href={prevUrl ?? '#'}
                            onClick={(e) => handleClick(e, prevUrl)}
                            aria-disabled={!prevUrl}
                            data-disabled={!prevUrl}
                            className={cn(!prevUrl && 'pointer-events-none opacity-50')}
                            label={previousLabel}
                        />
                    </PaginationItem>

                    {pageLinks.map((l, idx) => (
                        <PaginationItem key={`${l.label}-${idx}`}>
                            {l.url ? (
                                <PaginationLink
                                    href={l.url ?? '#'}
                                    isActive={l.active}
                                    onClick={(e) => handleClick(e, l.url)}
                                    aria-label={`${trans('messages.pagination.page')} ${l.label}`}
                                >
                                    {l.label}
                                </PaginationLink>
                            ) : (
                                <PaginationEllipsis />
                            )}
                        </PaginationItem>
                    ))}

                    <PaginationItem>
                        <PaginationNext
                            href={nextUrl ?? '#'}
                            onClick={(e) => handleClick(e, nextUrl)}
                            aria-disabled={!nextUrl}
                            data-disabled={!nextUrl}
                            className={cn(!nextUrl && 'pointer-events-none opacity-50')}
                            label={nextLabel}
                        />
                    </PaginationItem>
                </PaginationContent>
            </Pagination>
        </div>
    );
}
