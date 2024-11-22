<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    isOpen: Boolean,
});
const emit = defineEmits(['close']);

const form = useForm({
    file: null,
});

const submitForm = () => {
    form.post('/import', {
        onSuccess: () => {
            form.reset();
            emit('close');
            window.location.href = '/dashboard';
        },
        onError: (errors) => {
            console.error('Ошибка при загрузке файла:', errors);
        },
    });
};


const closePopup = () => {
    emit('close');
};
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-md max-w-lg w-full">
            <h2 class="text-xl font-bold mb-4">Загрузка выписки</h2>
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="file" class="block font-bold mb-2">Выберите файл (.txt)</label>
                    <input
                        type="file"
                        id="file"
                        class="block w-full border rounded p-2"
                        @change="form.file = $event.target.files[0]"
                    />
                    <div v-if="form.errors.file" class="text-red-500 mt-2">
                        {{ form.errors.file }}
                    </div>
                </div>
                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="closePopup"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-400"
                    >
                        Отмена
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Загрузить
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
