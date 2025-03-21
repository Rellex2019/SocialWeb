<template>
    <SideMenu />
    <div class="content" id="content">
        <div class="bckgr">
            <img :src="linkApp + '/img/back.png'" alt="" class="backgr_main">
        </div>
        <div class="bckgr2">
            <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2">
        </div>
        <div class="name_cart">
            <p>Мои друзья</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>



        <input v-model="searchInput" @input="startFilter" type="text" id="searchInput" class="search-input"
            placeholder="Введите имя и фамилию">


        <div class="friends_content">
            <div class="friends">


                <div class="but_categories1">
                    <button class="but_categories" id="applicantsBtn" @click="showContent('applicants')">Мои
                        друзья</button>
                    <button class="but_categories" id="completedBtn" @click="showContent('completed')">Заявки</button>
                    <button style="margin-top: 15px;" class="but_categories" id="searchBtn"
                        @click="showContent('search')">Поиск</button>
                </div>


                <div class="contents" id="contentBlock">

                    <div id="applicantsContent">

                        <div v-if="searchInput" v-for="friend in filteredFriends" class="name_chat">
                            <img class="img_avatar" v-if="friend.user_info && friend.user_info.avatar"
                                :src="linkApp + '/storage/' + friend.user_info.avatar" alt="">
                            <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                            <div class="chats"  @click="$router.push(`/profile/${friend.id}`)">
                                <p class="name_chat_f">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                                <p class="citata" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                <p class="citata" v-else>К несчатью цитатки нет</p>
                            </div> <img class="img_chat" @click="deleteFriend(friend.id)"
                                :src="linkApp + '/img/icons/deleet_fr.png'" alt="">
                        </div>
                        <div v-else v-for="friend in friends" class="name_chat">
                            <img class="img_avatar" v-if="friend.user_info && friend.user_info.avatar"
                                :src="linkApp + '/storage/' + friend.user_info.avatar" alt="">
                            <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                            <div class="chats" @click="$router.push(`/profile/${friend.id}`)">
                                <p class="name_chat_f">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                                <p class="citata" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                <p class="citata" v-else>К несчатью цитатки нет</p>
                            </div> <img class="img_chat" @click="deleteFriend(friend.id)"
                                :src="linkApp + '/img/icons/deleet_fr.png'" alt="">
                        </div>
                    </div>

                    <div id="completedContent">


                        <div v-for="request in friendRequest" class="name_chat">
                            <img class="img_avatar" v-if="request.user_info && request.user_info.avatar"
                                :src="linkApp + '/storage/' + request.user_info.avatar" alt="">
                            <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                            <a @click="$router.push(`/profile/${request.id}`)">
                                <div class="chats">
                                    <p class="name_chat_f">{{ request.user_info.name + ' '+ request.user_info.surname}}</p>
                                    <p class="citata" v-if="request.user_info && request.user_info.quote ">{{ request.user_info.quote }}</p>
                                    <p class="citata" v-else >Бедолага без цитаты</p>
                                </div>
                            </a>
                            <div class="but_zayav">
                                <a @click="sendRequestToFriend(request.id)" class="add_but" >добавить</a>
                                <a @click="deleteRequestToFriend(request.id)" class="reject_but" >отклонить</a>
                            </div>
                        </div>


                    </div>

                    <div id="searchContent">
                        <div v-if="searchInput" v-for="friend in filteredPeople" class="name_chat">
                            <img class="img_avatar" v-if="friend.user_info && friend.user_info.avatar"
                                :src="linkApp + '/storage/' + friend.user_info.avatar" alt="">
                            <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                            <div class="chats">
                                <p class="name_chat_f">{{ friend.user_info.name + ' ' + friend.user_info.surname }}
                                </p>
                                <p class="citata" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                <p class="citata" v-else>К несчатью цитатки нет</p>
                            </div> 
                            <button @click="sendRequestToFriend(friend.id)" class="btn">Добавить</button>
                        </div>
                        <div  v-else v-for="friend in allPeople" class="name_chat">
                            <img  class="img_avatar" v-if="friend.user_info && friend.user_info.avatar"
                                :src="linkApp + '/storage/' + friend.user_info.avatar" alt="">
                            <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                            <div class="chats" @click="$router.push(`/profile/${friend.id}`)">
                                <p class="name_chat_f">{{ friend.user_info.name + ' ' + friend.user_info.surname }}
                                </p>
                                <p class="citata" v-if="friend.user_info.quote">{{ friend.user_info.quote }}</p>
                                <p class="citata" v-else>К несчатью цитатки нет</p>
                            </div> 
                            <button @click="sendRequestToFriend(friend.id)" class="btn">Добавить</button>
                        </div>
                    </div>
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
            friendRequest:[]
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

.btn{
    padding: 5px 10px 5px 10px;
    width: auto;
    height: 2vw;
    border: 1px solid #865DF8;
    border-radius: 1vw;
    text-align: center;
}
.search-container {
    display: flex;
    /* Rectangle 27 */


    width: 44.95vw;
    height: 2.60vw;




}

.search-icon {

    position: absolute;
    margin-right: 0.78vw;
    margin-left: 0.52vw;
    margin-top: 0.26vw;
}

.search-icon img {
    width: 2.14vw;
    height: 2.14vw;

}

.search-input {
    border: none;
    outline: none;
    width: 44.95vw;
    padding-top: 0.52vw;
    padding-bottom: 0.52vw;
    padding-right: 1vw;
    padding-left: 1vw;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 400;
    font-size: 1.25vw;
    line-height: 1.56vw;
    border: 0.16vw solid #865DF8;
    border-radius: 1.56vw;
    margin-bottom: 2.60vw;

}

input::placeholder {

    padding-left: 23.96vw;
    color: rgba(134, 93, 248, 0.59);
}


.name_chat {
    display: flex;
    margin-bottom: 0.78vw;
    width: 56.35vw;
    height: 7.19vw;
    align-items: center;
    border-radius: 1.56vw;
}

.name_chat_f {
    font-size: 1.25vw;
    line-height: 1.56vw;
    color: #865DF8;
}

.citata {

    width: 36.46vw;
    height: 0.78vw;

    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.04vw;
    color: rgba(134, 93, 248, 0.47);
}

.img_chat {
    margin-left: -0.26vw;
    margin-top: 2.60vw;
    width: 2.44vw;
    height: 2.44vw;
}

.img_avatar {

    width: 5.36vw;
    height: 5.36vw;
    margin-top: 0.78vw;
}

.chats {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    margin-left: 1.04vw;
    margin-top: 0.52vw;
}

.text_chat {
    margin-top: -0.52vw;
}

a {
    text-decoration: none;
}




.active {
    text-decoration: underline;

}

.content {
    margin-top: 1.04vw;
    padding: 0.52vw;
}

.but_categories {
    border-radius: 1.56vw;
    background-color: #F2EDFE;
    border: none;
    padding: 1.04vw;

    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;

}

#applicantsContent,
#completedContent {
    display: none;
}

.but_categories1 {
    margin-left: 54.69vw;
    margin-top: -5.73vw;
    display: flex;
    flex-direction: column;
    background: #F2EDFE;
    border-radius: 1.56vw;

    width: 15.63vw;
    height: 6.20vw;


}

.but_zayav {
    margin-top: 1.56vw;
}

.add_but {
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
    margin-bottom: 0.52vw;
}

.reject_but {
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;
    margin-left: 2.08vw;
}

.friend_table {
    position: fixed;
    width: 22.03vw;
    height: 24.48vw;
    background-color: #F2EDFE;
    border-radius: 1.56vw;

    margin-left: 49.48vw;
    margin-top: -19.79vw;
}

.main_title_friends {
    width: 15.63vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;
    margin-left: 1.56vw;
    margin-top: 1.30vw;
    margin-bottom: 1.56vw;
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


.menu_mob {
    display: none;

}

@media (max-width:320px) {
    .menu_mob {
        display: block;
    }

    .content {
        margin-left: 0.05vw;
    }

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
        margin-bottom: 8vw;

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

    .search-icon {

        position: absolute;
        margin-right: 0.78vw;
        margin-left: 2vw;
        margin-top: 1.7vw;
    }

    .search-icon img {
        width: 5vw;
        height: 5vw;
        margin-left: 2vw;

    }

    .search-input {
        margin-left: 2vw;
        width: 62.50vw;
        padding-left: 7vw;
        padding-top: 2vw;
        padding-bottom: 2vw;

        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 400;
        font-size: 2vw;
        line-height: 1.56vw;
        border: 0.16vw solid #865DF8;
        border-radius: 5vw;
        margin-bottom: 2.60vw;

    }

    input::placeholder {

        padding-left: 30vw;
        color: rgba(134, 93, 248, 0.59);
    }


    .name_chat {
        margin-top: 10vw;
        display: flex;
        margin-bottom: 5vw;
        width: 56.35vw;
        height: 7.19vw;
        background: none;
        border-radius: 1.56vw;
    }

    .name_chat_f {
        width: 80vw;
        font-size: 3vw;
        line-height: 1vw;
        color: #865DF8;
    }

    .citata {

        width: 65vw;
        height: 5vw;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 2vw;
        font-family: 'Unbounded';


        color: rgba(134, 93, 248, 0.47);
    }

    .img_chat {
        margin-left: -5vw;
        margin-top: 5vw;
        width: 4vw;
        height: 4vw;
    }

    .img_avatar {

        width: 10vw;
        height: 10vw;
        margin-top: 0.78vw;
        margin-left: 1.04vw;
    }

    .chat {
        margin-top: 10vw;

    }

    .chats {
        margin-left: 2vw;
        margin-top: 0.52vw;
    }

    .text_chat {
        margin-top: -0.52vw;
    }


    .but_categories {
        border-radius: 1.56vw;
        background-color: #F2EDFE;
        border: none;
        padding: 2vw;

        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 400;
        font-size: 2vw;
        line-height: 1.30vw;
        color: #865DF8;

    }

    #applicantsContent,
    #completedContent {
        display: none;
    }

    .but_categories1 {
        margin-left: 75vw;
        margin-top: -11vw;
        display: flex;
        flex-direction: column;
        background: #F2EDFE;
        border-radius: 1.56vw;

        width: 20vw;
        height: 8vw;


    }

    .but_zayav {

        margin-left: -15vw;
    }

    .add_but {
        display: flex;
        justify-content: center;
        width: 15vw;
        margin-left: 2vw;
        height: 4vw;
        background: #865DF8;
        border-radius: 1.56vw;
        font-weight: 300;
        font-size: 2vw;
        line-height: 1.30vw;
        color: #FFFFFF;
        align-items: center;

        margin-bottom: -2vw;
    }

    .reject_but {

        font-weight: 300;
        font-size: 2vw;
        line-height: 1.30vw;
        color: #865DF8;
        margin-left: 3vw;
    }
}

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
</style>