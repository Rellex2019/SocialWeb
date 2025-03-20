import './bootstrap';
import './echo';
import { createApp } from 'vue';
import App from './App.vue';
import store from './store';

import { createRouter, createWebHistory } from 'vue-router';

import Main from './pages/Main.vue';
import Chat from './pages/Chat.vue';
import Registration from './pages/Registration.vue';
import Auth from './pages/Auth.vue';
import Index from './pages/Index.vue';
import Profile from './pages/Profile.vue';
import ProfileEdit from './pages/ProfileEdit.vue';


const router = createRouter({
    routes: [
        {
            path: '/',
            component: Index
        },
        {
            path: '/main',
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
            path: '/profile',
            component: Profile
        },
        {
            path: '/profile/edit',
            component: ProfileEdit
        }

    ],
    history: createWebHistory()
})

const app = createApp(App);
app.use(store);
app.use(router);
app.mount('#app');