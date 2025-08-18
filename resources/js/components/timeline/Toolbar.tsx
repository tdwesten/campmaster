import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import type { ViewMode } from '@/types/timeline';
import { Calendar as CalendarIcon, ChevronLeft, ChevronRight, Plus } from 'lucide-react';
import * as React from 'react';

export type ToolbarProps = {
    anchorDate: Date;
    onToday: () => void;
    onPrev: () => void;
    onNext: () => void;
    onPickDate: (d: Date) => void;
    viewMode: ViewMode;
    onViewModeChange: (v: ViewMode) => void;
    onOpenCreate: () => void;
    liveUpdates?: boolean;
};

export function Toolbar({
    anchorDate,
    onToday,
    onPrev,
    onNext,
    onPickDate,
    viewMode,
    onViewModeChange,
    onOpenCreate,
    liveUpdates = true,
}: ToolbarProps) {
    const [openDate, setOpenDate] = React.useState(false);
    const [pickDateStr, setPickDateStr] = React.useState<string>(anchorDate.toISOString().slice(0, 10));

    const applyDate = () => {
        if (pickDateStr) {
            const d = new Date(pickDateStr + 'T00:00:00');
            if (!Number.isNaN(d.getTime())) {
                onPickDate(d);
            }
        }
        setOpenDate(false);
    };

    return (
        <div className="flex items-center gap-2 rounded-md border bg-white/90 p-2">
            <div className="flex items-center gap-2">
                <Button variant="outline" size="sm" onClick={onToday} aria-label="Go to today">
                    Today
                </Button>
                <Button variant="ghost" size="icon" onClick={onPrev} aria-label="Previous">
                    <ChevronLeft className="size-4" />
                </Button>
                <Button variant="ghost" size="icon" onClick={onNext} aria-label="Next">
                    <ChevronRight className="size-4" />
                </Button>
                <Dialog open={openDate} onOpenChange={setOpenDate}>
                    <DialogTrigger asChild>
                        <Button variant="outline" size="sm" aria-label="Pick date">
                            <CalendarIcon className="mr-2 size-4" />
                            {anchorDate.toDateString()}
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Select anchor date</DialogTitle>
                        </DialogHeader>
                        <div className="grid gap-2 py-2">
                            <Label htmlFor="anchorDate">Date</Label>
                            <Input id="anchorDate" type="date" value={pickDateStr} onChange={(e) => setPickDateStr(e.target.value)} />
                        </div>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setOpenDate(false)}>
                                Cancel
                            </Button>
                            <Button onClick={applyDate}>Apply</Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>

            <Separator orientation="vertical" className="mx-1 h-6" />

            <ToggleGroup type="single" value={viewMode} onValueChange={(v) => v && onViewModeChange(v as ViewMode)}>
                <ToggleGroupItem value="monthly" aria-label="Monthly view">
                    Monthly
                </ToggleGroupItem>
                <ToggleGroupItem value="quarterly" aria-label="Quarterly view">
                    Quarterly
                </ToggleGroupItem>
            </ToggleGroup>

            <div className="ml-auto flex items-center gap-2">
                <Badge variant="secondary" className="border border-emerald-200 bg-emerald-100 text-emerald-700">
                    <span className="relative mr-2 flex h-2 w-2">
                        <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-75" />
                        <span className="relative inline-flex h-2 w-2 rounded-full bg-emerald-600" />
                    </span>
                    Live updates: {liveUpdates ? 'on' : 'off'}
                </Badge>
                <Button size="sm" onClick={onOpenCreate}>
                    <Plus className="mr-2 size-4" /> Create reservation
                </Button>
            </div>
        </div>
    );
}

export default Toolbar;
