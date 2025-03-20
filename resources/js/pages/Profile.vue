<template>
    <SideMenu />
    <div class="content" id="content">

        <div class="name_cart">
            <p>Мой профиль</p>
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
                <img :src="linkApp + '/img/img_acc.png'" alt="">
            </div>
            <div class="profile_text">
                <p class="name_accaunt" v-if="userInfo.user_info">{{ userInfo.user_info.name??' ' + ' ' + userInfo.user_info.surname }}</p>
                <p class="friends_col" v-if="userInfo.all_friends">{{ userInfo.all_friends.length }} друга</p>
                <p class="quote" v-if="userInfo.user_info">{{ userInfo.user_info.quote?? 'Цитата не установлена :('}} </p>
                <RouterLink to="/profile/edit" class="redact">Изменить профиль</RouterLink>
            </div>
        </div>


        <FriendBar :customTop="-10.42 + 'vw'" />

        <label class="my_post" for="">Мои посты</label>
        <div v-for="post in userPosts" :key="post.id" class="news">

            <div class="piple">
                <img class="img_avatar" :src="linkApp + '/img/icons/avatar.png'" alt="">
                <div class="name_piple">
                    <p class="name_profile">
                        {{ post.user.user_info.name + ' ' + post.user.user_info.surname }}
                    </p>
                    <p class="category_name">{{ post.category.name.toLowerCase() }}</p>
                </div>
            </div>
            <div class="new_content">
                <p>
                    {{ post.body }}
                </p>
                <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/storage/' + photo.path">
                <div class="action_container">

                    <div class="like" @click="toggleLike(post.id)">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512; width: 40px;" xml:space="preserve">
                            <g>
                                <g>
                                    <path
                                        d="M466.706,66.173c-29.609-29.609-69.224-45.914-111.56-45.914c-36.448,0-70.876,12.088-98.643,34.342 c-28.166-22.254-62.637-34.342-98.729-34.342c-42.532,0-82.252,16.312-111.86,45.914C16.305,95.776,0,135.398,0,177.727 c0,42.335,16.305,81.951,45.914,111.553l197.065,197.065c3.591,3.598,8.306,5.396,13.021,5.396c4.703,0,9.405-1.793,13.003-5.372 l197.224-196.623C495.75,259.561,512,219.89,512,178.034C512,135.791,495.965,96.12,466.706,66.173z M440.056,263.821 L256.018,447.294L71.956,263.238c-22.647-22.653-35.122-53.023-35.122-85.511s12.475-62.858,35.122-85.511 c22.653-22.647,53.128-35.122,85.818-35.122c32.169,0,62.705,12.53,85.966,35.269c7.207,7.054,18.767,6.992,25.895-0.147 c22.653-22.647,53.017-35.122,85.511-35.122c32.494,0,62.858,12.475,85.358,34.974c22.352,22.868,34.661,53.398,34.661,85.966 C475.165,210.209,462.642,240.738,440.056,263.821z"
                                        fill="#000000" style="fill: rgb(134, 93, 248);"></path>
                                </g>
                            </g>
                        </svg>
                        {{ post.likes.length }}
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

export default {
    name: 'Profile',
    data() {
        return {
            linkApp: '',
            userPosts: [],
            userInfo: [],
        }
    },
    methods: {
        getMyPosts() {
            axios.get('/post/my_posts').then(response => {
                this.userPosts = response.data;
            })
                .catch((error) => {
                    this.userPosts = error.response;
                })
        },
        async getUser()
        {
            await axios.get('/user/info')
            .then(response=>{
                this.userInfo = response.data;
            })
        },
        toggleLike(postId) {
            axios.post(`/post/${postId}/like`, {})
                .then(response => {
                    this.getMyPosts();
                })
        },
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted() {
        this.getUser();
        this.getMyPosts();
    },
    computed:{
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    components: {
        SideMenu,
        FriendBar
    }
}
</script>
<style scoped>
body {
    margin: 0;
    font-family: "Unbounded", serif;
    transition: background-color 0.3s ease;
}
.like{
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
    margin-top: -10.42vw;
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
    margin-top: -10.42vw;
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
    width: 3.65vw;
    height: 3.65vw;
}

.name_friend {
    width: 9.11vw;
    font-weight: 400;
    font-size: 0.73vw;
    line-height: 0.89vw;
    color: #865DF8;
    margin-bottom: 0.78vw;
}

.quote_friend {
    font-weight: 400;
    font-size: 0.63vw;
    line-height: 0.78vw;
    color: rgba(134, 93, 248, 0.47);

}

.avatar_icon {
    width: 2.03vw;
    height: 1.93vw;
}

.text_friend {
    margin-left: -3.13vw;
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