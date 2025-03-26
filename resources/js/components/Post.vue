<template>

    <!-- ДОДЕЛАТЬ -->

    <div class="content">
        <div class="container">


            <div class="block4" v-for="post in Posts" :key="post.id">
                <div class="block4_account">
                    <div class="account">
                        <img v-if="post.user && post.user.user_info && post.user.user_info.avatar"
                            :src="linkApp + '/storage/' + post.user.user_info.avatar" alt="" class="avatar_account">
                        <img v-else :src="linkApp + '/img/welcome_img/avatar_account.png'" alt=""
                            class="avatar_account">
                    </div>
                    <div class="post_account">
                        <p class="category_post">{{ post.category.name }}</p>
                        <p class="name_account">{{ post.user.user_info.name + ' ' + post.user.user_info.surname }}</p>
                    </div>
                </div>
                <div class="block4_posts">
                    <div class="post">
                        <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/storage/' + photo.path"
                            class="post_img">
                        <p class="description">{{ post.body }}</p>
                    </div>
                    <div class="time" style="margin-top: 0.25vw;">{{ formatDate(post.created_at) }}</div>
                    <div style="display: flex; gap: 1vw;">
                        <button class="redact" v-if="post.user.id == user.id" @click="goToEditPost(post)">Изменить
                            пост</button>
                        <button class="redact delete" v-if="post.user.id == user.id"
                            @click="deletePost(post.id)">Удалить
                            пост</button>
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
.time {
    max-width: 45.68vw;
    width: 100%;
    display: flex;
    justify-content: end;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.redact {
    background: #C68DFE;
    border-radius: 1.04vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    padding: 0.5vw 1vw;
    transition: 0.3s;
}

.redact:hover {
    background: #b162ff;
}

.delete {
    background: none;
    border: 1px solid #FFFFFF;
}

.delete:hover {
    background: red;
}

.content {
    margin-left: -1vw;

}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
    display: flex;
    flex-direction: column;
    gap: 2vw;
}

.block4 {
    display: flex;
    flex-direction: column;
    gap: 1.72vw;
}

.main_title {
    width: 11.46vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #FFFFFF;
    margin-bottom: 1.93vw;
}

.block4_account {
    display: flex;
    flex-direction: row;
    gap: 1.67vw;
    align-items: center;
}

.avatar_account {
    border-radius: 2.27vw;
    width: 4.58vw;
    height: 4.58vw;
}

.post_account {
    display: flex;
    flex-direction: column;
    gap: 0.57vw;
}

.category_post {
    display: flex;
    justify-content: center;
    padding: 0vw 1vw;
    height: 1.77vw;
    background: #C68DFE;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    align-items: center;
}

.name_account {
    font-weight: 500;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #C68DFE;
}

.post_img {
    max-width: 45.68vw;
    max-height: 30.10vw;
    border-radius: 0.52vw;
}

.description {
    width: 45.68vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    margin-top: 1.35vw;
    margin-bottom: 2vw;
}



/* friendsBar */




/* .content {
    margin-left: -1vw;

}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
}

.friends {
    position: absolute;
    width: 22.03vw;
    height: 36.93vw;
    border: 0.05vw solid rgba(198, 141, 254, 0.25);
    border-radius: 0.52vw;
    margin-top: -46.35vw;
    margin-left: 49.48vw;
    margin-top: 11.46vw;
}

.logo,
.friends {
    display: none;
}
.main_title_friends{
width: 7.86vw;
font-weight: 400;
font-size: 1.04vw;
line-height: 1.30vw;
color: #FFFFFF;
margin-left: 1.04vw;
margin-top: 1.04vw;
margin-bottom: 2.60vw;
}
.one_friend{
    margin-top: 0.83vw;
    margin-left: 1.04vw;
width: 19.22vw;
background: rgba(206, 157, 255, 0.2);
border-radius: 0.52vw;
display: flex;
flex-direction: row;
justify-content: space-between;
align-items: center;
padding-top: 0.78vw;
padding-bottom: 0.78vw;
padding-left: 0.42vw;
padding-right: 0.42vw;
}
.avatar_friend{
width: 2.71vw;
height: 2.71vw;
}
.text_friend{
    margin-left: -3.13vw;
}
.name_friend{
width: 9.11vw;
font-weight: 400;
font-size: 0.73vw;
line-height: 0.89vw;
color: #FFFFFF;
margin-bottom: 0.78vw;
}
.quote_friend{
font-weight: 400;
font-size: 0.63vw;
line-height: 0.78vw;
color: rgba(255, 255, 255, 0.65);
}
.avatar_icon{
width: 2.03vw;
height:1.93vw;
} */
</style>