<template>
    <SideMenu />
    <div class="content" id="content">

        <div class="name_cart">
            <p v-if="profileId == user.id">Мой профиль</p>
            <p v-else>Профиль</p>
        </div>
        <div class="bckgr">
            <img :src="linkApp + '/img/back.png'" alt="" class="backgr_main">
        </div>
        <div class="bckgr2">
            <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2">
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>


        <div class="profile">
            <div class="img_profile">
                <img v-if="userInfo.info && userInfo.info.avatar" :src="linkApp + '/storage/' + userInfo.info.avatar"
                    alt="">
                <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="">

            </div>
            <div class="profile_text">
                <p class="name_accaunt" v-if="userInfo.info">{{ userInfo.info.name + ' ' +
                    userInfo.info.surname }}</p>
                <p class="friends_col" v-if="userInfo.friends">{{ userInfo.friends.length }} друга</p>
                <p class="quote" v-if="userInfo.info">{{ userInfo.info.quote ?? 'Цитата не установлена :(' }}
                </p>
                <RouterLink v-if="profileId == user.id" :to="`/profile/${profileId}/edit`" class="redact">Изменить
                    профиль</RouterLink>
                <button @click="sendRequestToFriend(userInfo.id)" class="btn"
                    v-if="profileId != user.id && !isFriend()">Добавить в друзья</button>
                <button @click="openChat(userInfo.id ,isFriend().chat_id)" class="add_but btn"
                    v-if="profileId != user.id && isFriend()">Открыть чат</button>
                <button @click="deleteFriend(userInfo.id)" class="btn"
                    v-if="profileId != user.id && isFriend()">Удалить из друзей</button>
            </div>
        </div>



        <div class="content" v-if="isAuthenticated">
            <div class="container">
                <div class="block5">
                    <div class="friends">
                        <p class="main_title_friends">Друзья</p>

                        <div v-for="friend in userInfo.friends" class="one_friend">
                            <div style="display: flex; align-items: center; gap: 1vw;">
                                <img @click="$router.push(`/profile/${friend.id}`)" v-if="friend && friend.avatar"
                                    :src="linkApp + '/storage/' + friend.avatar" alt="" class="avatar_friend" />
                                <img @click="$router.push(`/profile/${friend.id}`)" v-else
                                    :src="linkApp + '/img/img_acc.jpg'" class="avatar_friend" />
                                <div class="text_friend" @click="$router.push(`/profile/${friend.id}`)">
                                    <p class="name_friend" v-if="friend">{{ friend.name + ' ' +
                                        friend.surname }}</p>
                                    <p class="quote_friend" v-if="friend">{{ friend.quote }}</p>
                                </div>
                            </div>
                            <a v-if="userInfo.id == user.id"
                                @click.prevent="openChat(friend.id, friend.chat_id ? friend.chat_id : null)">
                                <img :src="linkApp + '/img/icons/chat.png'" alt="" class="avatar_icon" />
                            </a>
                        </div>



                    </div>
                </div>
            </div>
        </div>

        <label class="my_post" for="">{{ profileId == user.id ? 'Мои посты' : 'Посты' }}</label>
        <div v-if="!userPosts">
            <p style="margin-top: 30px;">Вы ещё не создавали постов, хотите добавить?</p>
            <button @click="$router.push('/post/create')" class="btn_post">Создать пост</button>
        </div>
        <Post v-else :Posts="userPosts" @like="getPosts" />
    </div>

</template>
<script>
import { RouterLink } from 'vue-router';
import FriendBar from '../components/friendBar.vue';
import SideMenu from '../components/sideMenu.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import Post from '../components/Post.vue';
import { ref } from 'vue';


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
    watch: {
        '$route.params.id': {
            handler(newId, oldId) {
                this.getPosts(newId);
                this.getUser(newId);
            },
        }
    },
    methods: {
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`, { chatId })
                .then(response => {
                    this.$router.push(`/chat/${response.data.chatId}`)
                })
        },
        getPosts(id) {
            axios.get(`/posts/user/${id ? id : this.profileId}`).then(response => {
                this.userPosts = response.data;
            })
                .catch((error) => {
                    this.userPosts = error.response;
                })
        },
        async getUser(id) {
            await axios.get(`/user/info/${id ? id : this.profileId}`)
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
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`, { chatId })
                .then(response => {
                    this.$router.push(`/chat/${response.data.chatId}`)
                })
        },
        isFriend() {
            if (!this.userInfo.friends) return null;
            const friend =  this.userInfo.friends.find(friend => friend.id === this.user.id);
            return friend ? { id: friend.id, chat_id: friend.chat_id } : null;
        },
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted() {
        this.getUser();
        this.getPosts();


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
.add_but {
    cursor: pointer;
    background: #865DF8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
    margin-bottom: 0.52vw;
}
.btn_post {
    cursor: pointer;
    margin-top: 20px;
    padding: 0.5vw 1.5vw;
    border: 1px solid #865DF8;
    border-radius: 0.5vw;
}

body {
    margin: 0;
    font-family: "Unbounded", serif;
    transition: background-color 0.3s ease;
}

.btn {
    cursor: pointer;
    margin-top: 1vw;
    padding: 0.5vw 1vw;
    border-radius: 2vw;
    border-color: #865DF8;
}

.like {
    margin-top: 15px;
    cursor: pointer;
    margin-left: 20px;
    gap: 15px;
    display: flex;
    align-items: center;
    font-size: 28px;
}

.friends {
    position: fixed;
    width: 22.03vw;
    height: 33.85vw;
    background-color: #F2EDFE;
    border-radius: 1.56vw;
    margin-left: 25vw;
    margin-top: 0.05vw;
}

.container {
    display: flex;
}

h1,
h2,
h3,
p,
a {
    font-family: "Unbounded", serif;

}

.content {
    margin-left: 23.44vw;
    padding: 1.04vw;
}

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 18.23vw;
    height: 100%;
    background-color: #865DF8;
    padding: 1.04vw;
    border-radius: 0vw 1.56vw 0vw 1.56vw;
    box-shadow: 0.10vw 0 0.26vw rgba(0, 0, 0, 0.1);
}

.sidebar a {
    display: block;
    padding: 0.52vw 0;
    color: #ffffff;
    text-decoration: none;
}

.avatar_block_nav {
    width: 11.20vw;
    height: 11.20vw;
}

.links_nav {
    margin-top: 3.65vw;
}

.logo {
    width: 11.20vw;
    height: 1.51vw;
    margin-bottom: 4.17vw;
    margin-top: -1.60vw;

}

.links_page,
.exit_link_nav {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.77vw;
    margin-left: 2.60vw;
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



.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    /* position: absolute; */
    position: fixed;
    margin-left: 13.02vw;
    margin-top: -10.42vw;
    width: 51.72vw;
    height: 51.04vw;
}

.bckgr2 {
    position: relative;
    z-index: -30;
}

.backgr_main2 {
    /* position: absolute; */
    position: fixed;

    margin-top: 31.25vw;
    width: 51.72vw;
    height: 51.04vw;
}

.name_cart {
    margin-left: 59.90vw;
    margin-bottom: 2.60vw;
    margin-top: 2.08vw;
    display: flex;
    justify-content: center;
    width: 10.42vw;
    height: 2.08vw;
    background: #865DF8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;

}

.name_cart p {
    padding: 0.52vw 0.26vw;
}

.profile {
    display: flex;
}

.img_profile img {

    width: 9.84vw;
    height: 9.84vw;
    border: 0.52vw solid #865DF8;
    border-radius: 5.21vw;
}

.profile_text {
    margin-left: 2.08vw;
}

.name_accaunt {
    width: 23.80vw;
    height: 1.30vw;
    font-weight: 400;
    font-size: 1.67vw;
}

.friends_col {
    margin-top: 15px;
    font-size: 1.04vw;
}

.quote {

    width: 33.85vw;
    height: 2.60vw;

    font-style: normal;
    font-weight: 300;
    font-size: 1.04vw;
}

.redact {
    font-size: 1.04vw;
    text-decoration: none;
    color: #865DF8;
}

.friends {
    position: fixed;
    width: 22.03vw;
    height: 33.85vw;
    background-color: #F2EDFE;
    border-radius: 1.56vw;

    margin-left: 25vw;
    margin-top: -9.75vw;
}

.main_title_friends {
    width: 7.86vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;
    margin-left: 1.56vw;
    margin-top: 1.30vw;
    margin-bottom: 2.60vw;
}

.one_friend {
    margin-top: 0.83vw;
    margin-left: 1.04vw;
    width: 19.22vw;

    border-radius: 0.52vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;

    padding-left: 0.42vw;
    padding-right: 0.42vw;
}

.avatar_friend {
    cursor: pointer;
    border-radius: 1.8vw;
    width: 3.65vw;
    height: 3.65vw;
}

.name_friend {
    font-family: var(--default-font-family, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji");
    /* width: 9.11vw;  */
    font-weight: 400;
    /* font-size: 0.73vw;
    line-height: 0.89vw;
    color: #865DF8;
    margin-bottom: 0.78vw; */
}

.quote_friend {
    width: 7.8vw;
    text-overflow: ellipsis;
    text-wrap: nowrap;
    overflow: hidden;
    font-weight: 400;
    font-size: 0.63vw;
    line-height: 0.78vw;
    color: rgba(134, 93, 248, 0.47);
}

.avatar_icon {
    cursor: pointer;
    width: 2.03vw;
    height: 1.93vw;
}

.text_friend {
    cursor: pointer;
}





.piple {
    display: flex;
}

.news {
    margin-top: 2.60vw;
    width: 39.32vw;
    margin-bottom: 2.60vw;
}

.img_avatar {
    width: 4.58vw;
    height: 4.58vw;
    border-radius: 1.56vw;
}

.news_img {
    width: 38.80vw;
    height: 25.63vw;
    border-radius: 0.52vw;
}

.new_content p {

    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.56vw;
}

.name_profile {
    margin-top: 0.26vw;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 500;
    font-size: 1.25vw;
    color: #865DF8;
}

.category_name {
    display: flex;
    justify-content: center;
    width: 7.24vw;
    margin-top: -0.63vw;
    height: 1.77vw;
    background: #865DF8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
}

.name_piple {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-left: 1.04vw;
}

.my_post {

    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 500;
    font-size: 1.67vw;
    line-height: 2.08vw;

    color: #865DF8;

}

.menu_mob {
    display: none;
}

@media (max-width:320px) {

    .sidebar {
        display: none;
    }

    .popup {
        display: none;
    }

    .block5 {
        display: none;
    }

    .menu_mob {
        display: block;
        width: 13vw;
        height: 5vw;
        margin-top: -6vw;
        margin-left: 1vw;


    }

    .name_cart {
        margin-left: 65vw;
        width: 30.25vw;
        height: 4.25vw;
        background: #865DF8;
        border-radius: 5vw;
        font-weight: 300;
        font-size: 3vw;
        line-height: 1.30vw;
        color: #FFFFFF;
        align-items: center;

    }


    #categoryButtons {
        font-size: 6.25vw;
        margin-top: 5vw;
    }



    .news {
        width: 100vw;
        margin-bottom: 2.60vw;
    }

    .img_avatar {
        width: 10.06vw;
        height: 10.06vw;
        border-radius: 1.56vw;
    }

    .news_img {
        width: 100vw;
        height: 66.25vw;
        margin-left: -1.2vw;
        border-radius: none;
    }

    .new_content p {
        width: 90vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 300;
        font-size: 2.5vw;
        line-height: 4vw;
    }

    .name_profile {
        margin-top: 0.26vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 500;
        font-size: 3vw;
        color: #865DF8;
    }

    .category_name {
        width: 15vw;
        margin-top: -0.63vw;
        padding: 1vw;
        border-radius: 3w;
        font-weight: 300;
        font-size: 2vw;

    }

    .name_piple {
        margin-left: 1.04vw;
    }

    .content {
        margin-left: 0.05vw;
    }

    .piple,
    .new_content p {
        margin-left: 5vw;
    }

    .profile {
        flex-direction: column;
    }

    .img_profile img {

        width: 35.31vw;
        height: 35.31vw;
        border-radius: 30vw;
        border: 1vw solid #865DF8;
        margin-left: 30vw;
    }

    .profile_text {}

    .name_accaunt {
        display: flex;
        justify-content: center;
        font-size: 4vw;
        width: 300px;
    }

    .friends_col {
        font-size: 2vw;
        display: flex;
        justify-content: center;
    }

    .quote {
        margin-left: 9vw;
        width: 75vw;
        font-size: 2.5vw;
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .redact {
        display: flex;
        justify-content: center;
        margin-top: 5vw;
        font-size: 2.5vw;
        margin-bottom: 10vw;
    }

    .my_post {
        font-size: 4vw;
        margin-left: 5vw;

    }

    .news {
        margin-top: 10vw;
    }
}
</style>