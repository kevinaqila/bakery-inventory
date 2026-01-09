<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    products_count?: number;
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

// Confirmation modal state
const showConfirmation = ref(false);
const selectedCategory = ref<Category | null>(null);

const openDeleteConfirmation = (category: Category) => {
    selectedCategory.value = category;
    showConfirmation.value = true;
};

const confirmDelete = () => {
    if (selectedCategory.value) {
        router.delete(`/categories/${selectedCategory.value.id}`, {
            onSuccess: () => {
                showConfirmation.value = false;
                selectedCategory.value = null;
            },
        });
    }
};

const cancelDelete = () => {
    showConfirmation.value = false;
    selectedCategory.value = null;
};
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
                                    <Button
                                        v-if="isAdmin"
                                        variant="destructive"
                                        size="sm"
                                        @click="
                                            openDeleteConfirmation(category)
                                        "
                                    >
                                        Hapus
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div
            v-if="showConfirmation"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        >
            <div class="w-full max-w-sm rounded-lg bg-white p-7 shadow-lg">
                <h3 class="mb-2 text-lg font-bold text-red-600">
                    Hapus Kategori?
                </h3>
                <p class="mb-4 text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus kategori
                    <strong>{{ selectedCategory?.name }}</strong
                    >?
                </p>
                <p
                    class="mb-6 rounded bg-yellow-50 p-3 text-sm text-yellow-700"
                >
                    Semua produk dalam kategori ini akan dihapus juga!
                </p>

                <div class="flex justify-end gap-3">
                    <Button
                        variant="outline"
                        @click="cancelDelete"
                        class="px-4 py-2"
                    >
                        Batal
                    </Button>
                    <Button
                        variant="destructive"
                        @click="confirmDelete"
                        class="px-4 py-2"
                    >
                        Ya, Hapus
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
