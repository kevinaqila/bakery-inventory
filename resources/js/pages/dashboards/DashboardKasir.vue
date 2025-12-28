<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const dashboard = page.props as any;

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

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
</script>

<template>
    <div class="space-y-6 p-4">
        <HeadingSmall title="Dashboard Kasir" />

        <!-- Metrics Cards -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Card: Transaksi Bulan Ini -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Transaksi Bulan Ini
                </p>
                <p class="mt-2 text-3xl font-bold">
                    {{ dashboard.totalTransaksisBulanIni }}
                </p>
            </div>

            <!-- Card: Penjualan Bulan Ini -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Penjualan Bulan Ini
                </p>
                <p class="mt-2 text-3xl font-bold">
                    {{ formatCurrency(dashboard.totalPenjualanBulanIni) }}
                </p>
            </div>
        </div>

        <!-- Top Products Table -->
        <div class="rounded-lg border bg-white">
            <div class="border-b p-6">
                <h3 class="text-lg font-semibold">Produk Terlaris</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-muted">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">
                                Produk
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Qty Terjual
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Revenue
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="product in dashboard.topProducts"
                            :key="product.product_id"
                            class="border-b hover:bg-muted/50"
                        >
                            <td class="px-6 py-4">
                                {{ product.name }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Badge variant="outline">
                                    {{ product.total_qty }} pcs
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                {{ formatCurrency(product.total_revenue) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="rounded-lg border bg-white">
            <div class="border-b p-6">
                <h3 class="text-lg font-semibold">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-muted">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Jumlah Item
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="transaction in dashboard.recentTransactions"
                            :key="transaction.id"
                            class="border-b hover:bg-muted/50"
                        >
                            <td class="px-6 py-4">
                                {{ formatDate(transaction.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Badge variant="outline">
                                    {{
                                        transaction.transaction_items?.length ||
                                        0
                                    }}
                                    items
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                {{ formatCurrency(transaction.final_amount) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
