<template>
    <SideMenu />
    <div class="news-feed">
        <!-- Header -->
        <div class="content">
            <div class="container">
                <div class="block1">
                    <router-link :to="`/profile/${user.id}`" style="display: none;">
                        <img :src="linkApp + '/img/welcome_img/avatar_account.png'" class="avatar_mobile" alt="Profile">
                    </router-link>
                    <h1 class="main_title">Новости</h1>
                    <router-link to="/">
                        <img :src="linkApp + '/img/welcome_img/logo.png'" alt="Logo" class="logo">
                    </router-link>
                    <router-link to="/post/create" style="display: none;">
                        <img :src="linkApp + '/img/welcome_img/add_post_icon.png'" alt="Add Post" class="add_post">
                    </router-link>
                </div>
            </div>
        </div>




        <div class="bckgr">
            <img :src="linkApp + '/img/welcome_img/backgr_main.png'" alt="Background" class="backgr_main">
        </div>


        <div class="content">
            <div class="container">
                <div class="block2">
                    <router-link to="/post/create"><img :src="linkApp + '/img/welcome_img/add_news.png'" alt="Add News" class="add_news"></router-link>
                    <h1 class="add_news_title">Напиши новый пост!</h1>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="content">
            <div class="container">
                <div class="block3">
                    <div class="sort">
                        <select v-model="selectedCategoryId" @change="filterPosts">
                            <option :value="9999">Все категории</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts -->
        <div class="content">
            <div class="container">
                <div class="block4" v-for="post in filteredPosts" :key="post.id">
                    <div class="block4_account">
                        <div class="account">
                            <img :src="getUserAvatar(post.user)" alt="User Avatar" class="avatar_account"
                                @click="$router.push(`/profile/${post.user.id}`)">
                        </div>
                        <div class="post_account">
                            <p class="category_post">{{ post.category.name }}</p>
                            <p class="name_account">{{ getUserFullName(post.user) }}</p>
                        </div>
                    </div>
                    <div class="block4_posts">
                        <div class="post">
                            <img v-if="post.photos.length > 0" :src="getPostImage(post.photos)" alt="Post Image"
                                class="post_img">
                            <div class="description">
                                <p>{{ post.body }}</p>

                                <div class="post-footer">
                                    <span class="post-date">{{ formatDate(post.created_at) }}</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div v-if="filteredPosts.length === 0" class="no-posts">
                    <p v-if="selectedCategoryId !== 9999">В выбранной категории пока нет постов</p>
                </div>
            </div>
        </div>
    </div>
    <FriendBar />
</template>

<script>
import { mapGetters } from 'vuex';
import axios from 'axios';
import SideMenu from '../components/sideMenu.vue';
import FriendBar from '../components/friendBar.vue';

export default {
    name: 'NewsFeed',
    components: {
        SideMenu,
        FriendBar
    },
    data() {
        return {
            linkApp: '',
            posts: [],
            categories: [],
            selectedCategoryId: 9999 // По умолчанию "Все категории"
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
        filteredPosts() {
            // Сначала сортируем по дате (новые сверху)
            const sortedPosts = [...this.posts].sort((a, b) =>
                new Date(b.created_at) - new Date(a.created_at)
            );

            // Затем фильтруем по выбранной категории
            if (this.selectedCategoryId === 9999) {
                return sortedPosts;
            }
            return sortedPosts.filter(post => post.category.id === this.selectedCategoryId);
        }
    },
    methods: {
        filterPosts() {
            // Метод вызывается при изменении выбора в select
            // Вся логика уже в computed свойстве filteredPosts
        },
        getUserAvatar(user) {
            return user.user_info?.avatar
                ? `${this.linkApp}/storage/${user.user_info.avatar}`
                : `${this.linkApp}/img/welcome_img/avatar_account.png`;
        },
        getUserFullName(user) {
            return `${user.user_info?.name || ''} ${user.user_info?.surname || ''}`.trim();
        },
        getPostImage(photos) {
            return photos.length > 0
                ? `${this.linkApp}/storage/${photos[0].path}`
                : '';
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('ru-RU', options);
        },
        async fetchPosts() {
            try {
                const response = await axios.get('/post/get_posts');
                this.posts = response.data;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        async fetchCategories() {
            try {
                const response = await axios.get('/get/categories');
                this.categories = response.data;
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }
    },
    created() {
        this.linkApp = import.meta.env.VITE_APP_URL;
        this.fetchPosts();
        this.fetchCategories();
    }
}
</script>

<style scoped>
.sort select {
  width: 24.48vw;
  padding: 0.47vw 1.09vw;
  background-color: #C68DFE;
  border: none;
  border-radius: 1.04vw;
  color: #FFFFFF;
  font-family: "Unbounded", serif;
  font-weight: 400;
  font-size: 1.04vw;
  line-height: 1.30vw;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url('/img/welcome_img/arrow_sort.png');
  background-repeat: no-repeat;
  background-position: right 1.09vw center;
  background-size: 1.93vw 0.89vw;
  cursor: pointer;
  outline: none;
  transition: all 0.3s ease;
}

.sort select:hover {
  background-color: #b57df0;
}

.sort select:focus {
  box-shadow: 0 0 0 0.2vw rgba(198, 141, 254, 0.5);
}

/* Стили для options */
.sort select option {
  background-color: #F4F4F4;
  color: #000000;
  font-size: 1.04vw;
  padding: 0.52vw;
}

/* Для мобильных устройств */
@media (max-width: 766px) {
  .sort select {
    width: 57.50vw;
    font-size: 3.75vw;
    line-height: 4.69vw;
    padding: 1.47vw 1.09vw;
    border-radius: 6.25vw;
    background-size: 4.69vw 2.19vw;
    background-position: right 3vw center;
  }
  
  .sort select option {
    font-size: 3.04vw;
    padding: 1.5vw;
  }
}
.sort select::-webkit-scrollbar {
  width: 0.52vw;
}

.sort select::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 0.26vw;
}

.sort select::-webkit-scrollbar-thumb {
  background: #C68DFE;
  border-radius: 0.26vw;
}

.sort select::-webkit-scrollbar-thumb:hover {
  background: #b57df0;
}
.sort {
  position: relative;
  transition: all 0.3s ease;
}

.sort:after {
  content: '';
  position: absolute;
  right: 1.09vw;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.no-posts{
    color: #F4F4F4;
}
.post-footer {
    width: 100%;
    display: flex;
    justify-content: end;
}

.post-date {
    color: #F4F4F4;
}







/* Base Styles */
* {
    font-family: "Unbounded", serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    overflow-x: hidden;
    background-color: #181C22;
}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
}

.content {
    margin-left: 20.83vw;
    padding: 1.04vw;
}

/* Header Styles */
.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.main_title {
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #C68DFE;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.avatar_mobile,
.add_post {
    display: none;
}

/* Create Post Section */
.block2 {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.67vw;
}

.add_news_title {
    width: 13.75vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #C68DFE;
}

/* Sorting Section */
.block3 {
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
    background-color: #C68DFE;
    border-radius: 1.04vw;
    padding: 0.47vw 1.09vw;
}

.news_sort {
    color: #FFFFFF;
}

.option-list {
    width: 22.40vw;
    display: none;
    position: absolute;
    background-color: #F4F4F4;
    border: 0.05vw solid #ccc;
    border-radius: 1.30vw;
    z-index: 1000;
    font-size: 1.04vw;
    padding: 1.04vw;
}

.option-list.show {
    display: block;
}

.option-item {
    padding: 0.52vw;
    cursor: pointer;
    color: black;
}

.option-item:hover {
    background-color: #f0f0f0;
}

.arrow-icon {
    position: absolute;
    margin-top: 0.7vw;
    margin-left: 23vw;
    width: 1.93vw;
    height: 0.89vw;
}

/* Posts Section */
.block4 {
    display: flex;
    flex-direction: column;
    gap: 1.72vw;
    margin-bottom: 2.6vw;
}

.block4_account {
    display: flex;
    flex-direction: row;
    gap: 1.67vw;
    align-items: center;
}

.avatar_account {
    width: 4.58vw;
    height: 4.58vw;
    border-radius: 50%;
    object-fit: cover;
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
    object-fit: cover;
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

/* Background */
.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    position: fixed;
    margin-left: 41.67vw;
    margin-top: 15.63vw;
    width: 51.72vw;
    height: 51.04vw;
    z-index: -1;
}

/* Mobile Styles */
@media (max-width: 766px) {
    .content {
        margin-left: 0;
        padding: 3vw;
    }

    .main_title {
        font-size: 4.69vw;
        line-height: 5.94vw;
        margin-left: -15vw;
    }

    .avatar_mobile,
    .add_post {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .logo,
    .block2 {
        display: none;
    }

    .custom-select {
        width: 57.50vw;
        font-size: 3.75vw;
        line-height: 4.69vw;
        padding: 1.47vw 1.09vw;
        border-radius: 6.25vw;
    }

    .arrow-icon {
        width: 4.69vw;
        height: 2.19vw;
        margin-left: 51.56vw;
        margin-top: 3.13vw;
    }

    .option-list {
        width: 56.25vw;
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
        font-size: 2vw;
        line-height: 4.38vw;
        border-radius: 9.38vw;
    }

    .name_account {
        font-size: 3.75vw;
    }

    .post_img {
        width: 93.75vw;
        height: 51.56vw;
    }

    .description {
        width: 93.75vw;
        font-size: 3.44vw;
        line-height: 4.38vw;
    }
}
</style>