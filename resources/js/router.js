import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/', component: () => import('./components/Home.vue') },
    { path: '/dashboard', component: () => import('./components/Dashboard.vue') },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
