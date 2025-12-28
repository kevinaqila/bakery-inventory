<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Transaction {
    id: number;
    user: {
        id: number;
        name: string;
    };
    total_amount: number;
    discount_amount: number;
    final_amount: number;
    payment_method: string;
    created_at: string;
}

defineProps<{
    transactions: {
        data: Transaction[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Penjualan',
        href: '#',
    },
    {
        title: 'Riwayat',
        href: '#',
    },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getPaymentBadge = (method: string) => {
    return method === 'tunai' ? 'default' : 'secondary';
};
</script>

<template>
    <Head title="Riwayat Transaksi" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <HeadingSmall
                    title="Riwayat Transaksi"
                    description="Daftar semua transaksi penjualan"
                />
                <Link href="/transactions/create">
                    <Button class="bg-orange-500 hover:bg-orange-600">
                        Transaksi Baru
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
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Kasir
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Total
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Diskon
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Final
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold"
                                >
                                    Pembayaran
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="
                                    !transactions.data ||
                                    transactions.data.length === 0
                                "
                            >
                                <td
                                    colspan="7"
                                    class="py-8 text-center text-muted-foreground"
                                >
                                    Belum ada data transaksi
                                </td>
                            </tr>

                            <tr
                                v-for="transaction in transactions.data"
                                :key="transaction.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td class="px-6 py-3 text-sm">
                                    {{ formatDate(transaction.created_at) }}
                                </td>
                                <td class="px-6 py-3 font-medium">
                                    {{ transaction.user?.name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-3 text-right text-sm">
                                    {{
                                        formatCurrency(transaction.total_amount)
                                    }}
                                </td>
                                <td
                                    class="px-6 py-3 text-right text-sm text-red-600"
                                >
                                    -{{
                                        formatCurrency(
                                            transaction.discount_amount,
                                        )
                                    }}
                                </td>
                                <td class="px-6 py-3 text-right font-semibold">
                                    {{
                                        formatCurrency(transaction.final_amount)
                                    }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <Badge
                                        :variant="
                                            getPaymentBadge(
                                                transaction.payment_method,
                                            )
                                        "
                                    >
                                        {{ transaction.payment_method }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <Link
                                        :href="`/transactions/${transaction.id}`"
                                    >
                                        <Button variant="outline" size="sm">
                                            Lihat
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
                v-if="transactions.last_page > 1"
                class="flex items-center justify-between"
            >
                <div class="text-sm text-muted-foreground">
                    Halaman {{ transactions.current_page }} dari
                    {{ transactions.last_page }}
                </div>
                <div class="space-x-2">
                    <Link
                        v-if="transactions.current_page > 1"
                        :href="`/transactions?page=${transactions.current_page - 1}`"
                    >
                        <Button variant="outline" size="sm">
                            ← Sebelumnya
                        </Button>
                    </Link>
                    <Link
                        v-if="
                            transactions.current_page < transactions.last_page
                        "
                        :href="`/transactions?page=${transactions.current_page + 1}`"
                    >
                        <Button variant="outline" size="sm">
                            Berikutnya →
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
