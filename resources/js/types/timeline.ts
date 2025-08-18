import { type LucideIcon } from 'lucide-react';

export type ViewMode = 'monthly' | 'quarterly';

export type ResourceGroup = {
    id: string;
    name: string;
};

export type Resource = {
    id: string;
    name: string;
    groupId: string;
    groupName?: string; // convenience when already joined
    amenities?: string;
    icon?: LucideIcon; // lucide-react icon component
};

export type BookingVariant = 'confirmed' | 'tentative' | 'blocked' | 'group';

export type Booking = {
    id: string;
    resourceId: string;
    start: Date;
    end: Date; // inclusive end date
    name: string; // guest or reservation label
    variant?: BookingVariant;
    avatarUrl?: string;
    color?: string; // optional override color
};

export type TimelineProps = {
    resources: Resource[];
    bookings: Booking[];
    anchorDate: Date;
    defaultView?: ViewMode;
    onCreate?: (resourceId: string, start: Date, end: Date) => void;
    liveUpdates?: boolean;
};
