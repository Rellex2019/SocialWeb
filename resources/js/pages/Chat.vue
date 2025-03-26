<template>
    <SideMenu />

    <div class="content">
        <div class="container">
            <div class="block1">
                <div style="display: flex; align-items:  center; gap: 4vw;">
                    <div @click="$router.go(-1)"><img class="back_arrow"
                            :src="linkApp + '/img/profile_img/back_arrow.png'"></div>
                    <div class="name_friend_chat" v-if="allMessages.users && allMessages.users.length == 2">
                        <img class="avatar_friend_chat" :src="linkApp + getOtherUserAvatar(allMessages.users, user.id)">
                        <p class="name_friend_chat">{{ allMessages.chat.name }}</p>
                    </div>
                </div>
                <RouterLink to="/"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></RouterLink>
            </div>
        </div>
    </div>

    <div class="content contenttt">
        <div class="container container2">
            <div class="container_window" ref="messagesContainer">
                <div v-if="allMessages.chat && allMessages.chat.messages" v-for="message in allMessages.chat.messages"
                    :key="message.id" @scroll="checkReadStatus(message.id)">
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
                            <div class="time_my">{{ formatDate(message.updated_at) }}

                                <svg @click="deleteMessage(message.id)"xmlns="http://www.w3.org/2000/svg" width="16" class="trash" height="16" fill="currentColor"
                             viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>


                            </div>

                            <span v-if="message.isRead" class="read-indicator">Прочитано</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="container_messages">
                <textarea class="enter_message" @keydown="handleKeyDown" v-model="inputData"
                    placeholder="Введите сообщение"></textarea>
                <a @click.prevent="sendMessage"><img :src="linkApp + '/img/welcome_img/icon_enter.png'" alt=""
                        class="icon_enter"></a>
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
                    this.inputData = '';
                    this.selectedFile = null;
                    this.getMessages(); // Обновляем сообщения после отправки
                });
        },
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
        },
        handleKeyDown(event) {
            if (event.shiftKey && event.key === 'Enter') {
                event.preventDefault();
                this.sendMessage();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                container.scrollTop = container.scrollHeight;
            });
        },
        getOtherUserAvatar(users, currentUserId) {
            const otherUser = users.find(user => user.id !== currentUserId);
            return otherUser && otherUser.user_info.avatar ? '/storage/' + otherUser.user_info.avatar : '/img/img_acc.jpg';
        },
        checkReadStatus(messageId) {
            const messageElement = this.$el.querySelector(`[data-id="${messageId}"]`);
            if (messageElement) {
                const rect = messageElement.getBoundingClientRect();
                const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;
                if (isVisible) {
                    // Отправляем уведомление о прочтении
                    console.log('Сообщение прочитано', messageId);
                }
            }
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        },
        deleteMessage(id) {
            axios.delete(`/chat/${id}/message/delete`)
                .then(response => {
                    this.getMessages(); // Обновляем сообщения после удаления
                });
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
                this.getMessages();
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
.trash{
    color: red;
}
/* Основные стили */
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
}

.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
    cursor: pointer;
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
    color: #C68DFE;
}

.avatar_friend_chat {
    width: 3.39vw;
    height: 3.39vw;
    border-radius: 50%;
}

/* Стили сообщений */
.container_window {
    height: 60vh;
    overflow-y: auto;
    padding-right: 6.77vw;
    margin-bottom: 10vh;
}

.friend_messages {
    margin-bottom: 1.04vw;
    display: flex;
    justify-content: flex-start;
}

.msg {
    max-width: 31.46vw;
    background: #FFFFFF;
    border-radius: 0.52vw 0.52vw 0.52vw 0px;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;
    margin-top: 1.04vw;
}

.my_messages {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.04vw;
}

.my_msg {
    max-width: 31.46vw;
    border-radius: 0.52vw 0.52vw 0px 0.52vw;
    background: #C68DFE;
    padding: 0.94vw 1.88vw 0.78vw 0.99vw;
}

.msg_content_friend,
.msg_content_my {
    word-break: break-word;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
}

.msg_content_friend {
    color: #000000;
}

.msg_content_my {
    color: #FFFFFF;
}

.time,
.time_my {
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.30vw;
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.3vw;
}

.time {
    color: #000000;
}

.time_my {
    color: #FFFFFF;
}

.read-indicator {
    font-size: 0.7vw;
    color: #888;
    display: block;
    text-align: right;
}

/* Поле ввода сообщения */
.container_messages {
    position: fixed;
    bottom: 0;
    left: 20.83vw;
    right: 0;
    padding: 1vw;
    background: #181C22;
    display: flex;
    align-items: center;
}

.enter_message {
    width: 100%;
    background: #22232F;
    border: 0.05vw solid #C68DFE;
    border-radius: 1.04vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    padding: 1.25vw 1.35vw;
    resize: none;
}

.icon_enter {
    cursor: pointer;
    width: 2vw;
    height: 2vw;
    margin-left: 1vw;
}

/* Мобильные стили */
@media (max-width: 766px) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: 0;
        padding: 3vw;
    }

    .avatar_friend_chat {
        width: 10vw;
        height: 10vw;
    }

    .name_friend_chat {
        font-size: 4vw;
    }

    .msg,
    .my_msg {
        max-width: 70vw;
        padding: 2vw;
    }

    .msg_content_friend,
    .msg_content_my {
        font-size: 3.5vw;
    }

    .time,
    .time_my {
        font-size: 2.5vw;
    }

    .container_messages {
        left: 0;
        padding: 3vw;
    }

    .enter_message {
        font-size: 4vw;
        padding: 3vw;
    }

    .icon_enter {
        width: 6vw;
        height: 6vw;
    }

    .backgr_main {
        display: none;
    }
}

/* Общие стили */
* {
    font-family: "Unbounded", serif;
    font-optical-sizing: auto;
    text-decoration: none;
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
</style>