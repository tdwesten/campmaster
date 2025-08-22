import type { Booking, Resource, ViewMode } from '@/types/timeline';
import {
    addDays,
    addMonths,
    addQuarters,
    addWeeks,
    differenceInCalendarDays,
    eachDayOfInterval,
    endOfQuarter,
    format,
    isAfter,
    isBefore,
    isWithinInterval,
    startOfQuarter,
    startOfWeek,
} from 'date-fns';
import { useCallback, useMemo, useRef, useState } from 'react';

export type LayoutConfig = {
    cellWidth: number; // px for a column
    rowHeight: number; // px for a resource row
};

export type ComputedRange = {
    start: Date;
    end: Date;
    days: Date[]; // monthly view days
    weeks: Date[]; // weekly anchors (start of week) for quarterly
};

export type LayoutMaps = {
    xFromDate: (d: Date) => number;
    widthFromDates: (start: Date, end: Date) => number;
    colCount: number;
    labelForCol: (index: number) => string;
    monthLabelForCol: (index: number) => string;
};

export type ScrollSyncRefs = {
    headerRef: React.RefObject<HTMLDivElement | null>;
    bodyRef: React.RefObject<HTMLDivElement | null>;
    sidebarRef: React.RefObject<HTMLDivElement | null>;
};

export type UseTimelineLayoutReturn = {
    config: LayoutConfig;
    range: ComputedRange;
    maps: LayoutMaps;
    todayOffset: number | null; // x position in px of the today line relative to left grid edge
    resourceIndex: Map<string, number>;
    scrollRefs: ScrollSyncRefs;
    onBodyScroll: () => void;
    shiftPrev: () => void;
    shiftNext: () => void;
    setAnchorDate: (d: Date) => void;
    anchorDate: Date;
};

const ROW_HEIGHT = 56;
const DAY_CELL_WIDTH = 56;
const WEEK_CELL_WIDTH = 64;

export function useTimelineLayout(
    resources: Resource[],
    bookings: Booking[],
    initialDate: Date,
    viewMode: ViewMode,
    onViewDateChange?: (d: Date) => void,
): UseTimelineLayoutReturn {
    const [anchorDate, setAnchorDateState] = useState<Date>(initialDate);
    const setAnchorDate = useCallback(
        (d: Date) => {
            setAnchorDateState(d);
            onViewDateChange?.(d);
        },
        [onViewDateChange],
    );

    const config = useMemo<LayoutConfig>(() => {
        return {
            cellWidth: viewMode === 'monthly' ? DAY_CELL_WIDTH : WEEK_CELL_WIDTH,
            rowHeight: ROW_HEIGHT,
        };
    }, [viewMode]);

    const range = useMemo<ComputedRange>(() => {
        if (viewMode === 'monthly') {
            // Show upcoming 2 months starting at the beginning of the current week (ISO week start Monday)
            const start = startOfWeek(anchorDate, { weekStartsOn: 1 });
            // End at exactly two months from the start minus 1 day to keep an inclusive range
            const inclusiveEnd = addDays(addMonths(start, 2), -1);
            const days = eachDayOfInterval({ start, end: inclusiveEnd });
            return { start, end: inclusiveEnd, days, weeks: [] };
        } else {
            const start = startOfQuarter(anchorDate);
            const end = endOfQuarter(anchorDate);
            // Build week anchors (start of each week within the quarter)
            const firstWeek = startOfWeek(start, { weekStartsOn: 1 });
            const weeks: Date[] = [];
            let w = firstWeek;
            while (!isAfter(w, end)) {
                weeks.push(w);
                w = addWeeks(w, 1);
            }
            return { start, end, days: [], weeks };
        }
    }, [anchorDate, viewMode]);

    const maps = useMemo<LayoutMaps>(() => {
        if (viewMode === 'monthly') {
            return {
                xFromDate: (d: Date) => {
                    const i = Math.max(0, Math.min(differenceInCalendarDays(d, range.start), differenceInCalendarDays(range.end, range.start)));
                    return i * config.cellWidth;
                },
                widthFromDates: (s: Date, e: Date) => {
                    const clampedStart = isBefore(s, range.start) ? range.start : s;
                    const clampedEnd = isAfter(e, range.end) ? range.end : e;
                    const span = differenceInCalendarDays(clampedEnd, clampedStart) + 1;
                    return span * config.cellWidth;
                },
                colCount: range.days.length,
                labelForCol: (index: number) => format(range.days[index]!, 'd'),
                monthLabelForCol: (index: number) => format(range.days[index]!, 'LLL'),
            };
        }

        return {
            xFromDate: (d: Date) => {
                // position within the week fractionally
                const first = range.weeks[0]!;
                const weekIndex = Math.max(0, Math.floor(differenceInCalendarDays(d, first) / 7));
                // fraction within week for today line precision
                const dayOfWeek = differenceInCalendarDays(d, addWeeks(first, weekIndex)) % 7;
                const frac = Math.max(0, Math.min(6, dayOfWeek)) / 7;
                return (weekIndex + frac) * config.cellWidth;
            },
            widthFromDates: (s: Date, e: Date) => {
                const first = range.weeks[0]!;
                const startIdx = Math.floor(differenceInCalendarDays(s, first) / 7);
                const endIdx = Math.floor(differenceInCalendarDays(e, first) / 7);
                const span = endIdx - startIdx + 1;
                return Math.max(1, span) * config.cellWidth;
            },
            colCount: range.weeks.length,
            labelForCol: (index: number) => format(range.weeks[index]!, 'wo'),
            monthLabelForCol: (index: number) => format(addDays(range.weeks[index]!, 3), 'LLL'),
        };
    }, [config.cellWidth, range, viewMode]);

    const todayOffset = useMemo(() => {
        const today = new Date();
        if (!isWithinInterval(today, { start: range.start, end: range.end })) {
            return null;
        }
        return maps.xFromDate(today);
    }, [maps, range.end, range.start]);

    const resourceIndex = useMemo(() => {
        const map = new Map<string, number>();
        resources.forEach((r, i) => map.set(r.id, i));
        return map;
    }, [resources]);

    const headerRef = useRef<HTMLDivElement>(null);
    const bodyRef = useRef<HTMLDivElement>(null);
    const sidebarRef = useRef<HTMLDivElement>(null);

    const onBodyScroll = useCallback(() => {
        const body = bodyRef.current;
        const header = headerRef.current;
        const sidebar = sidebarRef.current;
        if (!body) return;
        if (header) header.scrollLeft = body.scrollLeft;
        if (sidebar) sidebar.scrollTop = body.scrollTop;
    }, []);

    const shiftPrev = useCallback(() => {
        setAnchorDate(viewMode === 'monthly' ? addMonths(anchorDate, -1) : addQuarters(anchorDate, -1));
    }, [anchorDate, setAnchorDate, viewMode]);

    const shiftNext = useCallback(() => {
        setAnchorDate(viewMode === 'monthly' ? addMonths(anchorDate, 1) : addQuarters(anchorDate, 1));
    }, [anchorDate, setAnchorDate, viewMode]);

    return {
        config,
        range,
        maps,
        todayOffset,
        resourceIndex,
        scrollRefs: { headerRef, bodyRef, sidebarRef },
        onBodyScroll,
        shiftPrev,
        shiftNext,
        setAnchorDate,
        anchorDate,
    };
}
