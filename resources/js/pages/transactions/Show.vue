<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface TransactionItem {
    id: number;
    product: {
        id: number;
        name: string;
    };
    quantity: number;
    unit_price: number;
    subtotal: number;
}

interface Transaction {
    id: number;
    user: {
        id: number;
        name: string;
    };
    total_amount: number;
    discount_amount: number;
    final_amount: number;
    payment_amount: number;
    change_amount: number;
    payment_method: string;
    notes: string | null;
    created_at: string;
    transaction_items: TransactionItem[];
}

defineProps<{
    transaction: Transaction;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Penjualan',
        href: '#',
    },
    {
        title: 'Riwayat',
        href: '/transactions',
    },
    {
        title: 'Detail',
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
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const handlePrint = () => {
    window.print();
};
</script>

<template>
    <Head :title="`Invoice #${transaction.id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <HeadingSmall
                    :title="`Invoice #${transaction.id}`"
                    description="Detail transaksi penjualan"
                />
                <div class="space-x-2">
                    <Button
                        @click="handlePrint"
                        class="bg-orange-500 hover:bg-orange-600 print:hidden"
                    >
                        Cetak
                    </Button>
                    <Link href="/transactions">
                        <Button variant="outline" class="print:hidden">
                            Kembali
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Invoice Container -->
            <div class="space-y-6 rounded-lg border bg-white p-8">
                <!-- Header -->
                <div class="border-b pb-6">
                    <h1 class="text-2xl font-bold">INVOICE</h1>
                    <p class="text-sm text-muted-foreground">
                        Manis Legit Bakery
                    </p>
                </div>

                <!-- Invoice Info -->
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm font-semibold text-muted-foreground">
                            Invoice Number
                        </p>
                        <p class="text-lg font-bold">#{{ transaction.id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-muted-foreground">
                            Tanggal
                        </p>
                        <p class="text-lg font-bold">
                            {{ formatDate(transaction.created_at) }}
                        </p>
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="border-t border-b py-6">
                    <p class="text-sm font-semibold text-muted-foreground">
                        Kasir
                    </p>
                    <p class="text-lg font-medium">
                        {{ transaction.user?.name ?? 'Unknown' }}
                    </p>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-muted">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold"
                                >
                                    Produk
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Qty
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Harga
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-semibold"
                                >
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in transaction.transaction_items"
                                :key="item.id"
                                class="border-b"
                            >
                                <td class="px-6 py-3 font-medium">
                                    {{ item.product?.name ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-3 text-right text-sm">
                                    {{ item.quantity }} pcs
                                </td>
                                <td class="px-6 py-3 text-right text-sm">
                                    {{ formatCurrency(item.unit_price) }}
                                </td>
                                <td class="px-6 py-3 text-right font-semibold">
                                    {{ formatCurrency(item.subtotal) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="space-y-2 border-t pt-6">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground"
                                    >Subtotal</span
                                >
                                <span class="font-medium">
                                    {{
                                        formatCurrency(transaction.total_amount)
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="transaction.discount_amount > 0"
                                class="flex justify-between text-sm"
                            >
                                <span class="text-muted-foreground">
                                    Diskon
                                </span>
                                <span class="font-medium text-red-600">
                                    -{{
                                        formatCurrency(
                                            transaction.discount_amount,
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                class="flex justify-between border-t border-b py-2 text-lg font-bold"
                            >
                                <span>Total</span>
                                <span>
                                    {{
                                        formatCurrency(transaction.final_amount)
                                    }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">
                                    Dibayar
                                </span>
                                <span class="font-medium">
                                    {{
                                        formatCurrency(
                                            transaction.payment_amount,
                                        )
                                    }}
                                </span>
                            </div>
                            <div
                                class="flex justify-between bg-orange-50 px-2 py-2 text-sm font-bold text-orange-700"
                            >
                                <span>Kembalian</span>
                                <span>
                                    {{
                                        formatCurrency(
                                            transaction.change_amount,
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div v-if="transaction.notes" class="border-t pt-4">
                    <p class="text-sm font-semibold text-muted-foreground">
                        Catatan
                    </p>
                    <p class="text-sm">{{ transaction.notes }}</p>
                </div>

                <!-- Footer -->
                <div
                    class="border-t pt-6 text-center text-xs text-muted-foreground"
                >
                    <p>Terima kasih telah berbelanja di Manis Legit Bakery</p>
                    <p>{{ formatDate(transaction.created_at) }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    :global(body) {
        background: white;
    }
    :global(.print\:hidden) {
        display: none;
    }
}
</style>
