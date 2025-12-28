<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/login';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase title="Bakery Inventory">
        <Head title="Login" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="font-semibold">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="font-semibold">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <InputError :message="errors.password" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full rounded-lg bg-orange-500 py-2.5 font-semibold text-white shadow-md transition-all duration-300 hover:bg-orange-600 hover:shadow-lg"
                    :tabindex="3"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    {{ processing ? 'Sedang Login...' : 'Login' }}
                </Button>
            </div>
        </Form>
    </AuthBase>
</template>
