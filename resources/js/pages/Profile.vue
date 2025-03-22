<template>
    <SideMenu />



    <div class="content">
        <div class="container">
            <div class="block1">
                <a href="./welcome.html"><img class="back_arrow"
                        :src="linkApp + '/img/profile_img/back_arrow.png'"></img></a>
                <a href="./welcome.html"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></a>
                <a href="./new_post.html"><img :src="linkApp + '/img/welcome_img/add_post_icon.png'" alt=""
                        class="add_post"></a>

            </div>
        </div>
    </div>

    <div class="bckgr">
        <img :src="linkApp + '/img/profile_img/backgr.png'" alt="" class="backgr_main">
    </div>

    <div class="content">
        <div class="container">
            <div class="block2">

                <div class="my_profile">
                    <img v-if="userInfo.user_info && userInfo.user_info.avatar"
                        :src="linkApp + '/storage/' + userInfo.user_info.avatar" alt="" class="my_profile_avatar">
                    <img v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="" class="my_profile_avatar">
                    <div class="text_content">
                        <h1 class="my_name" v-if="userInfo.user_info">{{ userInfo.user_info.name + ' '+userInfo.user_info.surname}}</h1>
                        <p class="count_friends" v-if="userInfo.all_friends">{{ userInfo.all_friends.length }} друга</p>
                        <p class="my_quote" v-if="userInfo.user_info">{{ userInfo.user_info.quote ?? 'Цитата не установлена' }} </p>
                        <RouterLink v-if="profileId == user.id" :to="`/profile/${userInfo.id}/edit`"
                            class="btn_edit_profile">Изменить профиль</RouterLink>
                        <button @click="sendRequestToFriend(userInfo.id)" class="btn_edit_profile"
                            v-if="profileId != user.id && !isFriend()">Добавить в друзья</button>
                        <button @click="deleteFriend(userInfo.id)" class="btn_edit_profile"
                            v-else-if="profileId != user.id && isFriend()">Удалить из друзей</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="content">
        <div class="container">
            <div class="block4">
                <p class="main_title" >{{profileId == user.id?'Мои посты':'Посты'}}</p>
                <div  v-for="post in userPosts" class="">
                    <div class="block4_account">
                        <div class="account">
                            <img v-if="post.user && post.user.user_info && post.user.user_info.avatar" :src="linkApp + '/storage/'+post.user.user_info.avatar" alt="" class="avatar_account">
                            <img v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="" class="avatar_account">
                        </div>
                        <div class="post_account">
                            <p class="category_post">{{ post.category.name}}</p>
                            <p class="name_account">{{ post.user.user_info.name + ' ' + post.user.user_info.surname }}</p>
                        </div>
                    </div>
                    <div class="block4_posts">
                        <div class="post">
                            <img class="post_img" v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/storage/' + photo.path">
                            <p class="description">{{ post.body }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</template>
<script>
import { RouterLink } from 'vue-router';
import FriendBar from '../components/friendBar.vue';
import SideMenu from '../components/sideMenu.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import Post from '../components/Post.vue';


export default {
    name: 'Profile',
    data() {
        return {
            profileId: this.$route.params.id,
            linkApp: '',
            userPosts: [],
            userInfo: [],
        }
    },
    methods: {
        getPosts() {
            axios.get(`/posts/user/${this.profileId}`).then(response => {
                this.userPosts = response.data;
            })
                .catch((error) => {
                    this.userPosts = error.response;
                })
        },
        async getUser() {
            await axios.get(`/user/info/${this.profileId}`)
                .then(response => {
                    this.userInfo = response.data;
                })
        },
        toggleLike(postId) {
            axios.post(`/post/${postId}/like`, {})
                .then(response => {
                    this.getPosts();
                })
        },
        sendRequestToFriend(id) {
            axios.post('/friend/send_friend_request', { 'id': id })
                .then(response => {
                })
        },
        deleteFriend(id) {
            axios.delete(`/friend/${id}/delete`, {})
                .then(response => {
                    console.log(response.data)
                })
        },
        isFriend() {
            if (!this.userInfo.all_friends) return false;
            return this.userInfo.all_friends.some(friend => friend.id === this.user.id);
        },
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted() {
        this.getUser();
        this.getPosts();


    },
    watch: {
        '$route.params.id': function(newId) {
            this.fetchData(newId);
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    components: {
        SideMenu,
        FriendBar,
        Post
    }
}
</script>
<style scoped>
.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
    margin-left: 60vw;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
}

/* -----------------------------block2--------------------------------- */
.my_profile {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 2.76vw;
}

.my_profile_avatar {
    width: 11.20vw;
    height: 11.20vw;
}

.my_name {
    width: 39.17vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #FFFFFF;
    margin-bottom: 1.04vw;
}

.count_friends {
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    margin-bottom: 0.94vw;
}

.my_quote {
    width: 46.30vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    padding-bottom: 1.77vw;
}

.btn_edit_profile {
    font-weight: 500;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    padding: 0.52vw 0.31vw;
    background: #C68DFE;
    border-radius: 0.52vw;
}

/* ------------------------------------block3----------------------------- */
.main_title {
    width: 11.46vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #FFFFFF;
    margin-top: 6.61vw;
    margin-bottom: 1.93vw;
}

.block4 {
    display: flex;
    flex-direction: column;
    gap: 1.72vw;
}

.block4_account {
    display: flex;
    flex-direction: row;
    gap: 1.67vw;
    align-items: center;
}

.account {}

.avatar_account {
    width: 4.58vw;
    height: 4.58vw;
}

.post_account {
    display: flex;
    flex-direction: column;
    gap: 0.57vw;
}

.category_post {
    display: flex;
    justify-content: center;
    width: 7.24vw;
    height: 1.77vw;
    background: #C68DFE;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
}

.name_account {
    font-weight: 500;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #C68DFE;
}

.post_img {
    width: 45.68vw;
    height: 30.10vw;
    border-radius: 0.52vw;
}

.description {
    width: 45.68vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    margin-top: 1.35vw;
    margin-bottom: 4.17vw;
}

/* -----------------------------------block5--------------------------------------- */
.block5 {}

.friends {
    position: absolute;
    width: 22.03vw;
    height: 36.93vw;
    border: 0.05vw solid rgba(198, 141, 254, 0.25);
    border-radius: 0.52vw;
    margin-top: -46.35vw;
    margin-left: 49.48vw;
    margin-top: 11.46vw;
}

.main_title_friends {
    width: 7.86vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    margin-left: 1.04vw;
    margin-top: 1.04vw;
    margin-bottom: 2.60vw;
}

.one_friend {
    margin-top: 0.83vw;
    margin-left: 1.04vw;
    width: 19.22vw;
    background: rgba(206, 157, 255, 0.2);
    border-radius: 0.52vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding-top: 0.78vw;
    padding-bottom: 0.78vw;
    padding-left: 0.42vw;
    padding-right: 0.42vw;
}

.avatar_friend {
    width: 2.71vw;
    height: 2.71vw;
}

.name_friend {
    width: 9.11vw;
    font-weight: 400;
    font-size: 0.73vw;
    line-height: 0.89vw;
    color: #FFFFFF;
    margin-bottom: 0.78vw;
}

.quote_friend {
    font-weight: 400;
    font-size: 0.63vw;
    line-height: 0.78vw;
    color: rgba(255, 255, 255, 0.65);
}

.avatar_icon {
    width: 2.03vw;
    height: 1.93vw;
}

.text_friend {
    margin-left: -3.13vw;
}

.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    /* position: absolute; */
    position: fixed;
    margin-left: 20.83vw;
    margin-top: 5.21vw;
    width: 75.83vw;
    height: 55.57vw;
}

.navbar,
.add_post {
    display: none;
}

/* -----------------------------------media----------------------------- */
@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;

    }

    .avatar_mobile {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .block1 {
        display: flex;
        align-items: center;
    }

    .main_title {
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
        width: 35vw;
    }

    .add_post {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .logo,
    .friends {
        display: none;
    }

    .navbar {
        display: block;
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #181C22;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 18.75vw;
        border-top: 0.31vw solid #C68DFE;
    }



    .nav-item img {
        width: 9.38vw;
        height: 9.38vw;
    }

    .nav-item {
        color: white;
        text-align: center;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .nav-item:hover {
        opacity: 0.7;
    }

    .custom-select {
        width: 57.50vw;
        background: #C68DFE;
        border-radius: 6.25vw;

        font-weight: 300;
        font-size: 3.75vw;
        line-height: 4.69vw;
        color: #FFFFFF;
        padding: 1.47vw 1.09vw;

    }

    .arrow-icon {
        width: 4.69vw;
        height: 2.19vw;
        margin-left: 51.56vw;
        margin-top: 3.13vw;
    }

    .option-list {
        width: 56.25vw;
        display: none;
        position: absolute;
        background-color: #F4F4F4;
        border: 0.05vw solid #ccc;
        border-radius: 1.30vw;
        z-index: 1000;
        font-size: 3.04vw;
        padding: 2.04vw;
    }

    .option-item {
        padding: 1.5vw;
    }


    .avatar_account {
        width: 13.13vw;
        height: 13.13vw;
    }

    .category_post {
        width: 25.63vw;
        height: 5.63vw;
        background: #C68DFE;
        border-radius: 9.38vw;
        font-weight: 300;
        font-size: 11px;
        line-height: 4.38vw;
        color: #FFFFFF;
    }

    .name_account {
        font-size: 3.75vw;
    }

    .post_account {
        gap: 3.57vw;
    }

    .post_img {
        width: 93.75vw;
        height: 51.56vw;
    }

    .description {
        width: 93.75vw;
        font-weight: 300;
        font-size: 3.44vw;
        line-height: 4.38vw;
        color: #FFFFFF;
    }

    .block4 {
        gap: 3.72vw;
    }

    .back_arrow {
        width: 5.20vw;
        height: 6.98vw;
    }

    .my_profile_avatar {
        width: 91px;
        height: 91px;
    }

    .my_name {
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
        margin-bottom: 4.04vw;
    }

    .count_friends {
        font-weight: 300;
        font-size: 3.75vw;
        line-height: 4.69vw;
        /* identical to box height */
        color: #FFFFFF;
        margin-bottom: 2.94vw;
    }

    .my_quote {
        width: 62.19vw;
        font-weight: 400;
        font-size: 3.75vw;
        line-height: 4.69vw;
        color: #FFFFFF;
        padding-bottom: 2.77vw;
    }

    .btn_edit_profile {
        width: 55vw;
        height: 10.31vw;
        background: #C68DFE;
        border-radius: 3.13vw;
        font-weight: 400;
        font-size: 3.75vw;
        line-height: 4.69vw;
        color: #FFFFFF;
        padding: 0.52vw 5.31vw;
    }




}

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