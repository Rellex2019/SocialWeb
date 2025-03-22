<template>
    <SideMenu />


    <div class="content">
        <div class="container">
            <div class="block1">
                <a href="./profile.html"><img :src="linkApp + '/img/welcome_img/avatar_account.png'" class="avatar_mobile" alt=""></a>
                <h1 class="main_title">Новости</h1>
                <a href="./welcome.html"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></a>
                <a href="./new_post.html"><img :src="linkApp + '/img/welcome_img/add_post_icon.png'" alt="" class="add_post"></a>
            </div>
        </div>
    </div>
    <div class="bckgr">
        <img :src="linkApp + '/img/welcome_img/backgr_main.png'" alt="" class="backgr_main">
    </div>

    

    <button class="open-menu" onclick="openMenu()">Открыть меню</button>

    <div class="overlay" id="overlay" onclick="closeMenu()"></div>
    <div class="menu" id="menu">
        <button class="close-btn" onclick="closeMenu()">× Закрыть</button>
        <div class="overl_content">
        <h2 class="overl_title">Дополнить профиль аватаркой?</h2>
        <img :src="linkApp + '/img/welcome_img/overlay_photo.png'" alt="" class="overl_image">
        <h2 class="overl_title">Какие категории вас интересуют?</h2>
        <div v-for="category in 4" class="category_content_overl">
            <p class="name_category_overl">Спорт</p>
            <img :src="linkApp + '/img/welcome_img/category.png'" alt="" class="overl_category">
        </div>

</div>
    </div>




    <div class="content">
        <div class="container">
            <div class="block2">
                <img :src="linkApp + '/img/welcome_img/add_news.png'" alt="" class="add_news">
                <h1 class="add_news_title">Напиши новый пост!</h1>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="block3">
                <div class="sort"><img :src="linkApp + '/img/welcome_img/arrow_sort.png'" alt="arrow" class="arrow-icon">
                    <div class="custom-select" id="customSelect">
                        <p class="news_sort">Все новости</p>
                    </div>
                    <div class="option-list" id="optionList">
                        <div class="option-item" data-value="audio">Спорт</div>
                        <div class="option-item" data-value="immersive">Спорт</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="block5">
                <div class="friends">
                    <p class="main_title_friends">Мои друзья</p>

                    <div v-for="frien in 4" class="one_friend">
                        <img :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="" class="avatar_friend">
                        <div class="text_friend">
                            <p class="name_friend">Орешина Виктория</p>
                            <p class="quote_friend">цитата</p>
                        </div>
                        <a href="#"><img :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" alt="" class="avatar_icon"></a>
                    </div>



                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container">
            <div v-for="post in 4" class="block4">
                <div class="block4_account">
                    <div class="account">
                        <img :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="" class="avatar_account">
                    </div>
                    <div class="post_account">
                        <p class="category_post">Музыка</p>
                        <p class="name_account">Лилия блошкина</p>
                    </div>
                </div>
                <div class="block4_posts">
                    <div class="post">
                        <img :src="linkApp + '/img/welcome_img/post_img.png'" alt="" class="post_img">
                        <p class="description">Ищите и загружайте самые популярные фото Природа на Freepik Бесплатное
                            коммерческое использование Качественная графика</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- ДОДЕЛАТЬ КОГДА СДЕЛАЮ КАТЕГОРИИ ПОЛЬЗОВАТЕЛЕЙ -->
        <div class="categories">
            <div class="category-buttons" id="categoryButtons">
                <button class="category-button" @click="filteredPost(9999)" data-id="1">Всё</button>
                <button class="category-button" :key="category.id" @click="filteredPost(category.id)"
                    v-for="category in selectedCategories" data-id="1">{{ category.name }}</button>
            </div>
        </div>
        <!-- --------------------------- -->

</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import FriendBar from '../components/friendBar.vue';
import Post from '../components/Post.vue';
import SideMenu from '../components/sideMenu.vue';

export default {
    name: "Index",

    data() {
        return {
            //Посты
            allPosts: [],
            allFilteredPost: [],
            filterId: 9999,
            // postInput: [],

            //Друзья
            userInfo: [],
            linkApp: "",
            selectedCategories: [],
        }
    },
    components: {
        SideMenu,
        FriendBar,
        Post
    },
    methods: {

        async getAllPosts() {
            await axios.get("/post/get_posts")
                .then((response) => {
                    this.allPosts = response.data;
                    this.filteredPost(this.filterId);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async getUser() {
            await axios.get(`/user/info/${this.profileId}`)
                .then(response => {
                    this.userInfo = response.data;
                })
        },
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`, { chatId })
                .then(response => {
                    this.$router.push(`/chat/${response.data.chatId}`)
                })
        },
        getSelectedCategory() {
            axios.get('/user/category')
                .then(response => {
                    this.selectedCategories = response.data;
                })
        },
        filteredPost(categoryId) {
            this.filterId = categoryId;
            if (categoryId === 9999) { 
                this.allFilteredPost = this.allPosts; 
            } else {
                this.allFilteredPost = this.allPosts.filter(post => post.category.id == categoryId); // Фильтруем по категории
            }
        }
    },
    mounted() {
        this.getAllPosts();
        this.getSelectedCategory();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
};
</script>
<style scoped>

.main_title{
font-weight: 400;
font-size: 1.67vw;
line-height: 2.08vw;
color: #C68DFE;
/* margin-left: -25vw; */
}

.block1{
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
.logo{
    width: 8.49vw;
    height: 1.56vw;
    margin-left: 50vw;
}

/* ----------------------------------block2---------------------------------- */
.block2{
display: flex;
flex-direction: row;
align-items: center;
gap: 1.67vw;
margin-top: 3.49vw;
}
.add_news_title{
width: 13.75vw;
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #C68DFE;
}

/* ----------------------------------block3----------------------------------- */
.block3{
    margin-top: 2.08vw;
}
.sort {
    position: relative;
    z-index: 0;
}

.custom-select {
cursor: pointer;
display: block;
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
width: 24.48vw;
/* height: 40px; */
background-color: #C68DFE;
border-radius: 1.04vw;
padding: 0.47vw 1.09vw;
}

.news_sort{
    color: #FFFFFF;


}

.option-list {
    width: 22.40vw;
    display: none;
    position: absolute;
    background-color: #F4F4F4;
    border: 0.05vw solid #ccc;
    border-radius: 1.30vw;
    /* width: 100%; */
    z-index: 1000;
    font-size: 1.04vw;
    padding: 1.04vw;
}

.option-item {
    padding: 0.52vw;
    cursor: pointer;
    color: black;
}

.option-item:hover {
    background-color: #F4F4F4;
}

.arrow-icon{
    position: absolute;
    margin-top: 0.7vw;
    margin-left: 23vw;
    width: 1.93vw;
    height: 0.89vw;
}

/* -----------------------------------block4--------------------------------------- */
.block4{
    display: flex;
    flex-direction: column;
    gap: 1.72vw;
}
.block4_account{
    display: flex;
    flex-direction: row;
    gap: 1.67vw;
    align-items: center;
}
.account{

}
.avatar_account{
width: 4.58vw;
height: 4.58vw;
}
.post_account{
display: flex;
flex-direction: column;
gap: 0.57vw;
}
.category_post{
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
.name_account{
font-weight: 500;
font-size: 1.04vw;
line-height: 1.30vw;
color: #C68DFE;
}
.post_img{
width: 45.68vw;
height: 30.10vw;
border-radius: 0.52vw;
}
.description{
width: 45.68vw;
font-weight: 300;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
margin-top: 1.35vw;
margin-bottom: 4.17vw;
}

/* -----------------------------------block5--------------------------------------- */
.block5{
    
}
.friends{
position: absolute;
width: 22.03vw;
height: 36.93vw;
border: 1px solid rgba(198, 141, 254, 0.25);
border-radius: 0.52vw;
margin-top: -46.35vw;
margin-left: 49.48vw;
margin-top: 0.05vw;
}

.main_title_friends{
width: 7.86vw;
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
margin-left: 1.04vw;
margin-top: 1.04vw;
margin-bottom: 2.60vw;
}
.one_friend{
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
.avatar_friend{
width: 2.71vw;
height: 2.71vw;
}
.name_friend{
width: 9.11vw;
font-weight: 400;
font-size: 0.73vw;
line-height: 0.89vw;
color: #FFFFFF;
margin-bottom: 0.78vw;
}
.quote_friend{
font-weight: 400;
font-size: 0.63vw;
line-height: 0.78vw;
color: rgba(255, 255, 255, 0.65);
}
.avatar_icon{
width: 2.03vw;
height: 1.93vw;
}
.text_friend{
    margin-left: -3.13vw;
}

.bckgr{
    position: relative;
    z-index: -30;
}
.backgr_main{
    /* position: absolute; */
    position: fixed;
    margin-left: 41.67vw;
    margin-top: 15.63vw;
    width: 51.72vw;
    height: 51.04vw;
}
.avatar_mobile, .add_post, .navbar{
    display: none;
}


.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1;
}

.menu {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 1.04vw;
    box-shadow: 0 0 0.52vw rgba(0, 0, 0, 0.5);
    z-index: 2;
    border-radius: 1.04vw;
    color: #FFFFFF;
    background-color: #22232F;
}

.overl_title{
font-weight: 400;
font-size: 0.83vw;
line-height: 1.15vw;
color: #C68DFE;
margin-top: 1.56vw;
margin-bottom: 1.04vw;
}
.overl_image{
    width: 6.46vw;
    height: 6.46vw;
}
.close-btn {
    background: #C68DFE;
    color: white;
    border: none;
    border-radius: 0.26vw;
    padding: 0.52vw;
    cursor: pointer;
}

.open-menu {
    margin: 1.04vw;
    margin-left: 25.35vw;
}
.overl_content{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.overl_category {
    width: 36.46vw;
    height: 6.51vw;
    margin-bottom: 1.04vw;
    z-index: 5;
}
.category_content_overl{
    display: flex;
    justify-content: center;
    align-items: center;
}
.name_category_overl{
    color: #FFFFFF;
    position: absolute;
    z-index: 10;
}
/* -----------------------------------media----------------------------- */
@media ((min-width: 320px) and (max-width: 766px)){
.sidebar{
    display: none;
}
.content{
    margin-left: -1vw;

}
.avatar_mobile{
    display: block;
    width: 10.63vw;
    height: 10.63vw;
}
.block1{
    display: flex;
    align-items: center;
}
.main_title{
    margin-left: -15vw;
font-weight: 400;
font-size: 4.69vw;
line-height: 5.94vw;
color: #C68DFE;
}
.add_post{
display: block;
width: 10.63vw;
height: 10.63vw;
}
.logo,.block2,.friends{
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

.custom-select{
width: 57.50vw;
background: #C68DFE;
border-radius: 6.25vw;

font-weight: 300;
font-size: 3.75vw;
line-height: 4.69vw;
color: #FFFFFF;
padding: 1.47vw 1.09vw;

}
.arrow-icon{
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
.option-item{
    padding: 1.5vw;
}


.avatar_account{
width: 13.13vw;
height: 13.13vw;
}

.category_post{
width: 25.63vw;
height: 5.63vw;
background: #C68DFE;
border-radius: 9.38vw;
font-weight: 300;
font-size: 11px;
line-height: 4.38vw;
color: #FFFFFF;
}
.name_account{
    font-size: 3.75vw;
}
.post_account {
    gap: 3.57vw;
}
.post_img {
width: 93.75vw;
height: 51.56vw;
}
.description{
width: 93.75vw;
font-weight: 300;
font-size: 3.44vw;
line-height: 4.38vw;
color: #FFFFFF;
}
.block4 {
    gap: 3.72vw;
}


.overl_image{
    width: 124px;
    height: 124px;
}
.overl_title{
    width: 226px;
        font-size: 14px;
        line-height: 5.15vw;
        margin-bottom: 10px;
        margin-top: 10px;
}

.overl_category{
    width: 252px;
    height: 63px;
    margin-bottom: 8px;
}


}

*{
    font-family: "Unbounded", serif;
    font-optical-sizing: auto;
    color: black;
    text-decoration: none;
    margin: 0;
    padding: 0;
}


body{
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
    box-shadow: 0.10vw 0 0.26vw rgba(0,0,0,0.1);
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

.avatar_block{
width: 17.50vw;
height: 16.46vw;
background: #22232F;
border-radius: 0.52vw;
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
}
.name_user_nav{
margin-top: 1.35vw;
width: 11.56vw;
font-weight: 500;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
}

.avatar_block_nav{
width: 11.20vw;
height: 11.20vw;
}
.links_nav{
    margin-top: 3.65vw;
}
.links_page, .exit_link_nav{
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.77vw;
}
.icon_link{
    padding-right: 1.41vw;
width: 1.82vw;
height: 1.72vw;
}
.name_link, .exit_link{
width: 9.22vw;
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
}

.exit_link_nav{
    margin-top: 7.21vw;
}

@media ((min-width: 320px) and (max-width: 766px)){
    .sidebar{
        display: none;
    }
    .content{
        margin-left: -1vw;
    }
}
</style>
