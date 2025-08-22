import { cn } from '@/lib/utils';
import type { Resource, ViewMode } from '@/types/timeline';
import { addDays, isWeekend } from 'date-fns';

export type GridProps = {
    viewMode: ViewMode;
    resources: Resource[];
    range: {
        start: Date;
        end: Date;
        days: Date[];
        weeks: Date[];
    };
    cellWidth: number;
    rowHeight: number;
    onCellClick?: (resourceId: string, start: Date, end: Date) => void;
};

export function Grid({ viewMode, resources, range, cellWidth, rowHeight, onCellClick }: GridProps) {
    const cols = viewMode === 'monthly' ? range.days : range.weeks;
    const totalWidth = cols.length * cellWidth;
    const totalHeight = resources.length * rowHeight;

    // Compute category (group) boundaries to draw full-width separators
    const groupBoundaries: number[] = [];
    let prevGroup: string | undefined = resources[0] ? (resources[0].groupId ?? 'default') : undefined;
    resources.forEach((r, idx) => {
        const g = r.groupId ?? 'default';
        if (idx > 0 && g !== prevGroup) {
            groupBoundaries.push(idx);
        }
        prevGroup = g;
    });

    return (
        <div className="relative" style={{ width: totalWidth, height: totalHeight }}>
            {/* Columns */}
            {cols.map((col, i) => {
                const weekend = viewMode === 'monthly' ? isWeekend(col) : false;
                return (
                    <div
                        key={i}
                        className={cn('absolute top-0 bottom-0 border-l border-slate-200/70', weekend && 'bg-slate-50')}
                        style={{ left: i * cellWidth, width: cellWidth }}
                    />
                );
            })}

            {/* Row separators */}
            {resources.map((r, idx) => (
                <div key={r.id} className="absolute right-0 left-0 border-b border-slate-200/70" style={{ top: (idx + 1) * rowHeight - 1 }} />
            ))}

            {/* Category separators across full width */}
            {groupBoundaries.map((idx) => (
                <div key={`group-sep-${idx}`} className="absolute right-0 left-0 border-t-2 border-slate-300" style={{ top: idx * rowHeight - 1 }} />
            ))}

            {/* Interactive cells */}
            {resources.map((r, rowIdx) =>
                cols.map((c, colIdx) => {
                    const colStart = viewMode === 'monthly' ? c : c;
                    const colEnd = viewMode === 'monthly' ? c : addDays(c, 6);
                    return (
                        <button
                            key={`${r.id}-${colIdx}`}
                            type="button"
                            aria-label={`Create on ${colStart.toDateString()} for ${r.name}`}
                            className="absolute bg-transparent outline-none hover:bg-emerald-50/50 focus-visible:ring-2 focus-visible:ring-emerald-300/60"
                            style={{
                                left: colIdx * cellWidth,
                                top: rowIdx * rowHeight,
                                width: cellWidth,
                                height: rowHeight,
                            }}
                            onClick={() => onCellClick?.(r.id, colStart, colEnd)}
                        />
                    );
                }),
            )}
        </div>
    );
}

export default Grid;
