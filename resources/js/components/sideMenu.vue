<template>
    <div class="sidebar">
        <div class="container">
            <div class="links_nav">
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/logo.png'" alt="" class="logo" />
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/profile.png'" alt="" class="icon_link" />
                    <RouterLink :to="`/profile/${user.id}`" class="name_link">Профиль</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/icons_chat.png'" alt="" class="icon_link" />
                    <RouterLink to='/chats/user' class="name_link">Мои чаты</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/icons_news.png'" alt="" class="icon_link" />
                    <RouterLink to='/' class="name_link">Новости</RouterLink>
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/icons_friends.png'" alt="" class="icon_link" />
                    <RouterLink to='/friends' class="name_link">Друзья</RouterLink> 
                </div>
                <div class="links_page">
                    <img :src="linkApp + '/img/icons/icons_new_post.png'" alt="" class="icon_link" />
                    <RouterLink to="/post/create" class="name_link">Создать пост</RouterLink>
                </div>
                <div class="links_page"v-if="user.role_id == 1">
                    <img :src="linkApp + '/img/icons/icons_category.png'" alt="" class="icon_link" />
                    <RouterLink to="/category/create" class="name_link">Создать категорию</RouterLink>
                </div>
                <div class="exit_link_nav">
                    <img :src="linkApp + '/img/icons/icons_logout.png'" alt="" class="icon_link" />
                    <div @click="logOut" class="exit_link">Выход</div>
                </div>
                <!-- <button class="open-popup" @click="openPopup">
                    Открыть всплывающее окно
                </button> -->
            </div>

            <!-- <div class="popup" id="popup">
                <div class="popup-content">
                    <span class="close" @click="closePopup">Нет, спасибо</span>
                    <div class="flex_popup">
                        <label class="lab_ok" for="">Привет, User</label><br />
                        <label class="lab_ok" for="">Дополнить профиль аватаркой?</label>
                        <img class="add_foto" :src="linkApp + '/img/edit_pr.png'" alt="" />
                        <label class="lab_ok2" for="">Какие категории вас интересуют?</label>

                        <div class="category_content">
                            <div v-for="item in 6" class="category_item">
                                <img class="img_category_item" :src="linkApp + '/img/category.png'" alt="" />
                                <p class="name_category">cпорт</p>
                            </div>
                        </div>
                        <p class="sd">Посмотреть еще</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>
<script>
import { RouterLink } from 'vue-router';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default{
    name: 'sideMenu',
    data(){
        return{
            linkApp: "",
        }
    },
    methods:{
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
                console.log(response.data);
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
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 18.23vw;
    height: 100%;
    background-color: #865df8;
    padding: 1.04vw;
    border-radius: 0vw 1.56vw 0vw 1.56vw;
    box-shadow: 0.1vw 0 0.26vw rgba(0, 0, 0, 0.1);
}
.sidebar a {
    display: block;
    padding: 0.52vw 0;
    color: #ffffff;
    text-decoration: none;
}
.container {
    display: flex;
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
    margin-left: 2.6vw;
}
.logo {
    width: 11.2vw;
    height: 1.51vw;

    margin-bottom: 4.17vw;
    margin-top: -1.6vw;
}
.icon_link {
    padding-right: 1.41vw;
    /* width: 1.82vw; */
    height: 1.72vw;
}
.name_link,
.exit_link {
    font-family: "Unbounded", serif;
    cursor: pointer;
    width: 9.22vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
}
@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }}
</style>