import { useEffect, useMemo, useState } from 'react';

export type VirtualRowsOptions = {
    count: number;
    rowHeight: number;
    overscan?: number;
    scrollElement?: HTMLElement | null;
};

export type VirtualRow = {
    index: number;
    start: number; // y offset
    size: number;
};

export type UseVirtualRowsReturn = {
    totalSize: number;
    startIndex: number;
    endIndex: number;
    paddingTop: number;
    paddingBottom: number;
    rows: VirtualRow[];
};

export function useVirtualRows({ count, rowHeight, overscan = 5, scrollElement }: VirtualRowsOptions): UseVirtualRowsReturn {
    const [viewportHeight, setViewportHeight] = useState<number>(600);
    const [scrollTop, setScrollTop] = useState<number>(0);

    useEffect(() => {
        if (!scrollElement) return;

        const onScroll = () => setScrollTop(scrollElement.scrollTop);
        const onResize = () => setViewportHeight(scrollElement.clientHeight || 600);

        onResize();
        onScroll();

        scrollElement.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onResize);

        return () => {
            scrollElement.removeEventListener('scroll', onScroll);
            window.removeEventListener('resize', onResize);
        };
    }, [scrollElement]);

    const totalSize = useMemo(() => count * rowHeight, [count, rowHeight]);

    const startIndex = Math.max(0, Math.floor(scrollTop / rowHeight) - overscan);
    const endIndex = Math.min(count - 1, Math.ceil((scrollTop + viewportHeight) / rowHeight) + overscan);

    const paddingTop = startIndex * rowHeight;
    const paddingBottom = Math.max(0, totalSize - (endIndex + 1) * rowHeight);

    const rows: VirtualRow[] = useMemo(() => {
        const r: VirtualRow[] = [];
        for (let i = startIndex; i <= endIndex; i++) {
            r.push({ index: i, start: i * rowHeight, size: rowHeight });
        }
        return r;
    }, [endIndex, rowHeight, startIndex]);

    return { totalSize, startIndex, endIndex, paddingTop, paddingBottom, rows };
}
