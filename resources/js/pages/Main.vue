<template>
    <div class="outer-padding">
        <div class="container-main-block">
            <div class="main-block">
                b
            </div>
            <button class="btn" @click="sendDataForRegistration">Registration</button>
            <button class="btn" @click="sendDataForAuth">Authorization</button>
            <button class="btn" @click="checkAuth">CheckAuth</button>
            <button class="btn" @click="logOut">LogOut</button>
            <input class="btn" type="text" @input="checkAvailability" v-model="dataReg.login"
                :style="{ borderColor: loginAvailability == true ? 'green' : 'red' }" placeholder="login for reg">
            <div v-if="isLoadingAvailability" class="loading">Loading...</div>
            <p :style="{ backgroundColor: loginAvailability == true ? 'green' : 'red' }"
                v-if="!isLoadingAvailability && loginAvailability != null">Логин {{ dataReg.login }} {{
                    loginAvailability == true ? 'свободен' : 'занят' }}</p>

            <p>ТВОИ ДРУЗЬЯ</p>
            <p v-for="friendInfo in yourFriendsInfo" :key="friendInfo.id">{{ friendInfo.user_info.surname + ' ' +
                friendInfo.user_info.name + friendInfo.id }}<button @click="deleteFriend(friendInfo.id)"
                    class="btn">Удалить</button></p>
            <p>ВСЕ ЛЮДИ В МИРЕ</p>
            <p v-for="people in allPeople">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <button @click="sendRequestToFriend(people.id)" class="btn">Добавить</button>
            </p>
            <p>Заявки в друзья</p>
            <p v-for="people in friendRequest">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <button @click="sendRequestToFriend(people.id)" class="btn">Принять</button>
            </p>
            <p>Отправленные мной заявки</p>
            <p v-for="people in myRequest">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <button @click="deleteRequestToFriend(people.id)" class="btn">Отменить заявку</button>
            </p>
            <p style="background-color: green;">ПОСТЫ</p>
            <button @click="createPost" class="btn">Создать пост</button>
            <p>МОИ ПОСТЫ</p>
            <p v-for="post in myPosts"><p>Имя:{{post.title}}</p><p>Тело:{{ post.body }}</p></p>
            <p>Все посты</p>
            <p v-for="post in allPosts"><p>Имя:{{post.title}}</p><p>Тело:{{ post.body }}</p></p>

        </div>
    </div>

</template>
<script>
import axios from 'axios';

export default {
    name: 'Main',
    data() {
        return {
            dataReg:
            {
                "name": "Кирилл",
                "surname": "Зятчин",
                "login": "thekirz",
                "email": "kirill@gmail.com",
                "password": "123123",
                "password_repeat": "123123"
            },
            dataLog:
            {
                "login": "thekirz",
                "password": "123123",
            },
            loginAvailability: null,
            isLoadingAvailability: false,



            //Друзья все люди
            yourFriendsInfo: [],
            allPeople: [],
            friendRequest: [],
            myRequest: [],
            //Посты
            myPosts: [],
            allPosts: [],
        }
    },
    methods: {
        sendDataForRegistration() {
            axios.post('/signup', this.dataReg)
                .then(response => {
                    console.log('Зарегистрирован', response.data);
                })
        },
        sendDataForAuth() {
            axios.post('/login', this.dataLog)
                .then(response => {
                    console.log('Авторизован', response.data);
                })
        },
        logOut() {
            axios.get('/logout').then(response => {
                console.log(response.data);
            });
        },
        checkAuth() {
            axios.get('/check_auth').then(response => {
                console.log(response.data);
            });
        },
        async checkAvailability() {
            this.isLoadingAvailability = true;
            await axios.post('/check/login_availability', { 'login': this.dataReg.login })
                .then(response => {
                    this.loginAvailability = response.data;
                })
                .finally(() => {
                    this.isLoadingAvailability = false;
                })
                .catch(error => {
                    console.log(error.response.data);
                    this.loginAvailability = null;
                })
        },
        //ДРУЗЬЯ
        getFriends() {
            axios.get('/friend').then(response => {
                this.yourFriendsInfo = response.data;
            })
                .catch((error) => {
                    this.yourFriendsInfo = error.response;
                })
        },
        getPeople() {
            axios.get('/people').then(response => {
                this.allPeople = response.data;
            })
                .catch((error) => {
                    this.allPeople = error.response;
                })
        },
        getRequestsFromFriends() {
            axios.get('/friend/get_friend_request').then(response => {
                this.friendRequest = response.data;
            })
                .catch((error) => {
                    this.friendRequest = error.response;
                })
        },
        getMyRequestsToFriends() {
            axios.get('/friend/get_user_request').then(response => {
                this.myRequest = response.data;
            })
                .catch((error) => {
                    this.myRequest = error.response;
                })
        },
        getMyPosts() {
            axios.get('/post/my_posts').then(response => {
                this.myPosts = response.data;
            })
                .catch((error) => {
                    this.myPosts = error.response;
                })
        },
        getAllPosts() {
            axios.get('/post/my_posts').then(response => {
                this.allPosts = response.data;
            })
                .catch((error) => {
                    this.allPosts = error.response;
                })
        },




        sendRequestToFriend(id) {
            axios.post('/friend/send_friend_request', { 'id': id })
                .then(response => {
                    console.log(response.data)
                })
        },
        deleteRequestToFriend(id) {
            axios.post('/friend/delete_friend_request', { 'id': id })
                .then(response => {
                    console.log(response.data)
                })
        },
        deleteFriend(id) {
            axios.post('/friend/delete_friend', { 'id': id })
                .then(response => {
                    console.log(response.data)
                })
        },
        createPost() {
            axios.post('/post/create', {
                "title": "jojo",
                "body": "BODYDOY"
            })
                .then(response => {
                    console.log(response.data)
                })
        }
    },
    mounted() {
        this.getFriends();
        this.getPeople();
        this.getRequestsFromFriends();
        this.getMyRequestsToFriends();
        this.getMyPosts();
        this.getAllPosts();
    }
}
</script>
<style>
.btn {
    border: 2px solid violet;
    margin-right: 20px;
}
</style>