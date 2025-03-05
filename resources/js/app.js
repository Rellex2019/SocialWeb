import './bootstrap';
import './echo';
import { createApp } from 'vue';
import App from './App.vue';


import { createRouter, createWebHistory } from 'vue-router';

import Main from './pages/Main.vue';



const router = createRouter({
    routes: [
        {
            path: '/',
            component: Main
        },
    ],
    history: createWebHistory()
})

const app = createApp(App);
app.use(router);
app.mount('#app');