<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="min-h-screen bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-700 mb-4">Create an Account</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Join us and start your journey today</p>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="name" value="Name" class="text-gray-700" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="email" value="Email" class="text-gray-700" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.email" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password" value="Password" class="text-gray-700" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.password" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-full p-3 focus:ring focus:ring-blue-500 focus:outline-none"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2 text-red-500" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <Link
                            :href="route('login')"
                            class="text-sm text-blue-500 hover:underline"
                        >
                            Already have an account?
                        </Link>
                        <PrimaryButton
                            class="py-3 px-6 bg-blue-500 text-white font-bold rounded-full hover:bg-blue-600 transition duration-300 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Register
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
