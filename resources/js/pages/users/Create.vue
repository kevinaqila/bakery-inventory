<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    roles: string[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data',
        href: '#',
    },
    {
        title: 'User',
        href: '/users',
    },
    {
        title: 'Tambah',
        href: '#',
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'karyawan',
});

const submit = () => {
    form.post('/users', {
        onSuccess: () => {
            alert('User berhasil dibuat!');
        },
    });
};
</script>

<template>
    <Head title="Tambah User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <HeadingSmall
                title="Tambah User"
                description="Buat user baru untuk aplikasi"
            />

            <div
                class="mx-auto w-full max-w-2xl rounded-lg border bg-white p-6"
            >
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="text-sm font-medium">Nama</label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Nama lengkap"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        />
                        <p
                            v-if="form.errors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="email@example.com"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        />
                        <p
                            v-if="form.errors.email"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="text-sm font-medium">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        />
                        <p
                            v-if="form.errors.password"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="text-sm font-medium">Role</label>
                        <select
                            v-model="form.role"
                            class="mt-2 w-full rounded-lg border border-input bg-background px-3 py-2 focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        >
                            <option
                                v-for="role in roles"
                                :key="role"
                                :value="role"
                            >
                                {{
                                    role.charAt(0).toUpperCase() + role.slice(1)
                                }}
                            </option>
                        </select>
                        <p
                            v-if="form.errors.role"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.role }}
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-4">
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </Button>
                        <Link href="/users" class="flex-1">
                            <Button variant="outline" class="w-full">
                                Batal
                            </Button>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
