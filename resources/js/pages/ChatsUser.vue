<template>
    <SideMenu />
    <div class="content" id="content">
        <div class="container">
            <div class="block1">
                <a href="/profile.html" style="display: none;"><img :src="linkApp + '/img/welcome_img/avatar_account.png'" class="avatar_mobile"  alt=""></a>
                
                <h1 class="main_title">Сообщения</h1>
                <a href="/welcome.html"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></a>
                <a href="#" style="display: none;"><img :src="linkApp + '/img/welcome_img/add_post_icon.png'" alt="" class="add_post" ></a>
            </div>
        </div>

        <div class="container">
            <div class="wrapper">
                <div class="chat">
                    <div v-if="Object.keys(chats).length>0" @click="openChat(chat.chat.id)" v-for="chat in chats.chats" :key="chat.id">
                        <div class="name_chat">
                            <div style="display: flex; align-items: center;">
                                <img class="img_avatar" v-if="chat.friend && chat.friend.avatar"
                                    :src="linkApp + '/storage/' + chat.friend.avatar" alt="">
                                <img class="img_avatar" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                                <div class="chats">
                                    <p class="name_chat_f" v-if="chat.friend" style="margin-left: 1vw;">{{ chat.friend.name+' '+chat.friend.surname }}</p>
                                    <p class="text_chat" v-html="chat.last_message.content.replace(/<br\s*\/?>/gi, ' ')" v-if="chat.last_message" style="margin-left: 1vw;"></p>
                                    <p class="text_chat" v-else>Этот чатик пустой</p>
                                </div>
                            </div>
                            <img class="img_chat" :src="linkApp + '/img/chats_img/unread.png'" alt="">
                        </div>
                        <hr class="palka">
                    </div>
                    <div v-else class="nothink">
                        <p>У вас нет активных чатов :(</p>
                        <p>Сперва создайте чат, нажав на значёк сообщения рядом с другом</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideMenu from '../components/sideMenu.vue';

export default {
    name: 'ChatsUser',
    data() {
        return {
            linkApp: '',
            chats: [],
        }
    },
    components: {
        SideMenu
    },
    methods: {
        async getChats() {
            await axios.get('/chat/users/get')
                .then(response => {
                    this.chats = response.data;
                })
        },
        async openChat(chatId) {
            this.$router.push(`/chat/${chatId}`)
        },
    },
    mounted() {
        this.getChats();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
}
</script>

<style scoped>
.block1{
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
.logo{
    width: 8.49vw;
    height: 1.56vw;
}
.main_title{
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #C68DFE;
}
/* ----------------------------------chat-----------------------------     */
.name_chat{
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 62.60vw;
    height: 5.63vw;
    border-radius: 1.56vw;
    margin-top: 1.04vw;
}

.name_chat_f{
    font-size: 1.04vw;
    line-height: 1.56vw;
    color: #ffff;
    margin-left: -26.04vw;
}
.text_chat{
    overflow: hidden;
    text-overflow: ellipsis;
    text-wrap: nowrap;
    width: 30.10vw;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.04vw;
    color: rgba(255, 255, 255, 0.52);
    margin-left: -26.04vw;
}
.palka{
    margin-top: 0.31vw;
    position: absolute;
    width: 70.10vw;
    border: 0.05vw solid rgba(255, 255, 255, 0.1);
} 

.img_chat{
    width: 0.42vw;
    height: 0.42vw;
}
.img_avatar{
    width: 4.69vw;
    height: 4.69vw;
    border-radius: 2.4vw;
}
.chats{
    display: flex;
    flex-direction: column;
    gap: 1.04vw;
}
.text_chat{
    margin-top: -0.52vw;
}
a{
    text-decoration: none;
}
.content{
    padding: 1.04vw;
    margin-left: 20.83vw;
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
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
        margin-left: -15vw;
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
    
    .custom-select{
        width: 57.50vw;
        background: #C68DFE;
        border-radius: 6.25vw;
        font-weight: 300;
        font-size: 3.75vw;
        line-height: 4.69vw;
        color: #FFFFFF;
        padding: 1.47vw 1.09vw;
    }
    .arrow-icon{
        width: 4.69vw;
        height: 2.19vw;
        margin-left: 51.56vw;
        margin-top: 3.13vw;
    }
    .option-list {
        width: 56.25vw;
        display: none;
        position: absolute;
        background-color: #F4F4F4;
        border: 0.05vw solid #ccc;
        border-radius: 1.30vw;
        z-index: 1000;
        font-size: 3.04vw;
        padding: 2.04vw;
    }
    .option-item{
        padding: 1.5vw;
    }
    
    .avatar_account{
        width: 13.13vw;
        height: 13.13vw;
    }
    
    .category_post{
        width: 25.63vw;
        height: 5.63vw;
        background: #C68DFE;
        border-radius: 9.38vw;
        font-weight: 300;
        font-size: 11px;
        line-height: 4.38vw;
        color: #FFFFFF;
    }
    .name_account{
        font-size: 3.75vw;
    }
    .post_account {
        gap: 3.57vw;
    }
    .post_img {
        width: 93.75vw;
        height: 51.56vw;
    }
    .description{
        width: 93.75vw;
        font-weight: 300;
        font-size: 3.44vw;
        line-height: 4.38vw;
        color: #FFFFFF;
    }
    .block4 {
        gap: 3.72vw;
    }

    .img_avatar{
        width: 13.13vw;
        height: 13.13vw;
    }
    .name_chat{
        margin-top: 14.04vw;
        width: 62.60vw;
        height: 5.63vw;
    }
    .name_chat_f{
        font-weight: 400;
        font-size: 3.44vw;
        line-height: 5vw;
        color: #FFFFFF;
        margin-left: 4.06vw;
    }
    .text_chat{
        width: 42.19vw;
        font-weight: 400;
        font-size: 3.13vw;
        line-height: 4.38vw;
        color: rgba(255, 255, 255, 0.62);
        margin-left: 4.06vw;
    }

    .palka {
        margin-top: 7.31vw;
        width: 90.10vw;
    }

    .img_chat {
        margin-top: 1.04vw;
        width: 1vw;
        height: 1vw;
        margin-left:25vw;
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
    margin-top: 2vw;
    margin-bottom: 1.5vw;
    padding-left: 2.86vw;
    padding-right: 3.91vw;
} 

.nothink {
    color: white;
    text-align: center;
    margin-top: 20px;
}
</style>