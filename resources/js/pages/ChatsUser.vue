<template>

    <SideMenu />
    <div class="content" id="content">


        <div class="name_cart">
            <p>Мои чаты</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>
        <div class="bckgr">
            <img :src="linkApp + '/img/back.png'" alt="" class="backgr_main">
        </div>
        <div class="bckgr2">
            <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2">
        </div>
        <div class="wrapper">


            <div v-for="chat in chats.chats" :key="chat.id" class="chat">
                <div class="name_chat">
                    <img class="img_avatar" :src="linkApp + '/img/img_acc.jpg'" alt="">
                    <div class="chats">
                        <p class="name_chat_f">{{ chat.chat.name }}</p>
                        <p style="margin-top: 15px;" class="text_chat" v-if="chat.last_message">{{
                            chat.last_message.content }}</p>
                        <p style="margin-top: 15px;" class="text_chat" v-else="chat.last_message">Этот чатик пустой</p>
                    </div> <img class="img_chat" @click="openChat(chat.chat.id)" :src="linkApp + '/img/icons/chat.png'" alt="">
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
                    console.log(response);
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
.name_chat {
    display: flex;
    margin-bottom: 0.78vw;
    width: 56.35vw;
    height: 7.19vw;
    background: #F6F3FF;
    border-radius: 1.56vw;
}

.name_chat_f {
    font-size: 1.25vw;
    line-height: 1.56vw;
    color: #865DF8;
}

.text_chat {

    width: 15.10vw;
    height: 0.78vw;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.04vw;
    color: rgba(134, 93, 248, 0.47);
}

.img_chat {
    cursor: pointer;
    margin-left: 28.65vw;
    margin-top: 1.56vw;
    width: 3.44vw;
    height: 3.44vw;
}

.img_avatar {

    width: 5.36vw;
    height: 5.36vw;
    margin-top: 0.78vw;
    margin-left: 1.04vw;
}

.chats {
    margin-left: 1.04vw;
    margin-top: 0.52vw;
}

.text_chat {
    margin-top: -0.52vw;
}

a {
    text-decoration: none;
}





.menu_mob {
    display: none;
}

@media (max-width:320px) {
    .content {
        margin-left: 0.05vw;
    }

    .menu_mob {
        display: block;
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


    }

    .name_cart {
        margin-left: 67vw;
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


    .name_chat {

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

    .text_chat {

        width: 72vw;
        height: 0.78vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 400;
        font-size: 2vw;
        line-height: 1.04vw;
        color: rgba(134, 93, 248, 0.47);
    }

    .img_chat {
        margin-left: -5vw;
        margin-top: 1.56vw;
        width: 7vw;
        height: 7vw;
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

    a {
        text-decoration: none;
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