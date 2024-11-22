<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ImportPopup from "@/Components/ImportPopup.vue";

const transactions = ref([]);
const isImportPopupOpen = ref(false);
const isDeletePopupOpen = ref(false);
const transactionToDelete = ref(null);
const toastMessage = ref(null);
const showToast = ref(false);
const isAddPopupOpen = ref(false);
const newTransaction = ref({
    operation_date: '',
    counterparty: '',
    amount: '',
    type: 'income',
    description: '',
});

const openAddPopup = () => {
    isAddPopupOpen.value = true;
};

const closeAddPopup = () => {
    isAddPopupOpen.value = false;
    resetNewTransaction();
};

const resetNewTransaction = () => {
    newTransaction.value = {
        operation_date: '',
        counterparty: '',
        amount: '',
        type: 'income',
        description: '',
    };
};

const addTransaction = async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRF token
        const response = await fetch('/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(newTransaction.value),
        });

        if (response.ok) {
            await fetchTransactions();
            displayToast('Операция успешно добавлена!', 'success');
            closeAddPopup();
        } else {
            displayToast('Ошибка добавления операции.', 'error');
            console.error('Ошибка добавления операции:', await response.text());
        }
    } catch (error) {
        displayToast('Ошибка добавления операции.', 'error');
        console.error('Ошибка добавления операции:', error);
    }
};

const fetchTransactions = async () => {
    try {
        const response = await fetch('/transactions');
        transactions.value = await response.json();
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    }
};

const openImportPopup = () => {
    isImportPopupOpen.value = true;
};

const closeImportPopup = () => {
    isImportPopupOpen.value = false;
};

const openDeletePopup = (transaction) => {
    transactionToDelete.value = transaction;
    isDeletePopupOpen.value = true;
};

const closeDeletePopup = () => {
    transactionToDelete.value = null;
    isDeletePopupOpen.value = false;
};

const displayToast = (message, type = 'success') => {
    toastMessage.value = { message, type };
    showToast.value = true;

    setTimeout(() => {
        showToast.value = false;
        toastMessage.value = null;
    }, 3000);
};

const confirmDeleteTransaction = async () => {
    if (!transactionToDelete.value) return;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Получаем CSRF-токен
        const response = await fetch(`/transactions/${transactionToDelete.value.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (response.ok) {
            displayToast('Операция успешно удалена!', 'success');
            await fetchTransactions();
        } else if (response.status === 403) {
            displayToast('Вы не можете удалить эту операцию, так как вы её не создавали.', 'error');
        } else {
            displayToast('Произошла ошибка при удалении.', 'error');
            console.error('Ошибка удаления:', await response.text());
        }
    } catch (error) {
        displayToast('Произошла ошибка при удалении.', 'error');
        console.error('Ошибка удаления операции:', error);
    } finally {
        closeDeletePopup();
    }
};


const copyTransaction = async (id) => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Получаем CSRF-токен
        const response = await fetch(`/transactions/copy/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (response.ok) {
            displayToast('Операция успешно скопирована!');
            await fetchTransactions();
        } else {
            console.error('Ошибка копирования операции:', await response.text());
        }
    } catch (error) {
        console.error('Ошибка копирования операции:', error);
    }
};

onMounted(fetchTransactions);
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <div class="p-6 bg-gray-100 min-h-screen">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Личный кабинет</h1>
                <p class="text-gray-600">Добро пожаловать в ваш личный кабинет!</p>
            </div>

            <div class="mb-6 flex gap-4">
                <button
                    @click="openImportPopup"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                >
                    Импорт выписки из 1С
                </button>
                <button
                    @click="openAddPopup"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                    Добавить операцию
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg p-4">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Последние операции</h2>
                <table class="table-auto w-full text-left">
                    <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-4 py-2">Создан пользователем</th>
                        <th class="px-4 py-2">Дата</th>
                        <th class="px-4 py-2">Контрагент</th>
                        <th class="px-4 py-2">Сумма</th>
                        <th class="px-4 py-2">Тип</th>
                        <th class="px-4 py-2">Описание</th>
                        <th class="px-4 py-2">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="transaction in transactions" :key="transaction.id" class="border-t">
                        <td class="px-4 py-2">{{ transaction.users[0]?.name }}</td>
                        <td class="px-4 py-2">{{ transaction.operation_date }}</td>
                        <td class="px-4 py-2">{{ transaction.counterparty }}</td>
                        <td
                            class="px-4 py-2"
                            :class="{ 'text-green-500': transaction.type === 'income', 'text-red-500': transaction.type === 'expense' }"
                        >
                            {{ transaction.amount }}
                        </td>
                        <td class="px-4 py-2">{{ transaction.type }}</td>
                        <td class="px-4 py-2">{{ transaction.description }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <button
                                @click="copyTransaction(transaction.id)"
                                class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600"
                            >
                                Копировать
                            </button>
                            <button
                                @click="openDeletePopup(transaction)"
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div
                v-if="isDeletePopupOpen"
                class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded shadow-md max-w-lg w-full">
                    <h2 class="text-xl font-bold mb-4">Подтверждение удаления</h2>
                    <p class="mb-4">
                        Вы уверены, что хотите удалить операцию
                        <strong>{{ transactionToDelete?.description }}</strong>?
                    </p>
                    <div class="flex justify-end space-x-4">
                        <button
                            @click="closeDeletePopup"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                        >
                            Отмена
                        </button>
                        <button
                            @click="confirmDeleteTransaction"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="isAddPopupOpen"
                class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded shadow-md max-w-lg w-full">
                    <h2 class="text-xl font-bold mb-4">Добавление операции</h2>
                    <form @submit.prevent="addTransaction">
                        <div class="mb-4">
                            <label class="block font-bold mb-2" for="operation_date">Дата операции</label>
                            <input
                                v-model="newTransaction.operation_date"
                                id="operation_date"
                                type="date"
                                class="block w-full border rounded p-2"
                                required
                            />
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-2" for="counterparty">Контрагент</label>
                            <input
                                v-model="newTransaction.counterparty"
                                id="counterparty"
                                type="text"
                                class="block w-full border rounded p-2"
                                required
                            />
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-2" for="amount">Сумма</label>
                            <input
                                v-model="newTransaction.amount"
                                id="amount"
                                type="number"
                                class="block w-full border rounded p-2"
                                required
                            />
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-2">Тип</label>
                            <select
                                v-model="newTransaction.type"
                                class="block w-full border rounded p-2"
                                required
                            >
                                <option value="income">Доход</option>
                                <option value="expense">Расход</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-2" for="description">Описание</label>
                            <textarea
                                v-model="newTransaction.description"
                                id="description"
                                class="block w-full border rounded p-2"
                                rows="3"
                            ></textarea>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button
                                @click="closeAddPopup"
                                type="button"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
                            >
                                Отмена
                            </button>
                            <button
                                type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                            >
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div
                v-if="showToast"
                class="fixed bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded shadow-md z-50"
                :class="{'bg-green-500 text-white': toastMessage?.type === 'success',
                         'bg-red-500 text-white': toastMessage?.type === 'error',}">
                {{ toastMessage?.message }}
            </div>

        </div>
        <ImportPopup :isOpen="isImportPopupOpen" @close="closeImportPopup"/>
    </AuthenticatedLayout>
</template>
