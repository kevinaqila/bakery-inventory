<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isAdmin = page.props.auth.user.role === 'admin';

interface Category {
    id: number;
    name: string;
    description?: string;
    slug: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

defineProps<{
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inventory',
        href: '#',
    },
    {
        title: 'Kategori',
        href: '/categories',
    },
];
</script>

<template>
    <Head title="Kategori" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <HeadingSmall
                    title="Daftar Kategori"
                    :description="
                        isAdmin
                            ? 'Kelola kategori produk'
                            : 'Referensi kategori'
                    "
                />
                <Link v-if="isAdmin" href="/categories/create">
                    <Button class="bg-orange-500 hover:bg-orange-600">
                        Tambah Kategori
                    </Button>
                </Link>
            </div>

            <!-- Table -->
            <!-- Table -->
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
                                    Deskripsi
                                </th>
                                <th
                                    v-if="isAdmin"
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="category in categories"
                                :key="category.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td class="px-6 py-3 font-medium">
                                    {{ category.name }}
                                </td>
                                <td
                                    class="px-6 py-3 text-sm text-muted-foreground"
                                >
                                    {{ category.description || '-' }}
                                </td>
                                <td
                                    v-if="isAdmin"
                                    class="space-x-2 px-6 py-3 text-right"
                                >
                                    <Link
                                        v-if="isAdmin"
                                        :href="`/categories/${category.id}/edit`"
                                    >
                                        <Button variant="outline" size="sm">
                                            Edit
                                        </Button>
                                    </Link>
                                    <Link
                                        v-if="isAdmin"
                                        :href="`/categories/${category.id}`"
                                        method="delete"
                                        as="button"
                                    >
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            type="button"
                                        >
                                            Hapus
                                        </Button>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
