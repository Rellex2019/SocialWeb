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
import ChatsUser from './pages/ChatsUser.vue';
import Friends from './pages/Friends.vue';
import CreatePost from './pages/CreatePost.vue';
import AddCategory from './pages/AddCategory.vue';
store.commit('authStore/initializeStore');
const isAuthenticated = (to, from, next) => {
    const authenticated = store.getters['authStore/isAuthenticated'];
    if (authenticated) {
        next(); 
    } else {
        next({ path: '/auth' }); 
    }
};
const isAdmin = (to, from, next) => {
    const user = store.getters['authStore/user'];
    if (user && user.role_id == 1) {
        next(); 
    } else {
        next({ path: '/' }); 
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
            path: '/category/create',
            component: AddCategory,
            beforeEnter: isAdmin
        },
        {
            path: '/post/create/:post?',
            name: 'post.create',
            component: CreatePost,
            props: (route) => ({ post: route.query.post }),
            beforeEnter: isAuthenticated
        },



    ],
    history: createWebHistory()
})

const app = createApp(App);
app.use(store);
app.use(router);
app.mount('#app');