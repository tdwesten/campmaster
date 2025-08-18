import BookingBar from '@/components/timeline/BookingBar';
import { Grid } from '@/components/timeline/Grid';
import { ResourceSidebar } from '@/components/timeline/ResourceSidebar';
import { TimelineHeader } from '@/components/timeline/TimelineHeader';
import { Toolbar } from '@/components/timeline/Toolbar';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTimelineLayout } from '@/hooks/useTimelineLayout';
import type { TimelineProps, ViewMode } from '@/types/timeline';
import { addDays, startOfDay } from 'date-fns';
import * as React from 'react';

const SIDEBAR_WIDTH = 240;

export default function Timeline({ resources, bookings, anchorDate, defaultView = 'monthly', onCreate, liveUpdates = true }: TimelineProps) {
    const [viewMode, setViewMode] = React.useState<ViewMode>(defaultView);

    const {
        config,
        range,
        maps,
        todayOffset,
        resourceIndex,
        scrollRefs,
        onBodyScroll,
        shiftPrev,
        shiftNext,
        setAnchorDate,
        anchorDate: currentAnchor,
    } = useTimelineLayout(resources, bookings, anchorDate, viewMode);

    const [createOpen, setCreateOpen] = React.useState(false);
    const [createResourceId, setCreateResourceId] = React.useState<string>(resources[0]?.id ?? '');
    const [createStart, setCreateStart] = React.useState<string>(startOfDay(currentAnchor).toISOString().slice(0, 10));
    const [createEnd, setCreateEnd] = React.useState<string>(startOfDay(addDays(currentAnchor, 2)).toISOString().slice(0, 10));

    React.useEffect(() => {
        // keep form dates in sync with anchor changes
        setCreateStart(startOfDay(currentAnchor).toISOString().slice(0, 10));
        setCreateEnd(startOfDay(addDays(currentAnchor, 2)).toISOString().slice(0, 10));
    }, [currentAnchor]);

    const scrollToToday = () => {
        const body = scrollRefs.bodyRef.current;
        if (!body) return;
        const offset = todayOffset ?? maps.xFromDate(new Date());
        body.scrollLeft = Math.max(0, offset - SIDEBAR_WIDTH);
    };

    const handleCellClick = (resourceId: string, start: Date, end: Date) => {
        setCreateResourceId(resourceId);
        setCreateStart(start.toISOString().slice(0, 10));
        setCreateEnd(end.toISOString().slice(0, 10));
        setCreateOpen(true);
    };

    const handleCreateSubmit = () => {
        const s = new Date(createStart + 'T00:00:00');
        const e = new Date(createEnd + 'T00:00:00');
        if (onCreate && createResourceId) {
            onCreate(createResourceId, s, e);
        } else {
            console.log('create reservation:', { createResourceId, start: s, end: e });
        }
        setCreateOpen(false);
    };

    const totalWidth = maps.colCount * config.cellWidth;
    const totalHeight = resources.length * config.rowHeight;

    return (
        <div className="w-full">
            {/* Toolbar */}
            <Toolbar
                anchorDate={currentAnchor}
                onToday={() => {
                    setAnchorDate(new Date());
                    scrollToToday();
                }}
                onPrev={shiftPrev}
                onNext={shiftNext}
                onPickDate={(d) => setAnchorDate(d)}
                viewMode={viewMode}
                onViewModeChange={(v) => setViewMode(v)}
                onOpenCreate={() => setCreateOpen(true)}
                liveUpdates={liveUpdates}
            />

            {/* Header: left spacer + scrollable header */}
            <div className="flex">
                <div
                    className="sticky left-0 z-30 shrink-0 border-b border-slate-200/70 bg-white/90 backdrop-blur"
                    style={{ width: SIDEBAR_WIDTH }}
                />
                <div className="min-w-0 flex-1">
                    <TimelineHeader
                        colCount={maps.colCount}
                        labelForCol={maps.labelForCol}
                        monthLabelForCol={maps.monthLabelForCol}
                        cellWidth={config.cellWidth}
                        headerRef={scrollRefs.headerRef}
                        todayOffset={todayOffset}
                    />
                </div>
            </div>

            {/* Body: single scroll container holding sidebar (sticky) and grid */}
            <div
                ref={scrollRefs.bodyRef}
                onScroll={onBodyScroll}
                className="relative overflow-auto"
                style={{ height: Math.min(640, totalHeight + 40) }}
            >
                {/* Sidebar */}
                <ResourceSidebar resources={resources} width={SIDEBAR_WIDTH} rowHeight={config.rowHeight} />

                {/* Grid + Bookings layer */}
                <div className="relative" style={{ marginLeft: SIDEBAR_WIDTH, width: totalWidth, height: totalHeight }}>
                    <Grid
                        viewMode={viewMode}
                        resources={resources}
                        range={range}
                        cellWidth={config.cellWidth}
                        rowHeight={config.rowHeight}
                        onCellClick={handleCellClick}
                    />

                    {/* Today line in grid */}
                    {todayOffset !== null && <div className="absolute top-0 bottom-0 w-[2px] bg-red-500" style={{ left: todayOffset }} />}

                    {/* Bookings layer */}
                    <div className="absolute inset-0">
                        {bookings.map((b) => {
                            const row = resourceIndex.get(b.resourceId);
                            if (row == null) return null;
                            const top = row * config.rowHeight;
                            const left = maps.xFromDate(b.start);
                            const width = maps.widthFromDates(b.start, b.end);
                            return (
                                <BookingBar
                                    key={b.id}
                                    booking={b}
                                    left={left}
                                    width={width}
                                    top={top}
                                    height={config.rowHeight}
                                    onClick={(bk) => console.log('clicked booking', bk.id)}
                                />
                            );
                        })}
                    </div>
                </div>
            </div>

            {/* Create reservation dialog */}
            <Dialog open={createOpen} onOpenChange={setCreateOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Create reservation</DialogTitle>
                    </DialogHeader>
                    <div className="grid gap-3 py-2">
                        <div className="grid gap-1">
                            <Label htmlFor="resource">Resource</Label>
                            <Select value={createResourceId} onValueChange={setCreateResourceId}>
                                <SelectTrigger id="resource">
                                    <SelectValue placeholder="Select resource" />
                                </SelectTrigger>
                                <SelectContent>
                                    {resources.map((r) => (
                                        <SelectItem key={r.id} value={r.id}>
                                            {r.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div className="grid gap-1">
                                <Label htmlFor="start">Start</Label>
                                <Input id="start" type="date" value={createStart} onChange={(e) => setCreateStart(e.target.value)} />
                            </div>
                            <div className="grid gap-1">
                                <Label htmlFor="end">End</Label>
                                <Input id="end" type="date" value={createEnd} onChange={(e) => setCreateEnd(e.target.value)} />
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" onClick={() => setCreateOpen(false)}>
                            Cancel
                        </Button>
                        <Button onClick={handleCreateSubmit}>Create</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
