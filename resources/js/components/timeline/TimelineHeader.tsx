import { cn } from '@/lib/utils';
import * as React from 'react';

export type TimelineHeaderProps = {
    colCount: number;
    labelForCol: (index: number) => string;
    monthLabelForCol: (index: number) => string;
    cellWidth: number;
    headerRef: React.RefObject<HTMLDivElement | null>;
    todayOffset: number | null;
};

export function TimelineHeader({ colCount, labelForCol, monthLabelForCol, cellWidth, headerRef, todayOffset }: TimelineHeaderProps) {
    // Compute month spans
    const months: { label: string; startIndex: number; span: number }[] = React.useMemo(() => {
        const res: { label: string; startIndex: number; span: number }[] = [];
        let currentLabel: string | null = null;
        let currentStart = 0;
        for (let i = 0; i < colCount; i++) {
            const lbl = monthLabelForCol(i);
            if (currentLabel === null) {
                currentLabel = lbl;
                currentStart = i;
            } else if (lbl !== currentLabel) {
                res.push({ label: currentLabel, startIndex: currentStart, span: i - currentStart });
                currentLabel = lbl;
                currentStart = i;
            }
        }
        if (currentLabel !== null) {
            res.push({ label: currentLabel, startIndex: currentStart, span: colCount - currentStart });
        }
        return res;
    }, [colCount, monthLabelForCol]);

    const totalWidth = colCount * cellWidth;

    return (
        <div className="sticky top-0 z-30 w-full border-b border-slate-200/70 bg-white/90 shadow-[0_1px_0_0_rgba(0,0,0,0.02)] backdrop-blur">
            <div ref={headerRef} className="w-full overflow-x-auto overflow-y-hidden">
                <div className="relative" style={{ width: totalWidth }}>
                    {/* Month labels */}
                    <div className="relative h-8 border-b border-slate-200/70">
                        {months.map((m, idx) => (
                            <div
                                key={idx}
                                className="absolute flex items-center text-xs font-medium text-slate-600"
                                style={{ left: m.startIndex * cellWidth, width: m.span * cellWidth, top: 0, bottom: 0 }}
                            >
                                <span className="px-2">{m.label}</span>
                            </div>
                        ))}
                    </div>

                    {/* Day/Week labels */}
                    <div className="relative h-10">
                        {Array.from({ length: colCount }).map((_, i) => (
                            <div
                                key={i}
                                className={cn(
                                    'absolute top-0 bottom-0 flex items-center justify-center border-l border-slate-200/70 text-sm text-slate-700',
                                )}
                                style={{ left: i * cellWidth, width: cellWidth }}
                            >
                                {labelForCol(i)}
                            </div>
                        ))}
                    </div>

                    {/* Today line */}
                    {todayOffset !== null && <div className="absolute top-0 bottom-0 w-[2px] bg-red-500" style={{ left: todayOffset }} />}
                </div>
            </div>
        </div>
    );
}

export default TimelineHeader;
