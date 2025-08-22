import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Link } from '@inertiajs/react';
import { BookOpen, CalendarCheck, CogIcon, LayoutGrid, TagIcon, TentIcon, Users } from 'lucide-react';
import AppLogo from './app-logo';

let mainNavItems: NavItem[] = [
    {
        title: 'messages.sidebar.dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'messages.sidebar.reservations',
        href: route('bookings.index'),
        icon: CalendarCheck,
    },
    {
        title: 'messages.sidebar.guests',
        href: route('guests.index'),
        icon: Users,
    },
];

let configurationNavMenuItems: NavItem[] = [
    {
        title: 'messages.sidebar.sites',
        href: route('sites.index'),
        icon: TentIcon,
    },
    {
        title: 'messages.sidebar.site_categories',
        href: route('site-categories.index'),
        icon: TagIcon,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'General Settings',
        href: '/settings',
        icon: CogIcon,
    },
    {
        title: 'Site',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { trans } = useLingua();

    [mainNavItems, configurationNavMenuItems] = [mainNavItems, configurationNavMenuItems].map((items) =>
        items.map((item) => ({
            ...item,
            title: trans(item.title),
        })),
    );

    return (
        <Sidebar collapsible="icon" variant="sidebar" className="w-64">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} title={trans('messages.sidebar.main')} />
                <NavMain items={configurationNavMenuItems} title={trans('messages.sidebar.configuration')} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
