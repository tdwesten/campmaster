import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { cn } from '@/lib/utils';
import type { Booking } from '@/types/timeline';
import * as React from 'react';

export type BookingBarProps = {
    booking: Booking;
    left: number; // px
    width: number; // px
    top: number; // px (relative to grid body)
    height: number; // px
    onClick?: (b: Booking) => void;
};

export function BookingBar({ booking, left, width, top, height, onClick }: BookingBarProps) {
    const variant = booking.variant ?? 'confirmed';
    const style: React.CSSProperties = {
        left,
        width,
        top: top + 8, // small vertical padding inside row
        height: Math.max(28, height - 16),
    };

    const diagonalStyle: React.CSSProperties =
        variant === 'blocked'
            ? {
                  backgroundImage: 'repeating-linear-gradient(135deg, rgb(203,213,225) 0 8px, transparent 8px 16px)',
              }
            : {};

    const classes = cn(
        'focus-visible:ring-ring/50 absolute flex cursor-grab items-center gap-2 rounded-md px-2 outline-none select-none focus-visible:ring-2',
        variant === 'confirmed' && 'bg-emerald-500 text-white hover:bg-emerald-600',
        variant === 'tentative' && 'border border-emerald-300 bg-emerald-200 text-emerald-900',
        variant === 'blocked' && 'bg-slate-100 text-slate-600',
        variant === 'group' && 'bg-emerald-300 text-emerald-950',
    );

    const label = booking.name;

    const content = (
        <button
            data-component="BookingBar"
            type="button"
            aria-label={`Booking ${label}, from ${booking.start.toDateString()} to ${booking.end.toDateString()}`}
            className={classes}
            style={Object.assign({}, style, diagonalStyle)}
            onClick={() => onClick?.(booking)}
        >
            <span className="truncate text-sm" aria-hidden>
                {label}
            </span>
            {/* drag handles placeholders */}
            <span className="ml-auto flex items-center gap-1 text-xs opacity-70">
                <span className="cursor-ew-resize" aria-hidden>
                    ⋮
                </span>
                <span className="cursor-ew-resize" aria-hidden>
                    ⋮
                </span>
            </span>
        </button>
    );

    return (
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger asChild>{content}</TooltipTrigger>
                <TooltipContent>
                    <div className="text-sm" data-component="BookingBarTooltip">
                        <div className="font-medium">{label}</div>
                        <div>
                            {booking.start.toDateString()} – {booking.end.toDateString()}
                        </div>
                    </div>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    );
}

export default BookingBar;
