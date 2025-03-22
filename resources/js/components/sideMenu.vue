<template>
    <div class="navbar">
        <RouterLink to="/friends" class="nav-item">
            <img :src="linkApp + '/img/welcome_img/icon_link_page_3.png'" alt="Друзья">
        </RouterLink>
        <RouterLink to="/chats/user" class="nav-item">
            <img :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" alt="Сообщения">
        </RouterLink>
        <RouterLink to="/" class="nav-item">
            <img :src="linkApp + '/img/welcome_img/icon_category_mobile.png'" alt="Категории">
        </RouterLink>
        <RouterLink to="/" class="nav-item">
            <img :src="linkApp + '/img/welcome_img/Mask group.png'" alt="Новости">
        </RouterLink>
    </div>

    <div class="sidebar">
        <div class="avatar_block">
            <RouterLink :to="`/profile/${user.id}`">
                <img v-if="user.user_info && user.user_info.avatar" :src="linkApp + '/storage/' + user.user_info.avatar"
                    class="avatar_block_nav" alt="Фото">
                <img v-else :src="linkApp + '/img/welcome_img/avatar_block_nav.png'" class="avatar_block_nav" alt="Фото">
                <p class="name_user_nav">{{ user.user_info.name + ' ' + user.user_info.surname }}</p>
            </RouterLink>
        </div>
        <div class="container">
            <div class="links_nav">
                <div class="links_page">
                    <img :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" alt="" class="icon_link">
                    <RouterLink to="/chats/user" class="name_link">Мои чаты</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/welcome_img/icon_link_page_2.png'" alt="" class="icon_link">
                    <RouterLink to="/" class="name_link">Новости</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/welcome_img/icon_link_page_3.png'" alt="" class="icon_link">
                    <RouterLink to="/friends" class="name_link">Друзья</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/welcome_img/icon_link_page_4.png'" alt="" class="icon_link">
                    <RouterLink to="/post/create" class="name_link">Создать пост</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/welcome_img/icon_link_page_5.png'" alt="" class="icon_link">
                    <RouterLink to="/post/create" class="name_link">Категории</RouterLink>
                </div>
            </div>

            <div class="exit_link_nav">
                <img :src="linkApp + '/img/welcome_img/icon_link_page_6.png'" alt="" class="icon_link">
                <a @click="logOut" class="exit_link">Выход</a>
            </div>
        </div>
    </div>
</template>
<script>
import { RouterLink } from 'vue-router';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: 'sideMenu',
    data() {
        return {
            linkApp: "",
        }
    },
    methods: {
        openPopup() {
            document.getElementById("popup").style.display = "flex";
        },
        closePopup() {
            document.getElementById("popup").style.display = "none";
        },
        async logOut() {
            await axios.get("/logout").then((response) => {
                this.$router.push("/auth");
                this.$store.commit('authStore/logout');
            });
        },
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
}
</script>
<style scoped>
* {
    font-family: "Unbounded", serif;
    font-optical-sizing: auto;
    color: black;
    text-decoration: none;
    margin: 0;
    padding: 0;
}


body {
    overflow-x: hidden;
    background-color: #181C22;
}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
}

/* -------------------------------------------------------------------------- */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 20.83vw;
    height: 100%;
    background-color: #181C22;
    padding: 1.04vw;
    box-shadow: 0.10vw 0 0.26vw rgba(0, 0, 0, 0.1);
}

.sidebar a {
    display: block;
    padding: 0.52vw 0;
    color: #ffffff;
    text-decoration: none;
}

.sidebar a:hover {
    /* background-color: #ddd; */
}

.content {
    margin-left: 20.83vw;
    padding: 1.04vw;
}

.avatar_block {
    width: 17.50vw;
    height: 16.46vw;
    background: #22232F;
    border-radius: 0.52vw;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.name_user_nav {
    margin-top: 1.35vw;
    width: 11.56vw;
    font-weight: 500;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.avatar_block_nav {
    width: 11.20vw;
    height: 11.20vw;
}

.links_nav {
    margin-top: 3.65vw;
}

.links_page,
.exit_link_nav {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.77vw;
}

.icon_link {
    padding-right: 1.41vw;
    width: 1.82vw;
    height: 1.72vw;
}

.name_link,
.exit_link {
    width: 9.22vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.exit_link_nav {
    margin-top: 7.21vw;
}

@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;
    }
}
</style>