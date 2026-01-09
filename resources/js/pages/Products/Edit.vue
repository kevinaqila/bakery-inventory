<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    unit: string;
    purchase_price: number;
    selling_price: number;
    stock_quantity: number;
    reorder_level: number;
}

const props = defineProps<{
    product: Product;
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inventory', href: '#' },
    { title: 'Produk', href: '/products' },
    { title: 'Edit Produk', href: '#' },
];

const form = useForm({
    name: props.product.name,
    sku: props.product.sku,
    description: props.product.description || '',
    category_id: props.product.category_id.toString(),
    unit: props.product.unit,
    purchase_price: props.product.purchase_price,
    selling_price: props.product.selling_price,
    stock_quantity: props.product.stock_quantity,
    reorder_level: props.product.reorder_level,
});

const handleSubmit = () => {
    form.put(`/products/${props.product.id}`);
};
</script>

<template>
    <Head title="Edit Produk" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <HeadingSmall
                title="Edit Produk"
                description="Ubah informasi produk"
            />

            <!-- Form Card -->
            <div class="rounded-lg border bg-white p-6 shadow-sm">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Name & SKU -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="name" class="font-semibold">
                                Nama Produk <span class="text-red-500">*</span>
                            </Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Contoh: Roti Tawar"
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="sku" class="font-semibold">
                                SKU <span class="text-red-500">*</span>
                            </Label>
                            <Input
                                id="sku"
                                v-model="form.sku"
                                type="text"
                                placeholder="Contoh: SKU001"
                            />
                            <InputError :message="form.errors.sku" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <Label for="description" class="font-semibold">
                            Deskripsi
                        </Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            placeholder="Deskripsi produk (opsional)..."
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                            rows="3"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Category & Unit -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="category" class="font-semibold">
                                Kategori <span class="text-red-500">*</span>
                            </Label>
                            <select
                                id="category"
                                v-model="form.category_id"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                            >
                                <option value="">Pilih Kategori</option>
                                <option
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id"
                                >
                                    {{ cat.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.category_id" />
                        </div>

                        <div class="space-y-2">
                            <Label for="unit" class="font-semibold">
                                Satuan <span class="text-red-500">*</span>
                            </Label>
                            <select
                                id="unit"
                                v-model="form.unit"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                            >
                                <option value="pcs">Pcs</option>
                                <option value="box">Box</option>
                            </select>
                            <InputError :message="form.errors.unit" />
                        </div>
                    </div>

                    <!-- Prices -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="purchase_price" class="font-semibold">
                                Harga Beli <span class="text-red-500">*</span>
                            </Label>
                            <Input
                                id="purchase_price"
                                v-model.number="form.purchase_price"
                                type="number"
                                placeholder="0"
                            />
                            <InputError :message="form.errors.purchase_price" />
                        </div>

                        <div class="space-y-2">
                            <Label for="selling_price" class="font-semibold">
                                Harga Jual <span class="text-red-500">*</span>
                            </Label>
                            <Input
                                id="selling_price"
                                v-model.number="form.selling_price"
                                type="number"
                                placeholder="0"
                            />
                            <InputError :message="form.errors.selling_price" />
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <Label for="stock_quantity" class="font-semibold">
                                Stok
                            </Label>
                            <Input
                                id="stock_quantity"
                                v-model.number="form.stock_quantity"
                                type="number"
                                placeholder="0"
                            />
                            <InputError :message="form.errors.stock_quantity" />
                        </div>

                        <div class="space-y-2">
                            <Label for="reorder_level" class="font-semibold">
                                Reorder Level
                            </Label>
                            <Input
                                id="reorder_level"
                                v-model.number="form.reorder_level"
                                type="number"
                                placeholder="0"
                            />
                            <InputError :message="form.errors.reorder_level" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <Button
                            type="submit"
                            class="bg-orange-500 hover:bg-orange-600"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Update Produk</span>
                            <span v-else>Menyimpan...</span>
                        </Button>
                        <Link href="/products">
                            <Button type="button" variant="outline">
                                Batal
                            </Button>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
