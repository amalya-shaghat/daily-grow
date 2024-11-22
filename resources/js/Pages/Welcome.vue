<script setup>
import {Head, Link} from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome"/>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white flex flex-col items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold mb-6">Welcome to <span class="text-yellow-300">DailyGrow</span></h1>
            <p class="text-lg mb-10">
                Build robust and scalable web applications with ease using Laravel.
            </p>

            <div class="flex justify-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-full shadow-lg text-lg font-bold transition"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 rounded-full shadow-lg text-lg font-bold transition"
                    >
                        Log In
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 rounded-full shadow-lg text-lg font-bold transition"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>
    </div>
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="text-center">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            <p class="mt-2">© {{ new Date().getFullYear() }} Laravel. All rights reserved.</p>
        </div>
    </footer>
</template>

<style>
a:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
}
</style>
