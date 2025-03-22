<template>
    <SideMenu />



    <div class="content">
        <div class="container">
            <div class="block1">
                <div class="category_slide">
                    <button id="applicantsBtn" @click="showContent('applicants')" class="switch">Друзья</button>
                    <button id="completedBtn" @click="showContent('completed')" class="switch">Заявки</button>
                    <button id="searchBtn" @click="showContent('search')" class="switch">Поиск</button>

                    <div class="content2" id="contentBlock">


                        <div id="applicantsContent" class="main_title">
                            <img :src="linkApp + '/img/friends_img/search.png'" alt="" class="search">
                            <input v-model="searchInput" type="text" id="searchInput" @input="startFilter" class="search-input"
                                placeholder="Введите имя и фамилию">

                            <div v-for="friend in searchInput? filteredFriends: friends" class="my_friend frr">
                                <img v-if="friend.user_info && friend.user_info.avatar"
                                    :src="linkApp + '/storage/' + friend.user_info.avatar" alt=""
                                class="avatar_friend">
                                <img v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="">
                                <div class="name_my_friend" @click="$router.push(`/profile/${friend.id}`)">
                                    <p class="friend_name">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                                    <p class="quote_friend" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                    <p class="quote_friend" v-else>К несчатью цитатки нет</p>

                                </div>
                                <button @click="deleteFriend(friend.id)">Удалить из друзей</button>
                                <img @click="$router.push(`/chat/${friend.chats.id}`)" :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" alt="" class="icon_mess">
                            </div>
                        </div>
                        <div id="searchContent" class="main_title">
                            <img :src="linkApp + '/img/friends_img/search.png'" alt="" class="search">
                            <input v-model="searchInput" type="text" id="searchInput" @input="startFilter" class="search-input"
                                placeholder="Введите имя и фамилию">

                            <div v-for="friend in searchInput? filteredPeople: allPeople" class="my_friend frr">
                                <img v-if="friend.user_info && friend.user_info.avatar"
                                    :src="linkApp + '/storage/' + friend.user_info.avatar" alt=""
                                class="avatar_friend">
                                <img v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="">
                                <div class="name_my_friend" @click="$router.push(`/profile/${friend.id}`)">
                                    <p class="friend_name">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                                    <p class="quote_friend" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                    <p class="quote_friend" v-else>К несчатью цитатки нет</p>

                                </div>
                                <button @click="sendRequestToFriend(friend.id)">Добавить в друзья</button>
                            </div>
                        </div>
                        <div id="completedContent" class="main_title2">

                            <div v-for="friend in friendRequest" class="my_friend frr">
                                <img v-if="friend.user_info && friend.user_info.avatar"
                                    :src="linkApp + '/storage/' + friend.user_info.avatar" alt=""
                                class="avatar_friend">
                                <img class="avatar_friend" v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt="">
                                <div class="name_my_friend" @click="$router.push(`/profile/${friend.id}`)">
                                    <p class="friend_name">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                                    <p class="quote_friend" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                    <p class="quote_friend" v-else>К несчатью цитатки нет</p>

                                </div>
                                <button @click="sendRequestToFriend(friend.id)">Принять</button>
                                <img @click="deleteRequestToFriend" :src="linkApp + '/img/welcome_img/close.png'" alt="" class="icon_mess">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="block1">
                    <a href="./profile.html"></a><img :src="linkApp + '/img/welcome_img/avatar_account.png'"
                        class="avatar_mobile" alt="">
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import SideMenu from '../components/sideMenu.vue';

export default {
    name: 'Friends',
    data() {
        return {
            linkApp: '',
            friends: [],
            filteredFriends: [],
            searchInput: '',
            filteredPeople: [],
            allPeople: [],
            friendRequest: []
        }
    },
    components:
    {
        SideMenu
    },
    methods: {
        showContent(section) {
            const applicantsBtn = document.getElementById('applicantsBtn');
            const completedBtn = document.getElementById('completedBtn');
            const searchBtn = document.getElementById('searchBtn');
            const applicantsContent = document.getElementById('applicantsContent');
            const completedContent = document.getElementById('completedContent');
            const searchContent = document.getElementById('searchContent');

            if (section === 'applicants') {
                applicantsContent.style.display = 'block';
                completedContent.style.display = 'none';
                searchContent.style.display = 'none';
                applicantsBtn.classList.add('active');
                completedBtn.classList.remove('active');
                searchBtn.classList.remove('active');
            } else if (section === 'completed') {
                completedContent.style.display = 'block';
                applicantsContent.style.display = 'none';
                searchContent.style.display = 'none';
                completedBtn.classList.add('active');
                searchBtn.classList.remove('active');
                applicantsBtn.classList.remove('active');
            } else if (section === 'search') {
                searchContent.style.display = 'block';
                completedContent.style.display = 'none';
                applicantsContent.style.display = 'none';
                searchBtn.classList.add('active');
                completedBtn.classList.remove('active');
                applicantsBtn.classList.remove('active');
            }
        },
        async getFriends() {
            await axios.get('/friend').then(response => {
                this.friends = response.data;
            })
        },
        async getPeople() {
            await axios.get('/people').then(response => {
                this.allPeople = response.data;
            })
                .catch((error) => {
                    this.allPeople = error.response;
                })
        },
        async getRequestsFromFriends() {
            await axios.get('/friend/get_friend_request').then(response => {
                this.friendRequest = response.data;
            })
                .catch((error) => {
                    this.friendRequest = error.response;
                })
        },
        deleteFriend(id) {
            axios.delete(`/friend/${id}/delete`, {})
                .then(response => {
                    this.getFriends();
                    this.startFilter();
                })
        },
        sendRequestToFriend(id) {
            axios.post('/friend/send_friend_request', { 'id': id })
                .then(response => {
                })
        },
        deleteRequestToFriend(id) {
            axios.post(`/friend/delete_friend_request`, { 'id': id })
                .then(response => {
                    this.getRequestsFromFriends();
                })
        },
        startFilter() {
            const searchTerm = this.searchInput.toLowerCase();
            this.filteredFriends = this.friends.filter(friend => {
                const fullName = (friend.user_info.name + ' ' + friend.user_info.surname).toLowerCase();
                return fullName.includes(searchTerm);
            });

            this.filteredPeople = this.allPeople.filter(friend => {
                const fullName = (friend.user_info.name + ' ' + friend.user_info.surname).toLowerCase();
                return fullName.includes(searchTerm);
            });
        }
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted() {
        this.startFilter();
        this.showContent('applicants');
        this.getFriends();
        this.getPeople();
        this.getRequestsFromFriends();
    }
}
</script>
<style scoped>
.main_title {
    width: 8.75vw;
    height: 2.08vw;
    font-weight: 400;
    font-size: 1.30vw;
    line-height: 2.08vw;
    color: #C68DFE;
}

.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.main_title2 {
    width: 7.50vw;
    font-weight: 400;
    font-size: 1.30vw;
    line-height: 2.08vw;
    color: #C68DFE;
}

.active {
    /* font-weight: bold; */
    text-decoration: underline;
}

.content2 {
    margin-top: 1.04vw;
    padding: 0.52vw;
}

#applicantsContent,
#completedContent {
    display: none;
}

.switch {
    width: 7.50vw;
    font-weight: 400;
    font-size: 1.30vw;
    line-height: 2.08vw;
    color: #C68DFE;
    background-color: #181C22;
    border: none;
    margin-right: 1.56vw;
}

.search-input {
    border: none;
    outline: none;
    width: 44.95vw;
    padding: 0.52vw 4.69vw;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 400;
    font-size: 1.25vw;
    line-height: 1.56vw;
    border: 0.05vw solid #C68DFE;
    border-radius: 0.52vw;
    margin-bottom: 2.60vw;
    background-color: #181C22;
    color: #fff;
}

input::placeholder {
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.56vw;
    color: rgba(255, 255, 255, 0.68);
}

.search {
    width: 1.82vw;
    height: 1.82vw;
    position: absolute;
    margin-top: 0.52vw;
    margin-left: 0.52vw;

}

.my_friend {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.51vw;
}

.name_my_friend {
    display: flex;
    flex-direction: column;
    margin-left: 1.77vw;
    gap: 0.52vw;
}

.friend_name {
    font-weight: 400;
    font-size: 1.25vw;
    line-height: 1.56vw;
    color: #FFFFFF;
}

.quote_friend {
    width: 26.67vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: rgba(255, 255, 255, 0.3);
}

.icon_mess {
    width: 2.03vw;
    height: 1.93vw;
    margin-left: 20.83vw;
}

.avatar_mobile,
.add_post,
.navbar {
    display: none;
}

.avatar_friend {
    width: 4.17vw;
    height: 4.17vw;
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
        /* display: block;
    width: 10.63vw;
    height: 10.63vw; */
        display: none;
    }

    .block1 {
        display: flex;
        align-items: center;
    }

    .main_title {
        margin-left: -15vw;
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
    }

    .add_post {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .logo,
    .block2,
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

    .switch {
        font-size: 13px;
        margin-right: 16.56vw;
    }

    .search-input {
        width: 65.95vw;
        padding: 2.52vw 2.69vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 400;
        margin-left: 15vw;
        line-height: 1.56vw;
        border: 0.05vw solid #C68DFE;
        border-radius: 1.52vw;
        margin-bottom: 3.60vw;
        background-color: #181C22;
        color: #fff;
        margin-top: 3.13vw;
        font-size: 3.25vw;

    }

    .search-input::placeholder {
        font-size: 3.25vw;

    }

    .avatar_friend {
        width: 13.44vw;
        height: 13.44vw;
    }

    .frr {
        margin-left: 5vw;
        margin-bottom: 6.25vw;
    }

    .zzz {
        margin-bottom: 6.25vw;
    }

    .content2 {
        margin-top: 5.04vw;
        padding: 0.52vw;
    }

    .friend_name {
        width: 62.50vw;
        font-weight: 300;
        font-size: 3.75vw;
        line-height: 5.31vw;
        color: #FFFFFF;
    }

    .quote_friend {
        margin-top: 3.13vw;
        font-size: 3.04vw;
    }

    .icon_mess {
        width: 8.75vw;
        height: 9.38vw;
        margin-left: 5.83vw;
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