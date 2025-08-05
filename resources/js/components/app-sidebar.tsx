import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, Home, Users, CreditCard, Wrench, UserPlus } from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const userRole = auth.user?.role;

    const getNavItems = (): NavItem[] => {
        const baseItems: NavItem[] = [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ];

        // Admin and staff navigation
        if (['administrator', 'housing_manager', 'sales_staff'].includes(userRole)) {
            baseItems.push(
                {
                    title: 'Data Rumah',
                    href: '/houses',
                    icon: Home,
                },
                {
                    title: 'Data Penghuni',
                    href: '/residents',
                    icon: Users,
                }
            );
        }

        // Admin and manager can manage payments
        if (['administrator', 'housing_manager'].includes(userRole)) {
            baseItems.push({
                title: 'Pembayaran',
                href: '/payments',
                icon: CreditCard,
            });
        }

        // All users can access complaints
        baseItems.push({
            title: 'Keluhan',
            href: '/complaints',
            icon: Wrench,
        });

        // Admin only features
        if (userRole === 'administrator') {
            baseItems.push({
                title: 'Kelola User',
                href: '/users',
                icon: UserPlus,
            });
        }

        return baseItems;
    };

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={getNavItems()} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}