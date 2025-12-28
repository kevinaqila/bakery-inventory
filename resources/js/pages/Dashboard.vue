<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DashboardAdmin from '@/pages/dashboards/DashboardAdmin.vue';
import DashboardKaryawan from '@/pages/dashboards/DashboardKaryawan.vue';
import DashboardKasir from '@/pages/dashboards/DashboardKasir.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const userRole = page.props.auth.user.role;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <DashboardAdmin v-if="userRole === 'admin'" />
            <DashboardKasir v-else-if="userRole === 'kasir'" />
            <DashboardKaryawan v-else-if="userRole === 'karyawan'" />
        </div>
    </AppLayout>
</template>
