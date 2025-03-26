<template>
    <SideMenu />
    <div class="content">
        <div class="container">
            <div class="block1">
                <div class="category_slide">
                    <button @click="showContent('friends')" :class="{ active: activeTab === 'friends' }"
                        class="switch">Друзья</button>
                    <button @click="showContent('requests')" :class="{ active: activeTab === 'requests' }"
                        class="switch">Заявки</button>
                    <button @click="showContent('search')" :class="{ active: activeTab === 'search' }"
                        class="switch">Поиск</button>

                    <div class="content2">
                        <!-- Friends  -->
                        <div v-show="activeTab === 'friends'">
                            <div class="search-container">
                                <img :src="linkApp + '/img/friends_img/search.png'" alt="Search" class="search">
                                <input type="text" v-model="searchInput" @input="startFilter" class="search-input"
                                    placeholder="Введите имя и фамилию">
                            </div>

                            <div v-if="filteredFriends.length > 0">
                                <div v-for="friend in filteredFriends" :key="friend.id" class="my_friend">
                                    <img v-if="friend.user_info.avatar"
                                        :src="linkApp + '/storage/' + friend.user_info.avatar" alt=""
                                        class="avatar_friend">
                                    <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="" class="avatar_friend">
                                    <div class="name_my_friend">
                                        <p class="friend_name">{{ friend.user_info.name }} {{ friend.user_info.surname
                                            }}</p>
                                        <p class="quote_friend">{{ friend.user_info.quote || 'цитата' }}</p>
                                    </div>
                                    <img @click="openChat(friend.id, friend.chats?.id)"
                                        :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" alt="Message"
                                        class="icon_mess">
                                    <img @click="deleteFriend(friend.id)" :src="linkApp + '/img/welcome_img/close.png'"
                                        alt="Reject" class="icon_mess">
                                </div>
                            </div>
                            <div v-else class="no-friends">
                                <p>Друзей не найдено</p>
                            </div>
                        </div>

                        <!-- Requests -->
                        <div v-show="activeTab === 'requests'">
                            <div v-if="friendRequest.length > 0">
                                <p class="no-requests">Входящие заявок</p>
                                <div v-for="request in friendRequest" :key="request.id" class="my_friend">
                                    <img v-if="request.avatar" :src="linkApp + '/storage/' + request.avatar" alt=""
                                        class="avatar_friend">
                                    <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="" class="avatar_friend">
                                    <div class="name_my_friend">
                                        <p class="friend_name">{{ request.name }} {{ request.surname }}</p>
                                        <p class="quote_friend">{{ request.quote || 'цитата' }}</p>
                                    </div>
                                    <div @click="sendRequestToFriend(request.id)" class="accept">✓</div>
                                    <img @click="deleteRequestToFriend(request.id)"
                                        :src="linkApp + '/img/welcome_img/close.png'" alt="Reject" class="icon_mess">
                                </div>
                            </div>
                            <div v-if="sentRequest.length > 0">
                                <p class="no-requests">Отправленные</p>
                                <div v-for="request in sentRequest" :key="request.id" class="my_friend">
                                    <img v-if="request.avatar" :src="linkApp + '/storage/' + request.avatar" alt=""
                                        class="avatar_friend">
                                    <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="" class="avatar_friend">
                                    <div class="name_my_friend">
                                        <p class="friend_name">{{ request.name }} {{ request.surname }}</p>
                                        <p class="quote_friend">{{ request.quote || 'цитата' }}</p>
                                    </div>
                                    <img @click="deleteRequestToFriend(request.id)"
                                        :src="linkApp + '/img/welcome_img/close.png'" alt="Reject" class="icon_mess">
                                </div>
                            </div>
                            <div v-else class="no-requests">
                                <p>Нет активных заявок</p>
                            </div>
                        </div>



                        <!-- Search  -->
                        <div v-show="activeTab === 'search'">
                            <div class="search-container">
                                <img :src="linkApp + '/img/friends_img/search.png'" alt="Search" class="search">
                                <input type="text" v-model="searchInput" @input="startFilter" class="search-input"
                                    placeholder="Введите имя и фамилию">
                            </div>

                            <div v-if="filteredPeoples.length > 0">
                                <div v-for="friend in filteredPeoples" :key="friend.id" class="my_friend">
                                    <img v-if="friend.user_info.avatar"
                                        :src="linkApp + '/storage/' + friend.user_info.avatar" alt=""
                                        class="avatar_friend">
                                    <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="" class="avatar_friend">
                                    <div class="name_my_friend">
                                        <p class="friend_name">{{ friend.user_info.name }} {{ friend.user_info.surname
                                            }}</p>
                                        <p class="quote_friend">{{ friend.user_info.quote || 'цитата' }}</p>
                                    </div>
                                    <div @click="sendRequestToFriend(friend.id)" class="text_search">Отправить заявку
                                    </div>
                                </div>
                            </div>
                            <div v-else class="no-friends">
                                <p>Мы не смогли найти друзей по вашему запросу</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import axios from 'axios';
import SideMenu from '../components/sideMenu.vue';

export default {
    name: 'Friends',
    components: {
        SideMenu
    },
    data() {
        return {
            linkApp: '',
            activeTab: 'friends',
            searchInput: '',
            friends: [],
            filteredFriends: [],
            friendRequest: [],
            sentRequest: [],
            filteredPeoples: [],
            peoples: [],
        }
    },
    methods: {
        showContent(section) {
            this.activeTab = section;
        },
        async getFriends() {
            const response = await axios.get('/friend');
            this.friends = response.data;
            this.filteredFriends = this.friends; // Изначально отображаем всех друзей
        },
        async getRequestsFromFriends() {
            const response = await axios.get('/friend/get_friend_request');
            this.friendRequest = response.data.users;
        },
        async getRequestsToFriend() {
            const response = await axios.get('/friend/get_user_request');
            this.sentRequest = response.data.users;
        },
        async getPeople() {
            await axios.get('/people').then(response => {
                this.peoples = response.data;
                this.filteredPeoples = this.peoples;
            })
        },
        startFilter() {
            const searchTerm = this.searchInput.toLowerCase();
            this.filteredFriends = this.friends.filter(friend => {
                const fullName = (friend.user_info.name + ' ' + friend.user_info.surname).toLowerCase();
                return fullName.includes(searchTerm);
            });
            this.filteredPeoples = this.peoples.filter(people => {
                const fullName = (people.user_info.name + ' ' + people.user_info.surname).toLowerCase();
                return fullName.includes(searchTerm);
            });
        },
        getFriendAvatar(friend) {
            return friend.user_info && friend.user_info.avatar
                ? `${this.linkApp}/storage/${friend.user_info.avatar}`
                : `${this.linkApp}/img/welcome_img/avatar_account.png`;
        },
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`, { chatId })
                .then(response => {
                    this.$router.push(`/chat/${response.data.chatId}`)
                })
        },
        deleteFriend(id) {
            let confirm = window.confirm('Вы уверены что хотите удалить дргуа?');
            if (confirm) {
                axios.delete(`/friend/${id}/delete`, {})
                    .then(response => {
                        this.getFriends();
                        this.startFilter();
                    })
            }

        },
        sendRequestToFriend(id) {
            axios.post('/friend/send_friend_request', { 'id': id })
                .then(response => {

                    if (response.data.user) {
                        this.sentRequest.push(response.data.user);
                        this.filteredPeoples = this.filteredPeoples.filter(people => { return people.id != response.data.user.id });

                    }
                    if (response.data.id) {
                        this.friendRequest = this.friendRequest.filter(request => { return request.id != response.data.id });
                        this.getFriends();
                    }

                })
        },
        deleteRequestToFriend(id) {
            axios.post(`/friend/delete_friend_request`, { 'id': id })
                .then(response => {
                    this.getRequestsFromFriends();
                    this.getPeople();
                    this.sentRequest = this.sentRequest.filter(request => { return request.id != id });
                })
        },
    },
    created() {
        this.linkApp = import.meta.env.VITE_APP_URL;
        this.getPeople();
        this.getFriends();
        this.getRequestsFromFriends();
        this.getRequestsToFriend();
    },
    mounted() {
        this.startFilter();
        this.showContent('friends');
    }
}
</script>

<style scoped>
.text_search {
    cursor: pointer;
    font-size: 1.1vw;
    color: #FFFFFF;
}

.accept {
    cursor: pointer;
    font-size: 2.6vw;
    color: white;
    margin-right: 2vw;
    transition: 0.3s;
}

.accept:hover {
    color: #181C22;
    font-size: 2.8vw;
}

/* Base Styles */
.content {
    margin-left: 20.83vw;
    padding: 1.04vw;
}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
}

/* Header Styles */
.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

/* Tabs Styles */
.switch {
    width: 7.50vw;
    font-weight: 400;
    font-size: 1.30vw;
    line-height: 2.08vw;
    color: #C68DFE;
    background-color: #181C22;
    border: none;
    margin-right: 1.56vw;
    cursor: pointer;
    transition: all 0.3s ease;
}

.switch:hover {
    opacity: 0.8;
}

.switch.active {
    text-decoration: underline;
    text-underline-offset: 0.52vw;
}

.content2 {
    margin-top: 1.04vw;
    padding: 0.52vw;
}

/* Search Styles */
.search-container {
    position: relative;
    margin-bottom: 2.60vw;
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
    background-color: #181C22;
    color: #fff;
}

.search-input::placeholder {
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.56vw;
    color: rgba(255, 255, 255, 0.68);
}

.search {
    width: 1.82vw;
    height: 1.82vw;
    position: absolute;
    top: 50%;
    left: 0.52vw;
    transform: translateY(-50%);
}

/* Friends List Styles */
.my_friend {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 1.51vw;
    padding: 0.52vw;
    transition: all 0.3s ease;
}

.my_friend:hover {
    background-color: rgba(198, 141, 254, 0.1);
    border-radius: 0.52vw;
}

.avatar_friend {
    width: 4.17vw;
    height: 4.17vw;
    border-radius: 50%;
    object-fit: cover;
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
    margin-left: auto;
    cursor: pointer;
    transition: all 0.3s ease;
}

.icon_mess:hover {
    transform: scale(1.1);
}

/* Empty States */
.no-friends,
.no-requests {
    font-size: 1.2vw;
    padding: 2vw 0.2vw;
    color: rgba(255, 255, 255, 0.5);
}

/* Mobile Styles */
@media (max-width: 766px) {
    .content {
        margin-left: 0;
        padding: 3vw;
    }

    .switch {
        font-size: 3.75vw;
        margin-right: 3vw;
    }

    .search-input {
        width: 80vw;
        padding: 2vw 10vw;
        font-size: 3.25vw;
        border-radius: 1.52vw;
        margin-bottom: 5vw;
    }

    .search-input::placeholder {
        font-size: 3.25vw;
    }

    .search {
        width: 4vw;
        height: 4vw;
        left: 3vw;
    }

    .avatar_friend {
        width: 13.44vw;
        height: 13.44vw;
    }

    .friend_name {
        font-size: 3.75vw;
        line-height: 5.31vw;
    }

    .quote_friend {
        font-size: 3.04vw;
        margin-top: 1vw;
    }

    .icon_mess {
        width: 8.75vw;
        height: 9.38vw;
    }

    .my_friend {
        margin-bottom: 5vw;
    }
}
</style>
