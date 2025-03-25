<template>
    <div v-for="post in Posts" :key="post.id" class="news">
        <div class="" v-if="post.user">
            <div style="display: flex; justify-content:space-between; font-size : 1.25vw;">
                <div @click="$router.push(`/profile/${post.user.id}`)" class="piple">
                    <img v-if="post.user && post.user.user_info && post.user.user_info.avatar"
                        :src="linkApp + '/storage/' + post.user.user_info.avatar" alt="" class="img_avatar">
                    <img v-else :src="linkApp + '/img/img_acc.jpg'" alt="" class="img_avatar">
                    <div class="name_piple">
                        <p class="name_profile">
                            {{ post.user.user_info.name + ' ' + post.user.user_info.surname }}
                        </p>
                        <p class="category_name">{{ post.category.name.toLowerCase() }}</p>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: space-between; align-items: end;">
                    <div class="time" style="margin-top: 0.25vw;">{{ formatDate(post.created_at) }}</div>

                    <button class="redact" v-if="post.user.id == user.id" @click="goToEditPost(post)">Изменить
                        пост</button>
                    <button class="redact" v-if="post.user.id == user.id" @click="deletePost(post.id)">Удалить
                        пост</button>
                </div>
            </div>

            <div class="new_content">
                <p>
                    {{ post.body }}
                </p>
                <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/storage/' + photo.path">
                <div style="display: flex; justify-content: space-between;" class="action_container">
                    <div class="" style="display: flex;">
                        <div class="like" @click="toggleLike(post.id)">
                            <svg width="40" height="34" viewBox="0 0 57 56" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M28.9997 5.49914C-3.00001 -12.5 -12.5 32.4998 28.9997 54.9991C70.5 30.5 60 -12 28.9997 5.49914Z"
                                    style="stroke-width: 2.5px;" stroke="#865DF8" />
                            </svg>

                            {{ post.likes.length }}


                        </div>
                        <button @click="openComments(post.id)" class="btn_comment"><img
                                :src="linkApp + '/img/icons/chat.png'" alt="" class="comment_icon" /></button>

                    </div>
                    <button v-if="isModalOpen == post.id" @click="closeComments" class="btn_comment"
                        style="font-size: 32px; color: #865DF8;">✕</button>
                </div>
                <div v-if="isModalOpen == post.id" class="container-comments">
                    <div class="nothing" v-if="comments.length < 1">Напишите комментарий первым</div>
                    <div class="container_comment">
                        <div v-for="comment in comments" :key="comment.comment.id" class="comment">
                            <div @click="$router.push(`/profile/${comment.comment.user_id}`)" class="user-comment">

                                <img class="img_avatar-comment" v-if="comment.user && comment.user.avatar"
                                    :src="linkApp + '/storage/' + comment.user.avatar" alt="">
                                <img class="img_avatar-comment" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">

                                {{ comment.user.name + ' ' + comment.user.surname }}
                            </div>

                            <div class="comment-body">{{ comment.comment.body }}</div>
                            <div>{{ formatDate(comment.comment.updated_at) }}</div>
                        </div>

                    </div>



                    <div class="container_messages">
                        <textarea class="input-comment" @input="handleScroll" v-model="comment"
                            placeholder="Введите сообщение"></textarea>
                        <a @click.prevent="addComment(post.id)"><img :src="linkApp + '/img/orpr.png'" alt=""
                                class="icon_enter"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';


export default {
    name: 'Post',
    emits: ['like', 'delete-post'],
    data() {
        return {
            linkApp: '',
            comments: [],
            isModalOpen: null,
            comment: '',
        }
    },
    props: {
        Posts: {
            required: true
        }
    },
    methods: {
        toggleLike(commentId) {
            axios.post(`/post/${commentId}/like`, {})
                .then(response => {
                    this.$emit('like');
                })
        },
        async openComments(commentId) {

            if (this.isModalOpen == commentId) {
                this.isModalOpen = null;
                this.comments = [];
            }
            else {
                await axios.get(`/post/${commentId}/comment`)
                    .then(response => {
                        this.comments = response.data;
                        this.isModalOpen = commentId;
                        console.log(this.comments);
                    })
            }
        },
        closeComments() {
            this.isModalOpen = null;
            this.comments = [];
        },
        addComment(commentId) {
            const formData = new FormData();
            formData.append('body', this.comment.replace(/\n/g, '<br>'));
            axios.post(`/post/${commentId}/comment`, formData)
                .then(response => {
                    this.comment = '';
                    this.comments.push({
                        'comment': response.data.comment,
                        'user': response.data.user
                    });
                    console.log(this.comments);
                })
        },
        handleScroll(event) {
            const textarea = event.target;
            textarea.style.height = 'auto';
            textarea.style.height = `${textarea.scrollHeight}px`;
        },
        formatDate(dateString) {
            const now = new Date();
            const postDate = new Date(dateString);
            const diffInMinutes = Math.floor((now - postDate) / (1000 * 60));

            if (diffInMinutes < 60) {
                return `${diffInMinutes} мин.назад`;
            } else if (diffInMinutes < 1440) {
                const diffInHours = Math.floor(diffInMinutes / 60);
                return `${diffInHours} час.назад`;
            } else {
                const diffInDays = Math.floor(diffInMinutes / 1440);
                return `${diffInDays} дн.назад`;
            }
        },
        goToEditPost(post) {
            this.$router.push({
                name: 'post.create',
                query: {
                    post: JSON.stringify(post)
                }
            });
        },
        deletePost(id) {
            let confirm = window.confirm('Вы уверены что хотите удалить пост?');
            if (confirm) {
                axios.delete(`/post/user/delete/${id}`, { 'id': id })
                    .then(response => {
                        this.$emit('delete-post');
                    })
            }
        },

    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    components: {}
}
</script>
<style scoped>
.redact {
    cursor: pointer;
    font-size: 0.8vw;
    text-decoration: none;
    color: #865DF8;
}

.container_comment {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 30px;
}

.img_avatar-comment {
    width: 3vw;
    height: 3vw;
    border-radius: 1.5vw;
}

.user-comment {
    cursor: pointer;
    gap: 15px;
    display: flex;
    align-items: center;
}

.comment {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 100%;
    padding: 0.25vw 1.35vw;
}

.comment-body {
    word-break: break-all;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}

.input-comment {
    width: 100%;
    background-color: #ffffff;
    border: 0.010vw solid #865DF8;
    border-radius: 1.04vw;
    height: 98px;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    padding: 1.25vw 1.35vw;
    overflow-y: hidden;
}

.icon_enter {
    cursor: pointer;
    right: 0;
    margin-top: auto;
}

.container-comments {
    position: relative;
    padding-top: 10px;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.56vw;
    border-top: 1px solid #865DF8;
    min-height: 10px;
    width: 100%;
    opacity: 0;
    /* Начальная прозрачность */
    transform: translateY(-20px);
    /* Начальное смещение сверху */
    animation: slideIn 0.5s ease-in-out forwards;
    /* Анимация */
}

@keyframes slideIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.container_messages {
    display: flex;
    align-items: center;
}

.btn_comment {
    cursor: pointer;
    margin-top: 15px;
    margin-left: 25px;
    margin-right: 5px;
}

.comment_icon {
    width: 40px;
}

.news {
    margin-top: 2.60vw;
    width: 39.32vw;
    margin-bottom: 2.60vw;
}

.img_avatar {
    width: 10.06vw;
    height: 10.06vw;
    border-radius: 1.56vw;
}

.piple {
    display: flex;
}

.img_avatar {
    width: 4.58vw;
    height: 4.58vw;
    border-radius: 2.2vw;
}

.name_piple {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-left: 1.04vw;
}

.name_profile {
    margin-top: 0.26vw;
    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 500;
    font-size: 1.25vw;
    color: #865DF8;
}

.category_name {
    display: flex;
    justify-content: center;
    margin-top: -0.63vw;
    height: 1.77vw;
    background: #865DF8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
}

.new_content p {

    font-family: 'Unbounded';
    font-style: normal;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.56vw;
}

.category_name {
    display: flex;
    justify-content: center;
    margin-top: -0.63vw;
    height: 1.77vw;
    background: #865DF8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
}

.like {
    margin-top: 15px;
    cursor: pointer;

    gap: 10px;
    display: flex;
    align-items: center;
    font-size: 28px;
}

@media (max-width:320px) {
    .news {
        width: 100vw;
        margin-bottom: 2.60vw;
    }

    .piple,
    .new_content p {
        margin-left: 5vw;
    }


    .name_piple {
        margin-left: 1.04vw;
    }

    .name_profile {
        margin-top: 0.26vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 500;
        font-size: 3vw;
        color: #865DF8;
    }

    .category_name {
        width: 15vw;
        margin-top: -0.63vw;
        padding: 1vw;
        border-radius: 3w;
        font-weight: 300;
        font-size: 2vw;

    }

    .new_content p {
        width: 90vw;
        font-family: 'Unbounded';
        font-style: normal;
        font-weight: 300;
        font-size: 2.5vw;
        line-height: 4vw;
    }

    .category_name {
        width: 15vw;
        margin-top: -0.63vw;
        padding: 1vw;
        border-radius: 3w;
        font-weight: 300;
        font-size: 2vw;

    }

    .img_avatar {
        width: 10.06vw;
        height: 10.06vw;
        border-radius: 2.2vw;
    }

}
</style>