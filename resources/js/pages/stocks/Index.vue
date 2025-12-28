<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface StockItem {
    id: number;
    product: {
        id: number;
        name: string;
    };
    type: 'in' | 'out';
    quantity: number;
    notes: string | null;
    user: {
        id: number;
        name: string;
    };
    created_at: string;
}

defineProps<{
    stocks: {
        data: StockItem[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inventory',
        href: '#',
    },
    {
        title: 'Stok',
        href: '#',
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getTypeBadge = (type: string) => {
    return type === 'in' ? 'default' : 'destructive';
};

const getTypeLabel = (type: string) => {
    return type === 'in' ? 'Masuk' : 'Keluar';
};
</script>

<template>
    <Head title="Riwayat Stok" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <HeadingSmall
                    title="Riwayat Stok"
                    description="Daftar semua transaksi stok masuk dan keluar"
                />
                <Link href="/stocks/create">
                    <Button class="bg-orange-500 hover:bg-orange-600">
                        Input Stok Masuk
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
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Produk
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold"
                                >
                                    Type
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Qty
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Input Oleh
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Catatan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!stocks.data || stocks.data.length === 0">
                                <td
                                    colspan="6"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    Belum ada data stok
                                </td>
                            </tr>

                            <tr
                                v-for="stock in stocks.data"
                                :key="stock.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td class="px-6 py-3 text-sm">
                                    {{ formatDate(stock.created_at) }}
                                </td>
                                <td class="px-6 py-3 font-medium">
                                    {{
                                        stock.product?.name ??
                                        'Produk Tidak Ditemukan'
                                    }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <Badge :variant="getTypeBadge(stock.type)">
                                        {{ getTypeLabel(stock.type) }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-3 text-right font-semibold">
                                    {{ stock.quantity }} pcs
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    {{
                                        stock.user?.name ??
                                        'User Tidak Ditemukan'
                                    }}
                                </td>
                                <td
                                    class="px-6 py-3 text-sm text-muted-foreground"
                                >
                                    {{ stock.notes || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
