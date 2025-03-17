<template>
    <div class="outer-padding">
        <div class="container-main-block">
            <div class="main-block">

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
                    class="btn">Удалить</button>
                <button @click="openChat(friendInfo.id)" class="btn">Открыть чат</button>
            </p>
            <p>ВСЕ ЛЮДИ В МИРЕ</p>
            <p v-for="people in allPeople">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <!-- Исправить когда будет Vuex на  v-if="people.id != Твой id" -->
                <button @click="sendRequestToFriend(people.id)" class="btn">Добавить</button>
            </p>
            <p>Заявки в друзья</p>
            <p v-for="people in friendRequest">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <button @click="sendRequestToFriend(people.id)" class="btn">Принять</button>
                <button @click="deleteRequestToFriend(people.id)" class="btn">Отклонить</button>
            </p>
            <p>Отправленные мной заявки</p>
            <p v-for="people in myRequest">{{ people.user_info.surname + ' ' + people.user_info.name + people.id }}
                <button @click="deleteRequestToFriend(people.id)" class="btn">Отменить заявку</button>
            </p>



            <p style="background-color: green;">ПОСТЫ</p>
            <input class="btn" placeholder="название" v-model="postInput.title" name="title" type="text">
            <input class="btn" placeholder="текст" v-model="postInput.body" name="body" type="text">
            <input type="file" multiple @change="handlePhotoUpload" accept="image/*">
            <select @change="selectCategory" id="categories" class="btn">
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}
                </option>
            </select>
            <button @click="createPost" class="btn">Создать пост</button>


            <p>МОИ ПОСТЫ</p>
            <p v-for="post in myPosts" :key="post.id" class="btn">
            <p style="display: flex; justify-content: space-between;">Имя:{{ post.title }}
            <div class="likes">Лайки:{{ post.likes.length }}</div>
            </p>
            <p style="display: flex; justify-content: space-between;">Тело:{{ post.body }} <button
                    @click="toggleLike(post.id)" class="btn">Поставить лайк</button></p>

            <div class="space">
                <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/' + photo.path"
                    style="width: 100px; height: auto; margin: 5px;">
            </div>
            <button class="btn" @click="deletePost(post.id)">Удалить пост {{ post.id }}</button>
            <button class="btn" @click="changePost(post.id)">Изменить пост {{ post.id }}</button>
            <button class="btn" @click="openComments(post.id)">Посмотреть коментарии {{ post.id }}</button>
            <div v-if="isModalOpen == post.id">
                <p>Комментарии</p>
                <p v-for="comment in comments" :key="comment.comment.id" style="border: 1px solid red;">
                    <div>{{ comment.user.name +' '+comment.user.surname}}</div>
                    <div style="display: flex; justify-content: space-between;"><div>{{ comment.comment.body }}</div><div>{{ comment.comment.created_at }}</div></div>
                    
                </p>
                <input type="text" class="btn" v-model="commentInput" placeholder="Введите ваш коментарий">
                <button @click="addComment(post.id)" class="btn">Отправить комментарий</button>
                <button @click="closeComments(post.id)" class="btn">Закрыть комментарий</button>
            </div>
            </p>



            <p>Все посты</p>
            <p v-for="post in allPosts" :key="post.id" class="btn">
            <p style="display: flex; justify-content: space-between;">Имя:{{ post.title }}
            <div class="likes">Лайки:{{ post.likes.length }}</div>
            </p>
            <p style="display: flex; justify-content: space-between;">Тело:{{ post.body }} <button
                    @click="toggleLike(post.id)" class="btn">Поставить лайк</button></p>
            <div class="space">
                <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/' + photo.path"
                    style="width: 100px; height: auto; margin: 5px;">
            </div>
            <button class="btn" @click="openComments(post.id)">Посмотреть коментарии {{ post.id }}</button>

            <!-- Убрать false и установить, id != user_id или чет такое, чтобы сразу два мод окна не отрывалось
            МОИ ПОСТЫ И ВСЕ ПОСТЫ -->
            <div v-if="isModalOpen == post.id && false">
                <p>Комментарии</p>
                <p v-for="comment in comments" :key="comment.comment.id" style="border: 1px solid red;">
                    <div>{{ comment.user.name +' '+comment.user.surname}}</div>
                    <div style="display: flex; justify-content: space-between;"><div>{{ comment.comment.body }}</div><div>{{ comment.comment.created_at }}</div></div>
                    
                </p>
                <input type="text" class="btn" v-model="commentInput" placeholder="Введите ваш коментарий">
                <button @click="addComment(post.id)" class="btn">Отправить комментарий</button>
                <button @click="closeComments(post.id)" class="btn">Закрыть комментарий</button>
            </div>
            </p>

        </div>
    </div>

</template>
<script>
import axios from 'axios';

export default {
    name: 'Main',
    data() {
        return {
            linkApp: '',
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
            postInput: [],
            //Категории
            categories: [],
            selectedCategory: 1,
            //Коментарии
            isModalOpen: null,
            commentInput: '',
            comments: [],
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
        // ДРУЗЬЯ
        // Доделать темку
        async openChat(friendId) {
            await axios.post(`/chat/${friendId}/message`,{})
            .then(response=>{
                console.log(response.data);
                this.$router.push(`/chat/${friendId}`)
            })

        },
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
            axios.get('/post/get_posts').then(response => {
                this.allPosts = response.data;
            })
                .catch((error) => {
                    this.allPosts = error.response;
                })
        },
        getCategories() {
            axios.get('/get/categories').then(response => {
                this.categories = response.data;
            })
                .catch((error) => {
                    this.categories = error.response;
                })
        },




        sendRequestToFriend(id) {
            axios.post('/friend/send_friend_request', { 'id': id })
                .then(response => {
                    console.log(response.data)
                })
        },
        deleteRequestToFriend(id) {
            axios.post(`/friend/delete_friend_request`, { 'id': id })
                .then(response => {
                    console.log(response.data)
                })
        },
        deleteFriend(id) {
            axios.delete(`/friend/${id}/delete`, {})
                .then(response => {
                    console.log(response.data)
                })
        },



        //ПОСТЫ
        deletePost(id) {
            axios.delete(`/post/user/delete/${id}`, { 'id': id })
                .then(response => {
                    console.log(response.data);
                    this.getMyPosts();
                    this.getAllPosts();
                })
        },
        createPost() {
            const formData = new FormData();
            formData.append('title', this.postInput.title);
            formData.append('body', this.postInput.body);
            console.log(this.selectedCategory);
            formData.append('category_id', this.selectedCategory);
            if (this.postInput.photos) {
                this.postInput.photos.forEach(file => {
                    formData.append('photos[]', file);
                });
            }


            axios.post('/post/create', formData, {
                headers: {
                    "Content-Type": 'multipart/form-data'
                }
            })
                .then(response => {
                    console.log(response.data);
                    this.getMyPosts();
                    this.getAllPosts();
                })
        },
        changePost(id) {
            axios.patch(`/post/user/change/${id}`, {
                'title': 'CHANGED',
                'body': 'CHANGED'
            })
                .then(response => {
                    this.getMyPosts();
                    this.getAllPosts();
                })
        },
        handlePhotoUpload(event) {
            const selectedPhotos = Array.from(event.target.files);
            this.postInput.photos = selectedPhotos;
            console.log(this.postInput.photos)
        },
        //Лайк
        toggleLike(postId) {
            axios.post(`/post/${postId}/like`, {})
                .then(response => {
                    console.log(response.data);
                    this.getAllPosts();
                })
        },
        //Комментарий
        async openComments(postId)
        {
            this.isModalOpen = postId;
            await axios.get(`/post/${postId}/comment`)
            .then(response=>{
                this.comments = response.data;
            })
            
        },
        closeComments()
        {
            this.isModalOpen=null;
            this.comments = [];
        },
        addComment(postId) {
            axios.post(`/post/${postId}/comment`, {'body': this.commentInput})
                .then(response => {
                    console.log(response.data);
                    this.commentInput = '';
                    this.openComments(postId);
                })
        },
        //Категории
        selectCategory(event) {
            this.selectedCategory = event.target.value;
        }

    },
    mounted() {
        this.getAllPosts();
        this.getCategories();
        this.getFriends();
        this.getPeople();
        this.getRequestsFromFriends();
        this.getMyRequestsToFriends();
        this.getMyPosts();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}/storage`;
    }
}
</script>
<style>
.btn {
    border: 2px solid violet;
    margin-right: 20px;
}

.space {
    display: flex;
}
</style>