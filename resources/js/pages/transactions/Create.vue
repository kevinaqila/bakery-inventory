<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Product {
    id: number;
    name: string;
    unit: string;
    selling_price: number;
    stock_quantity: number;
}

interface CartItem {
    product_id: number;
    product_name: string;
    quantity: number;
    unit_price: number;
    subtotal: number;
}

const props = defineProps<{
    products: Product[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Penjualan',
        href: '/transactions',
    },
    {
        title: 'Transaksi Baru',
        href: '#',
    },
];

// Form state
const form = useForm({
    total_amount: 0,
    discount_amount: 0,
    final_amount: 0,
    payment_amount: 0,
    change_amount: 0,
    payment_method: 'tunai',
    notes: '',
    items: [] as CartItem[],
});

// Cart state
const cart = ref<CartItem[]>([]);
const selectedProductId = ref('');
const selectedQuantity = ref('1');
const discountAmount = ref('0');
const paymentAmount = ref('0');

// Computed properties
const selectedProduct = computed(() => {
    if (!selectedProductId.value) return null;
    return props.products.find(
        (p) => p.id === parseInt(selectedProductId.value),
    );
});

const subtotal = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.subtotal, 0);
});

const totalDiscount = computed(() => {
    return parseInt(discountAmount.value) || 0;
});

const finalTotal = computed(() => {
    return subtotal.value - totalDiscount.value;
});

const payment = computed(() => {
    return parseInt(paymentAmount.value) || 0;
});

const change = computed(() => {
    return payment.value - finalTotal.value;
});

// Methods
const addToCart = () => {
    if (!selectedProduct.value) {
        alert('Pilih produk terlebih dahulu');
        return;
    }

    const qty = parseInt(selectedQuantity.value);
    if (qty <= 0) {
        alert('Kuantitas harus lebih dari 0');
        return;
    }

    if (qty > selectedProduct.value.stock_quantity) {
        alert('Stok tidak cukup');
        return;
    }

    // Check if product already in cart
    const existingItem = cart.value.find(
        (item) => item.product_id === selectedProduct.value?.id,
    );

    if (existingItem) {
        const newQty = existingItem.quantity + qty;
        if (newQty > selectedProduct.value.stock_quantity) {
            alert('Stok tidak cukup');
            return;
        }
        existingItem.quantity = newQty;
        existingItem.subtotal = newQty * existingItem.unit_price;
    } else {
        cart.value.push({
            product_id: selectedProduct.value.id,
            product_name: selectedProduct.value.name,
            quantity: qty,
            unit_price: selectedProduct.value.selling_price,
            subtotal: qty * selectedProduct.value.selling_price,
        });
    }

    // Reset input
    selectedProductId.value = '';
    selectedQuantity.value = '1';
};

const removeFromCart = (index: number) => {
    cart.value.splice(index, 1);
};

const updateQuantity = (index: number, newQty: string) => {
    const qty = parseInt(newQty);
    if (qty <= 0) {
        removeFromCart(index);
        return;
    }

    const item = cart.value[index];
    const product = props.products.find((p) => p.id === item.product_id);

    if (product && qty > product.stock_quantity) {
        alert('Stok tidak cukup');
        return;
    }

    item.quantity = qty;
    item.subtotal = qty * item.unit_price;
};

const submitTransaction = () => {
    if (cart.value.length === 0) {
        alert('Keranjang masih kosong');
        return;
    }

    if (payment.value < finalTotal.value) {
        alert('Pembayaran kurang');
        return;
    }

    form.total_amount = subtotal.value;
    form.discount_amount = totalDiscount.value;
    form.final_amount = finalTotal.value;
    form.payment_amount = payment.value;
    form.change_amount = change.value;
    form.items = cart.value;

    form.post('/transactions', {
        onSuccess: () => {
            alert('Transaksi berhasil!');
        },
        onError: (errors) => {
            console.log('Form errors:', errors);
            alert('Ada kesalahan pada form');
        },
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Transaksi Baru" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <HeadingSmall
                title="Transaksi Penjualan"
                description="Buat transaksi penjualan baru"
            />

            <!-- Top Input Section - Streamlined -->
            <div class="rounded-lg border bg-white p-6">
                <h3 class="mb-4 text-lg font-semibold">Tambah Produk</h3>
                <div class="flex gap-4">
                    <!-- Select Product -->
                    <div class="flex-1">
                        <label class="text-sm font-medium">Produk</label>
                        <select
                            v-model="selectedProductId"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        >
                            <option value="">Pilih Produk</option>
                            <option
                                v-for="product in props.products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }} (Stok:
                                {{ product.stock_quantity }})
                            </option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="w-24">
                        <label class="text-sm font-medium">Jumlah</label>
                        <input
                            v-model="selectedQuantity"
                            type="number"
                            min="1"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 text-center focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        />
                    </div>

                    <!-- Add Button -->
                    <div class="flex items-end">
                        <Button
                            @click="addToCart"
                            class="bg-orange-500 hover:bg-orange-600"
                        >
                            Tambah
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Left Column: Cart Items -->
                <div class="lg:col-span-2">
                    <!-- Cart Items -->
                    <div class="rounded-lg border bg-white p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Keranjang ({{ cart.length }} item)
                        </h3>

                        <div
                            v-if="cart.length === 0"
                            class="py-8 text-center text-muted-foreground"
                        >
                            Keranjang masih kosong
                        </div>

                        <div v-else class="overflow-x-auto">
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
                                            Harga
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-sm font-semibold"
                                        >
                                            Qty
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-sm font-semibold"
                                        >
                                            Subtotal
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
                                        v-for="(item, index) in cart"
                                        :key="index"
                                        class="border-b hover:bg-gray-50"
                                    >
                                        <td class="px-6 py-3">
                                            {{ item.product_name }}
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            {{
                                                formatCurrency(item.unit_price)
                                            }}
                                        </td>
                                        <td class="px-6 py-3">
                                            <input
                                                :value="item.quantity"
                                                @input="
                                                    (e) =>
                                                        updateQuantity(
                                                            index,
                                                            (
                                                                e.target as HTMLInputElement
                                                            ).value,
                                                        )
                                                "
                                                type="number"
                                                min="1"
                                                class="w-16 rounded border border-input px-2 py-1 text-center text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                            />
                                        </td>
                                        <td
                                            class="px-6 py-3 text-right font-medium"
                                        >
                                            {{ formatCurrency(item.subtotal) }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <button
                                                @click="removeFromCart(index)"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Payment -->
                <div class="space-y-4">
                    <!-- Totals Summary -->
                    <div class="rounded-lg border bg-white p-6">
                        <h3 class="mb-4 text-lg font-semibold">Ringkasan</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">
                                    Subtotal
                                </span>
                                <span class="font-medium">
                                    {{ formatCurrency(subtotal) }}
                                </span>
                            </div>

                            <div>
                                <label class="text-sm font-medium">
                                    Diskon (Rp)
                                </label>
                                <input
                                    v-model="discountAmount"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded border border-input px-2 py-1 text-right focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                />
                            </div>

                            <div
                                class="flex justify-between border-t border-b py-2 text-base font-bold"
                            >
                                <span>Total</span>
                                <span class="text-orange-600">
                                    {{ formatCurrency(finalTotal) }}
                                </span>
                            </div>

                            <div>
                                <label class="text-sm font-medium">
                                    Pembayaran (Rp)
                                </label>
                                <input
                                    v-model="paymentAmount"
                                    type="number"
                                    min="0"
                                    class="mt-1 w-full rounded border border-input px-2 py-1 text-right focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                />
                            </div>

                            <div
                                class="rounded bg-orange-50 px-3 py-2 font-bold text-orange-700"
                            >
                                <div
                                    class="text-xs font-normal text-orange-600"
                                >
                                    Kembalian
                                </div>
                                {{ formatCurrency(change) }}
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="rounded-lg border bg-white p-6">
                        <label class="text-sm font-medium">Catatan</label>
                        <textarea
                            v-model="form.notes"
                            placeholder="Catatan transaksi (opsional)"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <Button
                            @click="submitTransaction"
                            :disabled="cart.length === 0 || form.processing"
                            class="w-full bg-orange-500 hover:bg-orange-600 disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? 'Memproses...'
                                    : 'Selesaikan Transaksi'
                            }}
                        </Button>
                        <Link href="/transactions">
                            <Button variant="outline" class="w-full">
                                Batal
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
