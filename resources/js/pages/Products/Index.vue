<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const isAdmin = page.props.auth.user.role === 'admin';

interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    sku: string;
    description?: string;
    category_id: number;
    category: {
        id: number;
        name: string;
    };
    unit: string;
    purchase_price: number;
    selling_price: number;
    stock_quantity: number;
    created_at: string;
    updated_at: string;
}

interface PaginationData {
    data: Product[];
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    from: number;
    to: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

const props = defineProps<{
    products: PaginationData;
    categories: Category[];
    filters: {
        search?: string;
        category_id?: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inventory',
        href: '#',
    },
    {
        title: 'Produk',
        href: '/products',
    },
];

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category_id || '');

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const handleFilter = () => {
    const params: Record<string, any> = {};

    if (search.value) params.search = search.value;
    if (selectedCategory.value) params.category_id = selectedCategory.value;

    router.get('/products', params, { preserveState: false });
};

const clearFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    router.get('/products', {}, { preserveState: false });
};

const hasActiveFilters = computed(() => {
    return search.value || selectedCategory.value;
});

const getRelativeUrl = (fullUrl: string | null): string => {
    if (!fullUrl) return '#';
    try {
        const url = new URL(fullUrl);
        return url.pathname + url.search;
    } catch {
        return fullUrl;
    }
};
</script>

<template>
    <Head title="Produk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <HeadingSmall
                    title="Daftar Produk"
                    :description="
                        isAdmin ? 'Kelola semua produk' : 'Referensi produk'
                    "
                />
                <Link v-if="isAdmin" href="/products/create">
                    <Button class="bg-orange-500 hover:bg-orange-600">
                        Tambah Produk
                    </Button>
                </Link>
            </div>

            <!-- Filter Section -->
            <div class="flex flex-wrap items-end gap-3">
                <!-- Search -->
                <div class="min-w-[200px] flex-1">
                    <label class="text-sm font-medium">Cari Nama/SKU</label>
                    <input
                        v-model="search"
                        @keyup.enter="handleFilter"
                        type="text"
                        placeholder="Ketik nama atau SKU..."
                        class="mt-2 w-full rounded-lg border border-input px-3 py-2 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    />
                </div>

                <!-- Category Filter -->
                <div class="min-w-[180px] flex-1">
                    <label class="text-sm font-medium">Kategori</label>
                    <select
                        v-model="selectedCategory"
                        class="mt-2 w-full rounded-lg border border-input px-3 py-2 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    >
                        <option value="">Semua Kategori</option>
                        <option
                            v-for="cat in props.categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <Button
                        @click="handleFilter"
                        class="bg-orange-500 hover:bg-orange-600"
                    >
                        Cari
                    </Button>
                    <Button
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        variant="outline"
                    >
                        Reset
                    </Button>
                </div>
            </div>

            <!-- Results Info -->
            <div
                v-if="hasActiveFilters"
                class="rounded-lg border-l-4 border-l-orange-500 bg-orange-50 p-4"
            >
                <p class="text-sm text-orange-800">
                    Ditemukan
                    <span class="font-semibold">{{ products.total }}</span>
                    produk dari pencarian Anda
                    <button
                        @click="clearFilters"
                        class="ml-2 text-orange-600 underline hover:text-orange-800"
                    >
                        Reset filter
                    </button>
                </p>
            </div>

            <!-- Table -->
            <div class="rounded-lg border bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-muted">
                            <tr>
                                <th
                                    class="max-w-xs px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    SKU
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Harga Jual
                                </th>
                                <th
                                    v-if="isAdmin"
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Margin
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Stok
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
                                v-if="
                                    !products.data || products.data.length === 0
                                "
                            >
                                <td
                                    :colspan="isAdmin ? 7 : 6"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    <div class="space-y-2">
                                        <p>Tidak ada produk yang ditemukan</p>
                                        <button
                                            v-if="hasActiveFilters"
                                            @click="clearFilters"
                                            class="text-orange-600 underline hover:text-orange-800"
                                        >
                                            Coba reset filter
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td
                                    class="max-w-xs truncate px-6 py-3 font-medium"
                                >
                                    {{ product.name }}
                                </td>
                                <td
                                    class="px-6 py-3 text-sm text-muted-foreground"
                                >
                                    {{ product.sku }}
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    {{ product.category.name }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    {{ formatPrice(product.selling_price) }}
                                </td>
                                <td v-if="isAdmin" class="px-6 py-3 text-right">
                                    <Badge
                                        :variant="
                                            product.selling_price -
                                                product.purchase_price >
                                            0
                                                ? 'default'
                                                : 'destructive'
                                        "
                                    >
                                        {{
                                            formatPrice(
                                                product.selling_price -
                                                    product.purchase_price,
                                            )
                                        }}
                                        /
                                        {{
                                            Math.round(
                                                ((product.selling_price -
                                                    product.purchase_price) /
                                                    product.selling_price) *
                                                    100,
                                            )
                                        }}%
                                    </Badge>
                                </td>
                                <td class="px-6 py-3 text-right font-semibold">
                                    {{ product.stock_quantity }}
                                    {{ product.unit }}
                                </td>
                                <td
                                    v-if="isAdmin"
                                    class="space-x-2 px-6 py-3 text-right"
                                >
                                    <Link
                                        v-if="isAdmin"
                                        :href="`/products/${product.id}/edit`"
                                    >
                                        <Button variant="outline" size="sm">
                                            Edit
                                        </Button>
                                    </Link>
                                    <Link
                                        v-if="isAdmin"
                                        :href="`/products/${product.id}`"
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

            <!-- Pagination -->
            <div
                v-if="products.last_page > 1"
                class="flex items-center justify-between"
            >
                <p class="text-sm text-muted-foreground">
                    Menampilkan {{ products.from }} hingga
                    {{ products.to }} dari {{ products.total }} produk
                </p>
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in products.links"
                        :key="index"
                        :href="getRelativeUrl(link.url)"
                        preserve-scroll
                        :disabled="!link.url"
                        :class="[
                            'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                            link.active
                                ? 'bg-orange-500 text-white'
                                : link.url
                                  ? 'border hover:bg-muted'
                                  : 'cursor-not-allowed border text-muted-foreground opacity-50',
                        ]"
                    >
                        {{
                            link.label.includes('Previous')
                                ? '<'
                                : link.label.includes('Next')
                                  ? '>'
                                  : link.label.replace(/[^\d.]/g, '').trim() ||
                                    '...'
                        }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
