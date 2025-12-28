<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    LayoutGrid,
    Package,
    ShoppingCart,
    Tags,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const userRole = computed(() => page.props.auth.user.role);

// Admin menu - Analytics & Data
const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const adminSections = [
    {
        label: 'PRODUCT',
        items: [
            {
                title: 'Kategori Produk',
                href: '/categories',
                icon: Tags,
            },
            {
                title: 'Daftar Produk',
                href: '/products',
                icon: Package,
            },
        ],
    },
    {
        label: 'MANAJEMEN',
        items: [
            {
                title: 'User',
                href: '/users',
                icon: Users,
            },
        ],
    },
];

// Kasir menu - POS & Stok
const kasirNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const kasirSections = [
    {
        label: 'PENJUALAN',
        items: [
            {
                title: 'Buat Penjualan',
                href: '/transactions/create',
                icon: ShoppingCart,
            },
            {
                title: 'Riwayat Penjualan',
                href: '/transactions',
                icon: BookOpen,
            },
        ],
    },
    {
        label: 'PRODUK',
        items: [
            {
                title: 'Daftar Produk',
                href: '/products',
                icon: Package,
            },
        ],
    },
];

// Karyawan menu - Input Stok & Produk & Kategori
const karyawanNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const karyawanSections = [
    {
        label: 'STOK',
        items: [
            {
                title: 'Aktivitas Stok',
                href: '/stocks',
                icon: Package,
            },
        ],
    },
    {
        label: 'PRODUK',
        items: [
            {
                title: 'Daftar Produk',
                href: '/products',
                icon: Package,
            },
            {
                title: 'Kategori Produk',
                href: '/categories',
                icon: Tags,
            },
        ],
    },
];

// Select menu berdasarkan role
const mainNavItems = computed(() => {
    if (userRole.value === 'admin') return adminNavItems;
    if (userRole.value === 'kasir') return kasirNavItems;
    return karyawanNavItems;
});

const navSections = computed(() => {
    if (userRole.value === 'admin') return adminSections;
    if (userRole.value === 'kasir') return kasirSections;
    return karyawanSections;
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <div
                v-for="section in navSections"
                :key="section.label"
                class="space-y-2"
            >
                <SidebarGroup class="px-2 py-0">
                    <SidebarGroupLabel>{{ section.label }}</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem
                            v-for="item in section.items"
                            :key="item.title"
                        >
                            <SidebarMenuButton
                                as-child
                                :is-active="urlIsActive(item.href, page.url)"
                                :tooltip="item.title"
                            >
                                <Link :href="item.href">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
            </div>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
