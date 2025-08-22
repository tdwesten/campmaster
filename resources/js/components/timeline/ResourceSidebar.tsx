import { cn } from '@/lib/utils';
import type { Resource } from '@/types/timeline';
import * as React from 'react';

export type ResourceSidebarProps = {
    resources: Resource[];
    width?: number; // default 240
    rowHeight: number;
};

export const ResourceSidebar = React.forwardRef<HTMLDivElement, ResourceSidebarProps>(function ResourceSidebar(
    { resources, width = 240, rowHeight },
    ref,
) {
    // Group by groupName or groupId
    const groups = React.useMemo(() => {
        const map = new Map<string, { id: string; name: string; items: Resource[] }>();
        for (const r of resources) {
            const key = r.groupId ?? 'default';
            const name = r.groupName ?? 'Resources';
            if (!map.has(key)) {
                map.set(key, { id: key, name, items: [] });
            }
            map.get(key)!.items.push(r);
        }
        return Array.from(map.values());
    }, [resources]);

    return (
        <div ref={ref} className="absolute top-0 left-0 z-20 border-r border-slate-200/70 bg-white/90 backdrop-blur" style={{ width }}>
            {groups.map((g) => (
                <div key={g.id}>
                    <div className="border-b border-slate-200/70 bg-slate-50 px-3 py-2 text-xs font-semibold tracking-wide text-slate-600 uppercase">
                        {g.name}
                    </div>
                    {g.items.map((r) => {
                        const Icon = r.icon as React.ComponentType<{ className?: string }> | undefined;
                        return (
                            <div
                                key={r.id}
                                className={cn('flex items-center gap-3 px-3', 'border-b border-slate-200/70')}
                                style={{ height: rowHeight }}
                            >
                                <div className="flex size-8 items-center justify-center rounded-md bg-slate-100 text-slate-700">
                                    {Icon ? <Icon className="size-4" /> : <span className="text-xs">R</span>}
                                </div>
                                <div className="min-w-0">
                                    <div className="truncate text-sm font-medium">{r.name}</div>
                                    {r.amenities && <div className="truncate text-xs text-slate-500">{r.amenities}</div>}
                                </div>
                            </div>
                        );
                    })}
                </div>
            ))}
        </div>
    );
});

export default ResourceSidebar;
