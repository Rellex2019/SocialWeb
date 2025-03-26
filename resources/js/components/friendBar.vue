<template>
    <div class="friends-container" v-if="isAuthenticated">
      <div class="friends-header">
        <p class="friends-title">Друзья</p>
      </div>
      
      <div v-if="!friends" class="friends-list">
        <div  v-for="friend in friendsInfo" :key="friend.id" class="friend-item">
          <div class="friend-info" @click="$router.push(`/profile/${friend.id}`)">
            <img 
              v-if="friend.user_info && friend.user_info.avatar" 
              :src="linkApp + '/storage/' + friend.user_info.avatar" 
              class="friend-avatar" 
              alt="Аватар друга"
            />
            <img 
              v-else 
              :src="linkApp + '/img/img_acc.jpg'"  
              class="friend-avatar" 
              alt="Аватар по умолчанию"
            />
            
            <div class="friend-details">
              <p class="friend-name" v-if="friend.user_info">
                {{ friend.user_info.name + ' ' + friend.user_info.surname }}
              </p>
              <p class="friend-quote" v-if="friend.user_info && friend.user_info.quote">
                {{ friend.user_info.quote }}
              </p>
            </div>
          </div>
          
          <button 
            class="chat-button" 
            @click.prevent="openChat(friend.id, friend.chats?.id ?? null)"
            aria-label="Открыть чат"
          >
            <img 
              :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" 
              class="chat-icon" 
              alt="Иконка чата"
            />
          </button>
        </div>
      </div>


      <div v-else-if="friends" class="friends-list">
        <div  v-for="friend in friends" :key="friend.id" class="friend-item">
          <div class="friend-info" @click="$router.push(`/profile/${friend.id}`)">
            <img 
              v-if="friend.avatar" 
              :src="linkApp + '/storage/' + friend.avatar" 
              class="friend-avatar" 
              alt="Аватар друга"
            />
            <img 
              v-else 
              :src="linkApp + '/img/img_acc.jpg'"  
              class="friend-avatar" 
              alt="Аватар по умолчанию"
            />
            
            <div class="friend-details">
              <p class="friend-name" v-if="friend">
                {{ friend.name + ' ' + friend.surname }}
              </p>
              <p class="friend-quote" v-if="friend && friend.quote">
                {{ friend.quote }}
              </p>
            </div>
          </div>
          
          <button 
            class="chat-button" 
            @click.prevent="openChat(friend.id, friend.chat_id ?? null)"
            aria-label="Открыть чат"
          >
            <img 
              :src="linkApp + '/img/welcome_img/icon_link_page_1.png'" 
              class="chat-icon" 
              alt="Иконка чата"
            />
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex';
  import axios from 'axios';
  
  export default {
    name: 'FriendsList',
    data() {
      return {
        linkApp: '',
        friendsInfo: []
      }
    },
    props: {
      customTop: {
        type: String,
        required: false,
        default: ''
      },
      friends: {
        type: Array,
        required: false,
        default: null
      }
    },
    methods: {
      async getFriends() {
        try {
          const response = await axios.get('/friend');
          this.friendsInfo = response.data;
        } catch (error) {
          console.error('Ошибка при загрузке друзей:', error);
        }
      },
      async openChat(friendId, chatId = null) {
        console.log([friendId, chatId]);
        try {
          const response = await axios.post(`/chat/${friendId}/message/`, { chatId });
          this.$router.push(`/chat/${response.data.chatId}`);
        } catch (error) {
          console.error('Ошибка при открытии чата:', error);
        }
      }
    },
    computed: {
      ...mapGetters('authStore', ['isAuthenticated', 'user'])
    },
    created() {
      this.linkApp = import.meta.env.VITE_APP_URL;
      if (this.isAuthenticated && !this.friends) {
        this.getFriends();
      }
    },
    watch: {
      isAuthenticated(newVal) {
        if (newVal && !this.friends) {
          this.getFriends();
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .friends-container {
    position: fixed;
    width: 22.03vw;
    right: 5vw;
    top: 7.46vw;
    border: 0.05vw solid rgba(198, 141, 254, 0.25);
    border-radius: 0.52vw;
    background-color: rgba(24, 28, 34, 0.9);
    padding: 1vw;
    z-index: 100;
  }
  
  .friends-header {
    margin-bottom: 1.5vw;
  }
  
  .friends-title {
    font-weight: 400;
    font-size: 1.04vw;
    color: #FFFFFF;
    margin: 0;
  }
  
  .friends-list {
    max-height: 30vw;
    overflow-y: auto;
  }
  
  .friend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.78vw;
    margin-bottom: 0.83vw;
    background: rgba(206, 157, 255, 0.2);
    border-radius: 0.52vw;
  }
  
  .friend-info {
    display: flex;
    align-items: center;
    gap: 1vw;
    flex-grow: 1;
    cursor: pointer;
  }
  
  .friend-avatar {
    width: 2.71vw;
    height: 2.71vw;
    border-radius: 1.9vw;
    object-fit: cover;
  }
  
  .friend-details {
    flex-grow: 1;
  }
  
  .friend-name {
    font-weight: 400;
    font-size: 0.73vw;
    color: #FFFFFF;
    margin: 0 0 0.2vw 0;
  }
  
  .friend-quote {
    font-weight: 400;
    font-size: 0.63vw;
    color: rgba(255, 255, 255, 0.65);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 12vw;
  }
  
  .chat-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
  }
  
  .chat-icon {
    width: 2.03vw;
    height: 1.93vw;
    transition: opacity 0.2s;
  }
  
  .chat-icon:hover {
    opacity: 0.8;
  }
  
  /* Адаптивность для мобильных устройств */
  @media (max-width: 766px) {
    .friends-container {
      display: none;
    }
  }
  </style>