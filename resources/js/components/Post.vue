<template>
    <div v-for="post in Posts" :key="post.id" class="news">
        <div class="" v-if="post.user">
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
            <div class="new_content">
                <p>
                    {{ post.body }}
                </p>
                <img v-for="photo in post.photos" :key="photo.id" :src="linkApp + '/storage/' + photo.path">
                <div class="action_container">

                    <div class="like" @click="toggleLike(post.id)">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512; width: 40px;" xml:space="preserve">
                            <g>
                                <g>
                                    <path
                                        d="M466.706,66.173c-29.609-29.609-69.224-45.914-111.56-45.914c-36.448,0-70.876,12.088-98.643,34.342 c-28.166-22.254-62.637-34.342-98.729-34.342c-42.532,0-82.252,16.312-111.86,45.914C16.305,95.776,0,135.398,0,177.727 c0,42.335,16.305,81.951,45.914,111.553l197.065,197.065c3.591,3.598,8.306,5.396,13.021,5.396c4.703,0,9.405-1.793,13.003-5.372 l197.224-196.623C495.75,259.561,512,219.89,512,178.034C512,135.791,495.965,96.12,466.706,66.173z M440.056,263.821 L256.018,447.294L71.956,263.238c-22.647-22.653-35.122-53.023-35.122-85.511s12.475-62.858,35.122-85.511 c22.653-22.647,53.128-35.122,85.818-35.122c32.169,0,62.705,12.53,85.966,35.269c7.207,7.054,18.767,6.992,25.895-0.147 c22.653-22.647,53.017-35.122,85.511-35.122c32.494,0,62.858,12.475,85.358,34.974c22.352,22.868,34.661,53.398,34.661,85.966 C475.165,210.209,462.642,240.738,440.056,263.821z"
                                        fill="#000000" style="fill: rgb(134, 93, 248);"></path>
                                </g>
                            </g>
                        </svg>
                        {{ post.likes.length }}


                    </div>


                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: 'Post',
    emits: ['like'],
    data() {
        return {
            linkApp: '',
        }
    },
    props: {
        Posts: {
            required: true
        }
    },
    methods:{
        toggleLike(postId) {
            axios.post(`/post/${postId}/like`, {})
                .then(response => {
                    this.$emit('like');
                })
        },
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    components: {}
}
</script>
<style>
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
    width: 7.24vw;
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
    width: 7.24vw;
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
    margin-left: 20px;
    gap: 15px;
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