<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { login, register } from '@/routes';
import InputError from '@/components/InputError.vue';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(register.url(), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />
    <div class="min-h-screen bg-gray-900 text-white flex antialiased">
        <!-- Left Panel: Branding -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-gray-800/50 p-12 border-r border-gray-700/50">
            <div class="text-center max-w-md">
                <Link href="/">
                    <AppLogo class="mx-auto h-24 w-auto text-cyan-400" />
                </Link>
                <h1 class="mt-8 text-4xl font-extrabold tracking-tight text-white">
                    Crea tu Cuenta
                </h1>
                <p class="mt-4 text-lg text-gray-400">
                    Únete a nuestra comunidad para descubrir y adquirir lo último en innovación tecnológica.
                </p>
            </div>
        </div>

        <!-- Right Panel: Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-center text-cyan-400 mb-8">Registro</h2>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Label for="name" class="text-gray-300">Nombre Completo</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full bg-gray-800 border-gray-600 text-white placeholder-gray-400 focus:ring-cyan-500 focus:border-cyan-500"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="John Doe"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="email" class="text-gray-300">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full bg-gray-800 border-gray-600 text-white placeholder-gray-400 focus:ring-cyan-500 focus:border-cyan-500"
                            required
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
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <Label for="password_confirmation" class="text-gray-300">Confirmar Contraseña</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full bg-gray-800 border-gray-600 text-white placeholder-gray-400 focus:ring-cyan-500 focus:border-cyan-500"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div>
                        <Button type="submit" class="w-full justify-center bg-cyan-600 hover:bg-cyan-700 text-white font-bold" :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                            <span v-if="form.processing">Creando cuenta...</span>
                            <span v-else>Crear Cuenta</span>
                        </Button>
                    </div>
                </form>

                <div v-if="login" class="mt-8 text-center">
                    <p class="text-gray-400">
                        ¿Ya tienes una cuenta?
                        <Link :href="login.url()" class="font-semibold text-cyan-500 hover:text-cyan-400">
                            Inicia sesión aquí
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
