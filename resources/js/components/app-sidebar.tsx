import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import useLingua from '@cyberwolf.studio/lingua-react';
import { Link } from '@inertiajs/react';
import { BookOpen, CalendarCheck, CogIcon, LayoutGrid, Users } from 'lucide-react';
import AppLogo from './app-logo';

let mainNavItems: NavItem[] = [
    {
        title: 'messages.sidebar.dashboard',
        href: '/',
        icon: LayoutGrid,
    },
    {
        title: 'messages.sidebar.reservations',
        href: '/bookings',
        icon: CalendarCheck,
    },
    {
        title: 'messages.sidebar.guests',
        href: '/guests',
        icon: Users,
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

    mainNavItems = mainNavItems.map((item) => {
        return {
            ...item,
            title: trans(item.title),
        };
    });

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
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
