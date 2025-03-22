import './bootstrap';
import './echo';
import '../css/base.css'
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
import ChatsUser from './pages/ChatsUser.vue';
import Friends from './pages/Friends.vue';
import CreatePost from './pages/CreatePost.vue';
store.commit('authStore/initializeStore');
const isAuthenticated = (to, from, next) => {
    const authenticated = store.getters['authStore/isAuthenticated']; // Получаем статус авторизации
    if (authenticated) {
        next(); // Позволяем доступ
    } else {
        next({ path: '/auth' }); // Перенаправляем на страницу авторизации
    }
};


const router = createRouter({
    routes: [
        {
            path: '/',
            component: Index,
            beforeEnter: isAuthenticated
        },
        {
            path: '/main',
            component: Main,
            beforeEnter: isAuthenticated
        },
        {
            path: '/chat/:id',
            component: Chat,
            beforeEnter: isAuthenticated
        },        
        {
            path: '/chats/user',
            component: ChatsUser,
            beforeEnter: isAuthenticated
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
            path: '/profile/:id',
            component: Profile,
            beforeEnter: isAuthenticated
        },
        {
            path: '/profile/:id/edit',
            component: ProfileEdit,
            beforeEnter: isAuthenticated
        },
        {
            path: '/friends',
            component: Friends,
            beforeEnter: isAuthenticated
        },
        {
            path: '/post/create',
            component: CreatePost,
            beforeEnter: isAuthenticated
        }


    ],
    history: createWebHistory()
})

const app = createApp(App);
app.use(store);
app.use(router);
app.mount('#app');