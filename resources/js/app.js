import './bootstrap';
import './echo';
import { createApp } from 'vue';
import App from './App.vue';


import { createRouter, createWebHistory } from 'vue-router';

import Main from './pages/Main.vue';
import Chat from './pages/Chat.vue';
import Registration from './pages/Registration.vue';
import Auth from './pages/Auth.vue';
import Index from './pages/Index.vue';


const router = createRouter({
    routes: [
        {
            path: '/',
            component: Main
        },
        {
            path: '/chat/:id',
            component: Chat
        },
        {
            path: '/registration',
            component: Registration
        },
        {
            path: '/auth',
            component: Auth
        },
        {
            path: '/index',
            component: Index
        }
    ],
    history: createWebHistory()
})

const app = createApp(App);
app.use(router);
app.mount('#app');