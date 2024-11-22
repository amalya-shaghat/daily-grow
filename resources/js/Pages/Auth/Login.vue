<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in"/>

        <div
            class="min-h-screen bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-700 mb-4">Welcome Back!</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Log in to continue to your account</p>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="email" value="Email" class="text-gray-700"/>
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.email"/>
                    </div>
                    <div class="mb-4">
                        <InputLabel for="password" value="Password" class="text-gray-700"/>
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.password"/>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center text-sm text-gray-600">
                            <Checkbox name="remember" v-model:checked="form.remember"/>
                            <span class="ml-2">Remember me</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-500 hover:underline"
                        >
                            Forgot your password?
                        </Link>
                    </div>
                    <div class="flex justify-center">
                        <PrimaryButton
                            class="w-full py-3 px-6 bg-blue-500 text-white font-bold rounded-full hover:bg-blue-600 transition duration-300 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
                <p class="text-center text-sm text-gray-600 mt-6">
                    Don't have an account?
                    <Link :href="route('register')" class="text-blue-500 hover:underline">
                        Sign up here
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
