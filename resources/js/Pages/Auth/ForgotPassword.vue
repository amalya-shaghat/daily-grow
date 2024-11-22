<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password"/>

        <div
            class="min-h-screen bg-gradient-to-br from-purple-400 via-pink-500 to-red-500 flex items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-700 text-center mb-4">Forgot Password</h2>
                <p class="text-sm text-gray-500 text-center mb-6">
                    Don't worry! Just provide your email, and we'll send you a link to reset your password.
                </p>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="email" value="Email" class="text-gray-700"/>
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-pink-500 focus:outline-none"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.email"/>
                    </div>

                    <div class="flex items-center justify-center mt-6">
                        <PrimaryButton
                            class="py-3 px-6 bg-pink-500 text-white font-bold rounded-full hover:bg-pink-600 transition duration-300 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Send Reset Link
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
