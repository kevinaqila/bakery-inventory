<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inventory', href: '#' },
    { title: 'Kategori', href: '/categories' },
    { title: 'Buat Kategori', href: '#' },
];

const form = useForm({
    name: '',
    slug: '',
    description: '',
});

const generateSlug = () => {
    form.slug = form.name
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]/g, '');
};

const handleSubmit = () => {
    form.post('/categories');
};
</script>

<template>
    <Head title="Buat Kategori" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <HeadingSmall
                title="Buat Kategori"
                description="Tambahkan kategori produk baru"
            />

            <!-- Form Card -->
            <div class="rounded-lg border bg-white p-6 shadow-sm">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <Label for="name" class="font-semibold">
                            Nama Kategori <span class="text-red-500">*</span>
                        </Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            @input="generateSlug"
                            type="text"
                            placeholder="Contoh: Roti Tawar"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Slug -->
                    <div class="space-y-2">
                        <Label for="slug" class="font-semibold">
                            Slug <span class="text-red-500">*</span>
                        </Label>
                        <Input
                            id="slug"
                            v-model="form.slug"
                            type="text"
                            placeholder="Contoh: roti-tawar"
                        />
                        <InputError :message="form.errors.slug" />
                        <p class="text-xs text-muted-foreground">
                            Slug otomatis generate dari nama
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <Label for="description" class="font-semibold">
                            Deskripsi
                        </Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            placeholder="Deskripsi kategori (opsional)..."
                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus:border-orange-400 focus:ring-2 focus:ring-orange-200 focus:outline-none"
                            rows="4"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <Button
                            type="submit"
                            class="bg-orange-500 hover:bg-orange-600"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing"
                                >Buat Kategori</span
                            >
                            <span v-else>Menyimpan...</span>
                        </Button>
                        <Link href="/categories">
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
