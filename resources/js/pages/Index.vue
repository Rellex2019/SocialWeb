<template>
    <SideMenu />

    <div class="content" id="content">
        <div class="name_cart">
            <p>Главная</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt="" /></a>
        <div class="bckgr">
            <img :src="linkApp + '/img/back.png'" alt="" class="backgr_main" />
        </div>
        <div class="bckgr2">
            <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2" />
        </div>
        <FriendBar />


        <!-- ДОДЕЛАТЬ КОГДА СДЕЛАЮ КАТЕГОРИИ ПОЛЬЗОВАТЕЛЕЙ -->
        <div class="categories">
            <div class="category-buttons" id="categoryButtons">
                <button class="category-button" data-id="1">всё</button>
                <button class="category-button" data-id="1">музыка</button>
                <button class="category-button" data-id="1">спорт</button>
            </div>
        </div>
        <!-- --------------------------- -->
        <div class="category-content" id="categoryContent">
            <!-- <p  id="contentText">Выберите категорию, чтобы увидеть содержимое.</p> -->

            <div v-for="post in allPosts" class="news">
                <div class="piple">
                    <img class="img_avatar" :src="linkApp + '/img/icons/avatar.png'" alt="" />

                    <div class="name_piple">
                        <a style="text-decoration: none" href="">
                            <p class="name_profile">
                                {{ post.user.user_info.name + ' ' + post.user.user_info.surname }}
                            </p>
                        </a>
                        <p class="category_name">
                            {{ post.category.name.toLowerCase() }}
                        </p>
                    </div>
                </div>
                <div class="new_content">
                    <div class="title">{{ post.title }}</div>
                    <p>
                        {{ post.body }}
                    </p>
                    <div class="container_img">
                        <img v-for="photo in post.photos" :key="photo.id" class="news_img"
                            :src="linkApp + '/storage/' + photo.path" />
                    </div>
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
    </div>
    <!-- <script>
        function openPopup() {
            document.getElementById("popup").style.display = "flex";
        }
        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
        const burgerBtn = document.getElementById("burgerBtn");
        const menu = document.getElementById("menu");
        const content = document.getElementById("content");

        burgerBtn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
            if (menu.classList.contains("hidden")) {
                content.classList.remove("dimmed"); // Убираем затемнение, если меню закрыто
            } else {
                content.classList.add("dimmed"); // Добавляем затемнение, если меню открыто
            }
            burgerBtn.classList.toggle("active"); // изменяем класс для анимации кнопки
        });
    </script>
    <script src="./js/category.js"></script> -->
</template>
<script>
import FriendBar from '../components/friendBar.vue';
import SideMenu from '../components/sideMenu.vue';

export default {
    name: "Index",

    data() {
        return {
            //Посты
            myPosts: [],
            allPosts: [],
            // postInput: [],

            //Друзья
            friendsInfo: [],
            linkApp: "",
        }
    },
    components: {
        SideMenu,
        FriendBar
    },
    methods: {

        async getAllPosts() {
            await axios.get("/post/get_posts")
                .then((response) => {
                    this.allPosts = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`, { chatId })
                .then(response => {
                    this.$router.push(`/chat/${response.data.chatId}`)
                })
        },
    },
    mounted() {
        this.getAllPosts();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
};
</script>
<style scoped>
.like{
    margin-top: 15px;
    cursor: pointer;
    margin-left: 20px;
    gap: 15px;
    display: flex;
    align-items: center;
    font-size: 28px;
}
.title {
    font-size: 42px;
}

.categories {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.04vw;
}

.category-buttons {
    display: flex;
    flex-wrap: wrap;
    margin-top: -4.69vw;
    gap: 2.6vw;
    margin-left: 1.3vw;
}

.category-button {
    font-family: "Unbounded";
    font-style: normal;
    font-weight: 200;
    font-size: 1.25vw;
    line-height: 1.56vw;
    background-color: transparent;
    color: #000000;
    border: none;
    padding: 0.52vw 1.04vw;
    margin-left: -1.04vw;
    cursor: pointer;
    border-radius: 0.26vw;
    transition: color 0.3s, text-decoration 0.3s;
}

.name_cart p {
    padding: 0.52vw 0.26vw;
}

.category-button:hover {
    color: #6a4bdb;
    /* Изменяем цвет текста при наведении */
}

.category-button.active {
    text-decoration: underline;
    /* Подчеркиваем активную кнопку */
    text-decoration-color: #6a4bdb;

    color: #000000;
    /* Меняем цвет текста активной кнопки */
}

.category-content {
    margin-top: 1.04vw;
    padding: 1.04vw;

    border-radius: 0.26vw;
}

.piple {
    display: flex;
}

.news {
    width: 39.32vw;
    margin-bottom: 2.6vw;
}

.img_avatar {
    width: 4.58vw;
    height: 4.58vw;
    border-radius: 1.56vw;
}

.container_img {
    display: flex;
    flex-wrap: wrap;
    max-width: 800px;
}

.news_img {
    max-width: 750px;
    max-height: 500px;
    border-radius: 0.52vw;
}

.new_content p {
    font-family: "Unbounded";
    font-style: normal;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.56vw;
}

.name_profile {
    margin-top: 0.26vw;
    font-family: "Unbounded";
    font-style: normal;
    font-weight: 500;
    font-size: 1.25vw;
    color: #865df8;
}

.category_name {
    display: flex;
    justify-content: center;
    width: 7.24vw;
    margin-top: -0.63vw;
    height: 1.77vw;
    background: #865df8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
    align-items: center;
}

.name_piple {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-left: 1.04vw;
}

.friends {
    position: fixed;
    width: 22.03vw;
    height: 33.85vw;
    background-color: #f2edfe;
    border-radius: 1.56vw;

    margin-left: 25vw;
    margin-top: 0.05vw;
}




.name_friend {
    width: 9.11vw;
    font-weight: 400;
    font-size: 0.73vw;
    line-height: 0.89vw;
    color: #865df8;
    margin-bottom: 0.78vw;
}

.quote_friend {
    font-weight: 400;
    font-size: 0.63vw;
    line-height: 0.78vw;
    color: rgba(134, 93, 248, 0.47);
}

/* вспылвающее окно */

.popup {
    z-index: 2;
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.popup-content {
    z-index: 2;

    width: 40.99vw;
    height: 38.02vw;

    background: #ffffff;
    border-radius: 0.52vw;

    padding: 1.04vw;
    border-radius: 0.26vw;
    position: relative;

    box-shadow: 0 0 0.52vw rgba(0, 0, 0, 0.1);
}

.no_log {
    color: #000;
}

.close {
    z-index: 2;
    position: absolute;
    margin-top: 36.46vw;
    margin-left: 31.25vw;
    font-size: 1.04vw;
    cursor: pointer;
    color: #aaa;
}

.close:hover {
    color: #000;
}

.open-popup {
    z-index: 2;
    background-color: #5cb85c;
    color: white;
    padding: 0.52vw 1.04vw;
    border: none;
    border-radius: 0.26vw;
    cursor: pointer;
}

.open-popup:hover {
    background-color: #4cae4c;
}

.flex_popup {
    display: flex;
    flex-direction: column;
}

.add_foto {
    margin-left: 40%;
    margin-top: 2.6vw;
    width: 8.23vw;
    height: 8.23vw;
}

.lab_ok {
    font-family: "Unbounded";
    font-style: normal;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    text-align: center;
    color: #865df8;
}

.lab_ok2 {
    margin-top: 1.04vw;
    margin-bottom: 1.04vw;

    font-family: "Unbounded";
    font-style: normal;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    text-align: center;
    color: #865df8;
}

.category_content {
    display: flex;
    width: 40.99vw;
    margin-left: 3.65vw;

    flex-wrap: wrap;
}

.img_category_item {
    position: absolute;
    z-index: -20;
    width: 10.42vw;
    height: 5.21vw;
    border-radius: 1.56vw;
    filter: brightness(50%);
}

.sd {
    margin-left: 4.17vw;
}

.category_item {
    width: 11.61vw;
    height: 5.47vw;
}

.name_category {
    padding-top: 1.3vw;
    display: flex;
    justify-content: center;
    color: rgb(255, 255, 255);
}

.redact {
    padding-top: 2.6vw;
    font-size: 1.04vw;
    text-decoration: none;
    color: #865df8;
}

.sidebar {
    display: block;
}

.sidebar {
    display: block;
}

.popup {
    display: block;
}

.block5 {
    display: block;
}

.menu_mob {
    display: none;
}

@media ((min-width: 320px) and (max-width: 766px)) {
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
        margin-left: 78vw;
        width: 20.25vw;
        height: 4.25vw;
        background: #865df8;
        border-radius: 5vw;
        font-weight: 300;
        font-size: 3vw;
        line-height: 1.3vw;
        color: #ffffff;
        align-items: center;
    }

    #categoryButtons {
        font-size: 6.25vw;
        margin-top: 5vw;
    }

    .news {
        width: 100vw;
        margin-bottom: 2.6vw;
    }

    .img_avatar {
        width: 10.06vw;
        height: 10.06vw;
        border-radius: 1.56vw;
    }

    .news_img {
        width: 95vw;
        height: 66.25vw;
        margin-left: 0.5vw;
        border-radius: none;
    }

    .new_content p {
        width: 95vw;
        font-family: "Unbounded";
        font-style: normal;
        font-weight: 300;
        font-size: 2.5vw;
        line-height: 4vw;
    }

    .category-button {
        font-size: 4vw;
        margin-top: 2vw;
        margin-bottom: 5vw;
    }

    .name_profile {
        margin-top: 0.26vw;
        font-family: "Unbounded";
        font-style: normal;
        font-weight: 500;
        font-size: 3vw;
        color: #865df8;
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
}

/* menu.css */

body {
    margin: 0;
    font-family: "Unbounded", serif;
    transition: background-color 0.3s ease;
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

.avatar_block_nav {
    width: 11.2vw;
    height: 11.2vw;
}

.links_nav {
    margin-top: 3.65vw;
}

.logo {
    width: 11.2vw;
    height: 1.51vw;

    margin-bottom: 4.17vw;
    margin-top: -1.6vw;
}

.links_page,
.exit_link_nav {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.77vw;
    margin-left: 2.6vw;
}

.icon_link {
    padding-right: 1.41vw;
    /* width: 1.82vw; */
    height: 1.72vw;
}

.name_link,
.exit_link {
    cursor: pointer;
    width: 9.22vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
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
    margin-left: 59.9vw;
    margin-bottom: 2.6vw;
    margin-top: 2.08vw;
    display: flex;
    justify-content: center;
    width: 10.42vw;
    height: 2.08vw;
    background: #865df8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
    align-items: center;
}

.name_cart p {
    padding: 0.52vw 0.26vw;
}
</style>
