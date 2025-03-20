<template>
    <div class="content">
        <div class="container">
            <div class="block5">
                <div class="friends" :style="{marginTop: customTop}">
                    <p class="main_title_friends">Мои друзья</p>

                    <div v-for="friend in friendsInfo" class="one_friend">
                        <img :src="linkApp + '/img/icons/avatar.png'" alt="" class="avatar_friend" />
                        <div class="text_friend">
                            <p class="name_friend">{{ friend.user_info.name + ' ' + friend.user_info.surname }}</p>
                            <p class="quote_friend">цитата</p>
                        </div>
                        <a @click.prevent="openChat(friend.id, friend.chats.id ?? null)"><img
                                :src="linkApp + '/img/icons/chat.png'" alt="" class="avatar_icon" /></a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'friendBar',
    data()
    {
        return{
            friendsInfo: [],
            linkApp: "",
        }
    },
    props:{
        customTop:{
            required: false
        }
    },
    methods:{
        async getFriends() {
            await axios.get('/friend').then(response => {
                this.friendsInfo = response.data;
            })
                .catch((error) => {
                    this.friendsInfo = error.response;
                })
        },
        async openChat(friendId, chatId) {
            await axios.post(`/chat/${friendId}/message/`,{chatId})
            .then(response=>{
                this.$router.push(`/chat/${response.data.chatId}`)
            })
        },
    },
    mounted() {
        this.getFriends();
    },
}
</script>
<style scoped>
.content {
    margin-left: 0.05vw;
    padding: 1.04vw;
}

.container {
    display: flex;
}

.block5 {
    display: block;
}
.friends {
    position: fixed;
    width: 22.03vw;
    height: 33.85vw;
    background-color: #f2edfe;
    border-radius: 1.56vw;

    margin-left: 25vw;
    margin-top: 0.05vw;
}
.main_title_friends {
    width: 7.86vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #865df8;
    margin-left: 1.56vw;
    margin-top: 1.3vw;
    margin-bottom: 2.6vw;
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
.text_friend {
    margin-left: -3.13vw;
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
@media ((min-width: 320px) and (max-width: 766px)) {
    .block5 {
        display: none;
    }
}
</style>