<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const dashboard = page.props as any;

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'short',
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
    <div class="space-y-6 p-6">
        <HeadingSmall title="Dashboard Karyawan" />

        <!-- Metrics Cards -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Card: Total Input Bulan Ini -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Jumlah Input Stok Bulan Ini
                </p>
                <p class="mt-2 text-4xl font-bold">
                    {{ dashboard.totalStokMasukBulanIni }}
                </p>
            </div>

            <!-- Card: Total Quantity -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Total Qty Masuk
                </p>
                <p class="mt-2 text-4xl font-bold">
                    {{ dashboard.totalQuantityMasukBulanIni }}
                </p>
                <p class="mt-1 text-xs text-muted-foreground">pcs</p>
            </div>
        </div>

        <!-- Recent Stock Activities -->
        <div class="rounded-lg border bg-white">
            <div class="border-b p-6">
                <h3 class="text-lg font-semibold">Aktivitas Stok Terbaru</h3>
                <p class="mt-1 text-sm text-muted-foreground">
                    Daftar input stok 10 hari terakhir
                </p>
            </div>
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
                                Tipe
                            </th>
                            <th
                                class="px-6 py-3 text-right text-sm font-semibold"
                            >
                                Qty
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="stock in dashboard.recentStocks"
                            :key="stock.id"
                            class="border-b hover:bg-muted/50"
                        >
                            <td class="px-6 py-4 text-sm">
                                {{ formatDate(stock.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                {{ stock.product.name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <Badge :variant="getTypeBadge(stock.type)">
                                    {{ getTypeLabel(stock.type) }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">
                                {{ stock.quantity }} pcs
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
