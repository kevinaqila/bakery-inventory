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
    });
};
</script>

<template>
    <div class="space-y-6 p-4">
        <HeadingSmall title="Dashboard Admin" />

        <!-- Metrics Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
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
                <p class="mt-2 text-2xl font-bold">
                    {{ formatCurrency(dashboard.totalPenjualanBulanIni) }}
                </p>
            </div>

            <!-- Card: Transaksi Tahun Ini -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Transaksi Tahun Ini
                </p>
                <p class="mt-2 text-3xl font-bold">
                    {{ dashboard.totalTransaksisahunIni }}
                </p>
            </div>

            <!-- Card: Penjualan Tahun Ini -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Penjualan Tahun Ini
                </p>
                <p class="mt-2 text-2xl font-bold">
                    {{ formatCurrency(dashboard.totalPenjualanTahunIni) }}
                </p>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Card: Total Produk -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Produk Aktif
                </p>
                <p class="mt-2 text-4xl font-bold">
                    {{ dashboard.totalProduk }}
                </p>
            </div>

            <!-- Card: Total User -->
            <div class="rounded-lg border bg-white p-6">
                <p class="text-sm font-medium text-muted-foreground">
                    Total User
                </p>
                <p class="mt-2 text-4xl font-bold">
                    {{ dashboard.totalUser }}
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
                            <th class="px-6 py-3 text-right font-semibold">
                                Profit (Margin)
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Margin %
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
                            <td class="px-6 py-4 text-right">
                                <Badge
                                    :variant="
                                        product.total_margin > 0
                                            ? 'default'
                                            : 'destructive'
                                    "
                                >
                                    {{ formatCurrency(product.total_margin) }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Badge
                                    :variant="
                                        product.total_revenue > 0
                                            ? 'default'
                                            : 'destructive'
                                    "
                                >
                                    {{
                                        Math.round(
                                            (product.total_margin /
                                                product.total_revenue) *
                                                100,
                                        )
                                    }}%
                                </Badge>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Chart Data (7 hari) -->
        <div class="rounded-lg border bg-white">
            <div class="border-b p-6">
                <h3 class="text-lg font-semibold">Penjualan 7 Hari Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b bg-muted">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right font-semibold">
                                Total Penjualan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(sale, index) in dashboard.salesChart"
                            :key="index"
                            class="border-b hover:bg-muted/50"
                        >
                            <td class="px-6 py-4">
                                {{ formatDate(sale.date) }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                {{ formatCurrency(sale.total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
