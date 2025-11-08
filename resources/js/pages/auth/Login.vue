<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { login, register } from '@/routes';
import InputError from '@/components/InputError.vue';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(login.url(), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <div class="min-h-screen bg-gray-900 text-white flex antialiased">
        <!-- Left Panel: Branding -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-gray-800/50 p-12 border-r border-gray-700/50">
            <div class="text-center max-w-md">
                <Link href="/">
                    <AppLogo class="mx-auto h-24 w-auto text-cyan-400" />
                </Link>
                <h1 class="mt-8 text-4xl font-extrabold tracking-tight text-white">
                    ¡Bienvenido!
                </h1>
                <p class="mt-4 text-lg text-gray-400">
                    Inicia sesión para acceder a tu cuenta y explorar lo último en tecnología.
                </p>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-center text-cyan-400 mb-8">Iniciar Sesión</h2>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Label for="email" class="text-gray-300">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full bg-gray-800 border-gray-600 text-white placeholder-gray-400 focus:ring-cyan-500 focus:border-cyan-500"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="tu@email.com"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <Label for="password" class="text-gray-300">Contraseña</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full bg-gray-800 border-gray-600 text-white placeholder-gray-400 focus:ring-cyan-500 focus:border-cyan-500"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center">
                        <Checkbox id="remember" v-model:checked="form.remember" name="remember" class="border-gray-600 data-[state=checked]:bg-cyan-600" />
                        <Label for="remember" class="ml-2 text-sm font-medium text-gray-400 cursor-pointer">Recuérdame</Label>
                    </div>

                    <div>
                        <Button type="submit" class="w-full justify-center bg-cyan-600 hover:bg-cyan-700 text-white font-bold" :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                            <span v-if="form.processing">Iniciando...</span>
                            <span v-else>Iniciar Sesión</span>
                        </Button>
                    </div>
                </form>

                <div v-if="register" class="mt-8 text-center">
                    <p class="text-gray-400">
                        ¿No tienes una cuenta?
                        <Link :href="register.url()" class="font-semibold text-cyan-500 hover:text-cyan-400">
                            Regístrate aquí
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>