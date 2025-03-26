<template>
    <SideMenu />

    <div class="content">
        <div class="container">
            <div class="block1">
                <div @click="$router.go(-1)"><img class="back_arrow" :src="linkApp + '/img/profile_img/back_arrow.png'">
                </div>
                <RouterLink to="/"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></RouterLink>
            </div>
        </div>
    </div>

    <div class="content" id="content">
        <div class="form-container">
            <form @submit.prevent="handleSubmit">
                <label for="postText">Напишите текст</label>
                <textarea v-model="fields.body" id="postText" name="postText" placeholder="Что нового?"
                    required></textarea>

                <label for="category">Выбор категории</label>
                <div class="block3">
                    <div class="sort">
                        <select @change="selectCategory" id="categories" class="custom-select">
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="image-preview" id="imagePreview">
                    <label>Добавить фото</label>
                </div>
                <input type="file" id="imageUpload" name="imageUpload" ref="imageUpload" accept="image/*"
                    style="display:none;" @change="previewImage">

                <button type="button" class="upload-btn" @click="openFileInput">
                    <img v-if="fields.imagePreview" :src="fields.imagePreview" alt="">
                    <img v-else-if="localPost.photos && localPost.photos.length > 0"
                        :src="linkApp + '/storage/' + localPost.photos[0].path" alt="">
                    <img v-else :src="linkApp + '/img/welcome_img/add_photo.png'" alt="">
                </button>

                <button class="but_post" type="submit" v-if="Object.keys(localPost).length > 0">изменить</button>
                <button class="but_post" type="submit" v-else>опубликовать</button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import SideMenu from '../components/sideMenu.vue';

export default {
    name: 'CreatePost',
    data() {
        return {
            linkApp: '',
            fields: {
                title: '',
                body: '',
                selectedCategory: 1,
                imagePreview: null,
                imageFile: null,
            },
            categories: [],
            localPost: {},
        }
    },
    props: {
        post: {
            required: false
        }
    },
    watch: {
        post: {
            handler(newId, oldId) {
                this.initialField();
            },
        }
    },
    components: {
        SideMenu
    },
    methods: {
        handleSubmit() {
            if (this.isEditMode) {
                this.changePost(this.localPost.id);
            } else {
                this.createPost();
            }
        },
        openFileInput() {
            this.$refs.imageUpload.click();
        },
        previewImage(event) {
            const selectedPhotos = Array.from(event.target.files);
            this.fields.imageFile = selectedPhotos;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.fields.imagePreview = e.target.result;
            };

            if (selectedPhotos[0]) {
                reader.readAsDataURL(selectedPhotos[0]);
            }
        },
        createPost() {
            const formData = new FormData();
            formData.append('title', 'название');
            formData.append('body', this.fields.body);
            formData.append('category_id', this.fields.selectedCategory);
            if (this.fields.imageFile) {
                this.fields.imageFile.forEach(file => {
                    formData.append('photos[]', file);
                });
            }
            axios.post('/post/create', formData, {
                headers: {
                    "Content-Type": 'multipart/form-data'
                }
            })
                .then(response => {
                    this.$router.push(`/profile/${this.user.id}`)
                })
        },
        getCategories() {
            axios.get('/get/categories').then(response => {
                this.categories = response.data;
            })
                .catch((error) => {
                    this.categories = error.response;
                })
        },
        selectCategory(event) {
            this.fields.selectedCategory = event.target.value;
        },
        initialField() {
            this.localPost = {};
            this.fields = [];
            if (this.post) {
                this.localPost = (JSON.parse(this.post));
                this.fields = { ...this.localPost };
                this.fields = { ...this.fields, selectedCategory: this.localPost.category.id }
                console.log(this.localPost);
            }
        },
        changePost(id) {
            const formData = new FormData();
            formData.append('title', 'название');
            formData.append('body', this.fields.body);
            formData.append('category_id', this.fields.selectedCategory);
            if (this.fields.imageFile) {
                this.fields.imageFile.forEach(file => {
                    formData.append('photos[]', file);
                });
            }
            axios.post(`/post/user/change/${id}`, formData, {
                headers: {
                    "Content-Type": 'multipart/form-data'
                }
            })
                .then(response => {
                    this.$router.push(`/profile/${this.user.id}`)
                })
        },
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
        isEditMode() {
            return this.localPost?.id; // Проверяем наличие id
        }
    },
    mounted() {
        this.initialField();
        this.getCategories();
    }
}
</script>

<style scoped>
.content {
    margin-left: 20.83vw;
    padding: 1.04vw;
}

.container {
    padding-left: 2.86vw;
    padding-right: 3.91vw;
}

.block1 {
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
}

.form-container {
    padding: 1.04vw;
    border-radius: 0.26vw;
    max-width: 26.04vw;
}

label {
    margin-top: 1.56vw;
    display: block;
    margin-bottom: 1vw;
    width: 31.25vw;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #C68DFE;
}

textarea {
    margin-bottom: 0.78vw;
    padding: 1.04vw;
    border: none;
    width: 64.27vw;
    height: 8.54vw;
    background: rgba(198, 141, 254, 0.26);
    border-radius: 0.52vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

input[type="text"] {
    margin-bottom: 0.78vw;
    padding: 1.04vw;
    border: none;
    width: 64.27vw;
    background: rgba(198, 141, 254, 0.26);
    border-radius: 0.52vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.custom-select {
    cursor: pointer;
    display: block;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
    padding: 0.47vw 1.09vw;
    width: 39.22vw;
    background: #45395B;
    border-radius: 0.52vw;
    border: none;
}

.arrow-icon {
    position: absolute;
    margin-top: 0.7vw;
    margin-left: 38vw;
    width: 1.93vw;
    height: 0.89vw;
}

.file-input {
    margin-bottom: 0.78vw;
}

.upload-btn {
    border: none;
    cursor: pointer;
    background-color: #181C22;
    padding: 0;
}

.upload-btn img {
    max-width: 100%;
    height: auto;
    border-radius: 0.52vw;
}

.but_post {
    width: 19.38vw;
    background: #C68DFE;
    border-radius: 1.56vw;
    font-size: 1.04vw;
    color: white;
    padding: 0.52vw;
    border: none;
    cursor: pointer;
    font-family: 'Unbounded';
    margin-top: 2.60vw;
}

.but_post:hover {
    background-color: #C68DFE;
}

/* Mobile styles */
@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;
    }

    .block1 {
        display: flex;
        align-items: center;
    }

    .logo,
    .friends {
        display: none;
    }

    .back_arrow {
        width: 4.20vw;
        height: 6.98vw;
    }

    .form-container {
        max-width: 100vw;
    }

    textarea {
        width: 92.19vw;
        height: 31.25vw;
        padding: 3.04vw;
        font-size: 4.04vw;
        border-radius: 3.13vw;
        line-height: 5.30vw;
    }

    input[type="text"] {
        width: 92.19vw;
        padding: 3.04vw;
        font-size: 4.04vw;
        border-radius: 3.13vw;
        line-height: 5.30vw;
    }

    label {
        margin-top: 6vw;
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
        width: 51.25vw;
    }

    .custom-select {
        font-size: 4.04vw;
        padding: 2.47vw 4.09vw;
        width: 59.22vw;
        border-radius: 0.52vw;
    }

    .arrow-icon {
        margin-top: 1.7vw;
        margin-left: 60vw;
        width: 5.93vw;
        height: 2.89vw;
    }

    .but_post {
        width: 49.38vw;
        font-size: 4.04vw;
        padding: 1.52vw;
        margin-top: 2.60vw;
    }

    .upload-btn img {
        width: 315px;
    }
}

/* Sidebar styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 20.83vw;
    height: 100%;
    background-color: #181C22;
    padding: 1.04vw;
    box-shadow: 0.10vw 0 0.26vw rgba(0, 0, 0, 0.1);
}

.avatar_block {
    width: 17.50vw;
    height: 16.46vw;
    background: #22232F;
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
</style>