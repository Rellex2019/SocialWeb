<template>

    <SideMenu />
    <div class="content" id="content">

        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>

        <div class="block1">

            <div class="name_friend_chat" v-if="allMessages.users && allMessages.users.length == 2">

                <img class="avatar_friend_chat" :src="linkApp + getOtherUserAvatar(allMessages.users, user.id)">

                <p class="name_friend_chat">
                <div class="">{{ allMessages.chat.name }}</div>
                </p>
            </div>

        </div>

    </div>

    <div class="content">
        <div class="container">
            <div class="container_messages">
                <textarea class="enter_message" @keydown="handleKeyDown" v-model="inputData"
                    placeholder="Введите сообщение"></textarea>
                <a @click.prevent="sendMessage"><img :src="linkApp + '/img/orpr.png'" alt="" class="icon_enter"></a>
            </div>
        </div>
    </div>

    <div class="content contenttt">
        <div class="container container2">
            <div class="container_window" ref="messagesContainer">


                <div @scroll="checkReadStatus(message.id)" v-if="allMessages.chat && allMessages.chat.messages" v-for="message in allMessages.chat.messages"
                    class="" :key="message.id">
                    <div v-if="message.user_id && message.user_id != user.id" class="friend_messages">
                        <div class="msg">
                            <div class="msg_content_friend" v-html="message.content"></div>
                            <div class="time">{{ formatDate(message.updated_at) }}</div>
                            <span v-if="message.isRead" class="read-indicator">Прочитано</span>
                        </div>
                    </div>

                    <div v-if="message.user_id && message.user_id == user.id" class="my_messages">
                        <div class="my_msg">
                            <div class="msg_content_my" v-html="message.content"></div>
                            <div class="time_my">{{ formatDate(message.updated_at) }}</div>
                            <span v-if="message.isRead" class="read-indicator">Прочитано</span>
                        </div>
                        <button @click="deleteMessage(message.id)" style="margin-top: -5px;">Удалить</button>
                    </div>
                </div>


            </div>
        </div>
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
        deleteMessage(id)
        {
            axios.delete(`/chat/${id}/message/delete`)
            .then(response=>{
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
.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    /* position: absolute; */
    position: fixed;
    margin-left: 41.67vw;
    margin-top: 15.63vw;
    width: 51.72vw;
    height: 51.04vw;
}


.main_title {
    width: 8.75vw;
    height: 2.08vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #C68DFE;
}

.block1 {

    position: fixed;
    margin-left: 30vw;
    margin-top: 1vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.name_friend_chat {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1.56vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.56vw;
    color: #865DF8;
}

.avatar_friend_chat {
    max-width: 120px;
    width: 3.39vw;
    height: 3.39vw;
    border-radius: 50px;
    margin-left: -26.04vw;
}

.container_messages {
    display: flex;
    position: fixed;
    /* margin-top: 40.63vw; */
    bottom: 20px;
}

.enter_message {
    width: 67vw;
    background-color: #ffffff;
    border: 0.010vw solid #865DF8;
    border-radius: 1.04vw;

    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    padding: 1.25vw 1.35vw;
}

.icon_enter {
    cursor: pointer;
    position: absolute;
    margin-left: -3.65vw;
    margin-top: 0.52vw;
}

/* ---------------------------------------------- */
.container_window {
    /* display: flex;
    justify-content: space-between; */
    padding-right: 6.77vw;
}

.friend_messages {
    margin-top: 10px;
    margin-bottom: 1.04vw;
}

.msg {
    width: 31.46vw;
    background: #FFFFFF;
    border-radius: 0.52vw 0.52vw 0.52vw 0px;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;
    margin-top: 1.04vw;
}

.msg_content_friend {
    word-break: break-all;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #000000;
}

.time {

    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.30vw;
    color: #000000;
    display: flex;
    justify-content: end;
}

.my_messages {
    margin-top: 10px;
    display: flex;
    align-items: flex-end;
    flex-direction: column;
    gap: 1.04vw;
}

.my_msg {
    width: 31.46vw;
    border-radius: 0.52vw 0.52vw 0px 0.52vw;
    background: #865DF8;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;

}

.msg_content_my {
    word-break: break-all;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.time_my {
    display: flex;
    justify-content: end;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.30vw;
    color: #FFFFFF;

}

.avatar_mobile,
.add_post,
.navbar {
    display: none;
}

.icon_enter {
    margin-top: 1vw;
    margin-left: -3vw;
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
        display: block;
        width: 10.63vw;
        height: 10.63vw;
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
        color: #865DF8;
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
        border-top: 0.31vw solid #865DF8;
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
        width: 36px;
        height: 36px;
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
        color: #865DF8;
    }

    .msg {
        width: 215px;
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
        width: 215px;
        border-radius: 1.52vw 1.52vw 0px 1.52vw;
        background: #865DF8;
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
        width: 300px;

        border-radius: 20px;
        font-weight: 400;
        font-size: 12px;
        line-height: 15px;

    }

    .enter_message::placeholder {}

    .icon_enter {
        width: 27px;
        height: 28px;
        cursor: pointer;
        position: absolute;
        margin-left: -9.65vw;
        margin-top: 0.52vw;
    }


    .name_friend_chat {
        margin-top: 5vw;
    }

}



* {
    font-family: "Unbounded", serif;
    font-optical-sizing: auto;
    text-decoration: none;
    margin: 0;
    padding: 0;
}


body {
    overflow-x: hidden;


    background: rgba(134, 93, 248, 0.11);


}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
    padding-bottom: 5vw;
}



/* -------------------------------------------------------------------------- */
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

.content {
    overflow-x: hidden;
    background: rgba(134, 93, 248, 0.11);
    margin-left: 16.83vw;
    padding: 1.04vw;
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

.avatar_block {
    width: 17.50vw;
    height: 16.46vw;

    background: #FFFFFF;
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

.menu_mob {
    display: none;
}

@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;
    }

    .name_cart {
        margin-left: 78vw;
        width: 20.25vw;
        height: 4.25vw;
        background: #865DF8;
        border-radius: 5vw;
        font-weight: 300;
        font-size: 3vw;
        line-height: 1.30vw;
        color: #FFFFFF;
        align-items: center;

    }

    .container_window {
        padding-right: 0.1vw;
    }

    .menu_mob {
        display: block;
        width: 13vw;
        height: 5vw;
        margin-top: -6vw;
        margin-left: 1vw;


    }

}
</style>