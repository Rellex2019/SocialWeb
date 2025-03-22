<template>

    <SideMenu />

    <div class="content">
        <div class="container">
            <div class="block1"  v-if="allMessages.users && allMessages.users.length == 2">
                <a @click="$router.go('-1')"><img class="back_arrow" :src="linkApp + '/img/profile_img/back_arrow.png'"></img></a>
                <div class="name_friend_chat">
                    <img class="avatar_friend_chat" :src="linkApp + getOtherUserAvatar(allMessages.users, user.id)">
                    <p class="name_friend_chat">{{ allMessages.chat.name }}</p>
                </div>
                <a href="./welcome.html"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="container_messages">
                <input class="enter_message" @keydown="handleKeyDown" v-model="inputData" placeholder="Введите сообщение"></input>
                <a @click.prevent="sendMessage"><img :src="linkApp + '/img/welcome_img/icon_enter.png'" alt="" class="icon_enter"></a>
            </div>
        </div>
    </div>

    <div class="content  content-padding">
        <div class="container">



            <div class="container_window"  @scroll="checkReadStatus(message.id)" v-if="allMessages.chat && allMessages.chat.messages" v-for="message in allMessages.chat.messages"
             :key="message.id">
                <div v-if="message.user_id && message.user_id != user.id" class="friend_messages">
                    <div class="msg">
                        <p class="msg_content_friend">{{ message.content }}</p>
                        <p class="time">{{ formatDate(message.updated_at) }}</p>
                    </div>
                </div>

                <div v-if="message.user_id && message.user_id == user.id" class="my_messages">
                    <div class="my_msg">
                        <p class="msg_content_my">{{ message.content }}</p>
                        <p class="time_my">{{ formatDate(message.updated_at) }}</p>
                    </div>
                    <button @click="deleteMessage(message.id)" style="margin-top: -5px;">Удалить</button>
                </div>
            </div>


        </div>
    </div>

    <div class="bckgr">
        <img :src="linkApp + '/img/welcome_img/backgr_main.png'" alt="" class="backgr_main">
    </div>




</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import SideMenu from '../components/sideMenu.vue';
export default {
    name: 'Chat',
    data() {
        return {
            chatId: this.$route.params.id,
            inputData: '',
            selectedFile: null,
            allMessages: [],
            linkApp: '',
        }
    },
    methods: {
        getMessages() {
            axios.get(`/chat/${this.chatId}/messages`)
                .then(response => {
                    this.allMessages = response.data;
                    this.$nextTick(this.scrollToBottom);
                })
                .catch(e => {
                    if (e.status === 403) {
                        this.$router.push('/')
                    }
                })
        },
        async sendMessage() {
            const formData = new FormData();
            formData.append('content', this.inputData.replace(/\n/g, '<br>'));
            if (this.selectedFile) {
                formData.append('file', this.selectedFile);
            }
            await axios.post(`/chat/${this.chatId}/message/send`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
                .then(response => {
                    // this.allMessages.push(response.data.data);
                    this.inputData = '';
                    this.selectedFile = null;
                    this.scrollToBottom();
                });

        },
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0]; // Получаем выбранный файл
        },
        pushBack() {
            this.$router.go(-1);
        },
        handleKeyDown(event) {
            if (event.shiftKey && event.key === 'Enter') {
                event.preventDefault();
                this.sendMessage();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                window.scrollTo({
                    top: document.body.scrollHeight, // Прокручиваем до конца страницы
                    behavior: 'smooth' // Плавная прокрутка
                });
            });
        },

        getOtherUserAvatar(users, currentUserId) {
            const otherUser = users.find(user => user.id !== currentUserId);
            return otherUser && otherUser.user_info.avatar ? '/storage/' + otherUser.user_info.avatar : '/img/img_acc.jpg'; // Вернуть аватар другого пользователя или аватар по умолчанию
        },
        checkReadStatus(messageId) {
            const messageElement = this.$refs.messagesContainer.querySelector(`.message[data-id="${messageId}"]`);
            const rect = messageElement.getBoundingClientRect();
            const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;

            if (isVisible) {
                // Отправляем уведомление о прочтении
                console.log('scroll')
            }
        },
        formatDate(dateString) {
            // const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            // return new Date(dateString).toLocaleDateString('ru-RU', options);
            const date = new Date(dateString);
            const hours = String(date.getHours()).padStart(2, '0'); // Получаем часы и добавляем ведущий ноль
            const minutes = String(date.getMinutes()).padStart(2, '0'); // Получаем минуты и добавляем ведущий ноль
            return `${hours}:${minutes}`; // Возвращаем строку с временем
        },
        deleteMessage(id) {
            axios.delete(`/chat/${id}/message/delete`)
                .then(response => {
                    console.log(response.data);
                })
        }
    },
    mounted() {
        this.getMessages();

        window.Echo.join(`chat.${this.chatId}`)
            .here((users) => {
                console.log('Пользователи в чате:', users);
            })
            .joining((user) => {
                console.log(`${user.id} присоединился к чату.`);
            })
            .leaving((user) => {
                console.log(`${user.id} покинул чат.`);
            })
            .listen('MessageSend', (e) => {
                console.log(e);
                this.getMessages();
                // this.allMessages.chat.messages.push(e.message);
            })
            .error((error) => {
                console.error('Ошибка при подключении к каналу:', error);
            });
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    components: {
        SideMenu
    }

}
</script>
<style scoped>
.content-padding{
    margin-bottom: 7vw !important;
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


.main_title{
    width: 8.75vw;
    height: 2.08vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #C68DFE;
}
.block1{
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}
.back_arrow{
    width: 1.20vw;
    height: 1.98vw;
}
.logo{
    width: 8.49vw;
    height: 1.56vw;
}
.name_friend_chat{
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.56vw;
    font-weight: 400;
font-size: 1.04vw;
line-height: 1.56vw;
color: #C68DFE;
}

.avatar_friend_chat{
width: 3.39vw;
height: 3.39vw;
margin-left: -26.04vw;
}

.container_messages{
    position: fixed;
    bottom: 40px;
}
.enter_message{
width: 61.09vw;
background: #22232F;
border: 0.05vw solid #C68DFE;
border-radius: 1.04vw;

font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
padding: 1.25vw 1.35vw;
}
.icon_enter{
    width: 2.1vw;
    cursor: pointer;
position: absolute;
margin-left: -3.65vw;
margin-top: 0.52vw;
}
/* ---------------------------------------------- */
.container_window{
    /* display: flex;
    justify-content: space-between; */
    padding-right: 6.77vw;
}

.friend_messages{
    margin-bottom: 1.04vw;
}
.msg{
width: 31.46vw;
background: #FFFFFF;
border-radius: 0.52vw 0.52vw 0.52vw 0px;
padding: 0.94vw 1.88vw 0.78vw 0.99vw;
margin-top: 1.04vw;
}
.msg_content_friend{
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #000000;
}
.time{
    width: 4.17vw;
font-weight: 400;
font-size: 0.83vw;
line-height: 1.30vw;
color: #000000;
margin-left: 29.17vw;
}

.my_messages{
    display: flex;
    align-items: flex-end;
    flex-direction: column;
gap: 1.04vw;
}

.my_msg{
width: 31.46vw;
border-radius: 0.52vw 0.52vw 0px 0.52vw;
background: #C68DFE;
padding: 0.94vw 1.88vw 0.78vw 0.99vw;

}
.msg_content_my{
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
}
.time_my{
font-weight: 400;
font-size: 0.83vw;
line-height: 1.30vw;
color: #FFFFFF;
margin-left: 29.17vw;

}

.avatar_mobile, .add_post, .navbar{
    display: none;
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
.back_arrow {
    width: 3.20vw;
    height: 6.98vw;
}
.avatar_friend_chat {
    width: 11.25vw;
    height: 11.25vw;
    margin-left: -26.04vw;
}
.name_friend_chat {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.56vw;
    font-weight: 400;
    font-size: 3.04vw;
    line-height: 1.56vw;
    color: #C68DFE;
}
.msg {
    width: 67.19vw;
    background: #FFFFFF;
    border-radius: 1.52vw 1.52vw 1.52vw 0px;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;
    margin-top: 1.04vw;
}
.msg_content_friend {
    font-weight: 400;
    font-size: 3.04vw;
    line-height: 4.30vw;
    color: #000000;
}
.time {
    width: 4.17vw;
    font-weight: 400;
    font-size: 1.83vw;
    line-height: 1.30vw;
    color: #000000;
    margin-left: 61.17vw;
}
.my_msg {
    width: 67.19vw;
    border-radius: 1.52vw 1.52vw 0px 1.52vw;
    background: #C68DFE;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;
}
.msg_content_my {
    font-weight: 400;
    font-size: 3.04vw;
    line-height: 4.30vw;
    color: #FFFFFF;
}
.time_my {
    font-weight: 400;
    font-size: 1.83vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    margin-left: 61.17vw;
}
.container_messages {
    position: fixed;
    margin-top: 149.63vw;
}
.enter_message {
    padding: 2.25vw 1.35vw;
width: 93.75vw;
background: #97999C;
border-radius: 6.25vw;
font-weight: 400;
font-size: 3.75vw;
line-height: 4.69vw;
color: #FFFFFF;
}
.enter_message::placeholder {
color: #FFFFFF;
}

.icon_enter {
    width: 8.44vw;
    height: 8.75vw;
    cursor: pointer;
    position: absolute;
    margin-left: -9.65vw;
    margin-top: 0.52vw;
}

.container_window{
    padding-right: 0.1vw;
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