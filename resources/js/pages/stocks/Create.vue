<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    products?: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inventory',
        href: '#',
    },
    {
        title: 'Stok',
        href: '/stocks',
    },
    {
        title: 'Input Stok Masuk',
        href: '#',
    },
];

const form = useForm({
    product_id: '',
    quantity: '',
    notes: '',
});

const handleSubmit = () => {
    form.post('/stocks');
};
</script>

<template>
    <Head title="Input Stok Masuk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <HeadingSmall
                title="Input Stok Masuk"
                description="Catat stok produk yang masuk dari hasil produksi"
            />

            <!-- Form Card -->
            <div class="rounded-lg border bg-white p-6 shadow-sm">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Produk Select -->
                    <div class="space-y-2">
                        <Label for="product" class="font-semibold">
                            Pilih Produk <span class="text-red-500">*</span>
                        </Label>
                        <select
                            id="product"
                            v-model="form.product_id"
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                        >
                            <option value="">Pilih Produk</option>
                            <option
                                v-for="product in products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.product_id" />
                    </div>

                    <!-- Quantity Input -->
                    <div class="space-y-2">
                        <Label for="quantity" class="font-semibold">
                            Jumlah <span class="text-red-500">*</span>
                        </Label>
                        <Input
                            id="quantity"
                            v-model.number="form.quantity"
                            type="number"
                            placeholder="Masukkan jumlah stok"
                            min="1"
                        />
                        <InputError :message="form.errors.quantity" />
                    </div>

                    <!-- Notes Textarea -->
                    <div class="space-y-2">
                        <Label for="notes" class="font-semibold">
                            Catatan (Opsional)
                        </Label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            placeholder="Contoh: Produksi pagi shift 1, Kue hari ini..."
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                            rows="4"
                        />
                        <InputError :message="form.errors.notes" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <Button
                            type="submit"
                            class="bg-orange-500 hover:bg-orange-600"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing"
                                >Simpan Stok Masuk</span
                            >
                            <span v-else>Menyimpan...</span>
                        </Button>
                        <Link href="/stocks">
                            <Button type="button" variant="outline">
                                Batal
                            </Button>
                        </Link>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <p class="text-sm text-blue-900">
                    <strong>Tips:</strong> Isi produk yang telah diproduksi.
                    Catatan membantu Anda mengingat detail stok (shift, batch,
                    dll).
                </p>
            </div>
        </div>
    </AppLayout>
</template>
