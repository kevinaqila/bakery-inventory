<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'kasir' | 'karyawan';
    created_at: string;
}

defineProps<{
    users: User[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data',
        href: '#',
    },
    {
        title: 'Manajemen User',
        href: '#',
    },
];

const getRoleBadge = (role: string): 'default' | 'secondary' | 'outline' => {
    const badges: Record<string, 'default' | 'secondary' | 'outline'> = {
        admin: 'default',
        kasir: 'secondary',
        karyawan: 'outline',
    };
    return badges[role] || 'default';
};

const getRoleLabel = (role: string) => {
    const labels: Record<string, string> = {
        admin: 'Admin',
        kasir: 'Kasir',
        karyawan: 'Karyawan',
    };
    return labels[role] || role;
};

const handleDelete = (userId: number) => {
    if (confirm('Yakin ingin menghapus user ini?')) {
        router.delete(`/users/${userId}`);
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Manajemen User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <HeadingSmall
                    title="Manajemen User"
                    description="Kelola semua user aplikasi"
                />
                <Link href="/users/create">
                    <Button class="bg-orange-500 hover:bg-orange-600">
                        Tambah User
                    </Button>
                </Link>
            </div>

            <div class="rounded-lg border bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-muted">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Role
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Dibuat
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!users || users.length === 0">
                                <td
                                    colspan="5"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    Belum ada user
                                </td>
                            </tr>

                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td class="px-6 py-3 font-medium">
                                    {{ user.name }}
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-3">
                                    <Badge :variant="getRoleBadge(user.role)">
                                        {{ getRoleLabel(user.role) }}
                                    </Badge>
                                </td>
                                <td
                                    class="px-6 py-3 text-sm text-muted-foreground"
                                >
                                    {{ formatDate(user.created_at) }}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-center gap-2">
                                        <Link :href="`/users/${user.id}/edit`">
                                            <Button variant="outline" size="sm">
                                                Edit
                                            </Button>
                                        </Link>
                                        <Button
                                            @click="handleDelete(user.id)"
                                            variant="destructive"
                                            size="sm"
                                        >
                                            Hapus
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
