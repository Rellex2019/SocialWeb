<template>

    <SideMenu />
    <div class="content" id="content">
        <div class="name_cart">
            <p>Новый пост</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>

        <div class="form-container">

            <form @submit.prevent="createPost">
                <label for="postText">Заголовок</label>
                <input v-model="fields.title" class="texr-2" id="postText" name="postText" placeholder="Заголовок"
                    required />
                <label for="postText">Текст поста</label>
                <textarea v-model="fields.body" class="texr-post" id="postText" name="postText"
                    placeholder="Что нового?" required></textarea>

                <label for="category">Выбор категории</label>
                <select @change="selectCategory" id="categories" class="category">
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>

                <div class="image-preview" id="imagePreview">
                    <label for="">Добавить фото</label>
                </div>
                <input type="file" id="imageUpload" name="imageUpload" accept="image/*" style="border: 1px solid black;"
                    @change="previewImage">

                <img v-if="fields.imagePreview" :src="fields.imagePreview" alt="">
                <img v-else :src="linkApp + '/img/icons/add_foto.png'" alt="">
                <button class="but_post" type="submit">опубликовать</button>
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
        }
    },
    components: {
        SideMenu
    },
    methods: {
        previewImage(event) {
            const selectedPhotos = Array.from(event.target.files);
            this.fields.imageFile = selectedPhotos;;


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
            formData.append('title', this.fields.title);
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
        }
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    mounted() {
        this.getCategories();
    }
}
</script>
<style scoped>
body {
    font-family: 'Unbounded';
}

.form-container {

    padding: 1.04vw;
    border-radius: 0.26vw;

    max-width: 26.04vw;

}

h2 {
    text-align: center;
    margin-bottom: 1.04vw;
}

label {
    margin-top: 1.56vw;
    display: block;
    margin-bottom: 1vw;

    font-style: normal;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;
}

textarea {
    width: 38.44vw;
    height: 9.64vw;

    background: #F3EFFE;
    border-radius: 1.56vw;

    margin-bottom: 0.78vw;
    padding: 1.04vw;
    font-size: 1.04vw;
    border: none;
}

select {
    border: none;
    width: 21.25vw;
    height: 2.86vw;
    font-size: 1.04vw;
    line-height: 1.30vw;
    padding: 0.52vw;
    color: #000000;
    background: #F3EFFE;
    border-radius: 1.56vw;
}

option {
    font-size: 0.68vw;
    border-radius: 1.56vw;
}



.option-list {
    width: 22.40vw;
    display: none;
    position: absolute;
    background-color: #F4F4F4;
    border: 0.05vw solid #ccc;
    border-radius: 1.30vw;
    /* width: 100%; */
    z-index: 1000;
    font-size: 1.04vw;
    padding: 1.04vw;
}

.option-item {
    padding: 0.52vw;
    cursor: pointer;
    color: black;
}

.option-item:hover {
    background-color: #F4F4F4;
}

.file-input {
    margin-bottom: 0.78vw;
}

.upload-btn {
    border: none;
    cursor: pointer;
    border-radius: 1.56vw;
}

.but_post {
    width: 19.38vw;
    height: 2.86vw;


    background: #865DF8;
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
    background-color: #754ce6;
}


/* category */

.in_name_category {
    width: 48.70vw;
    height: 1.56vw;
    background: #F3EFFE;
    border-radius: 1.56vw;
    margin-bottom: 0.78vw;
    padding: 0.52vw;
    font-size: 1.04vw;
    border: none;
}

.category_delete {
    display: flex;
    flex-direction: column;
    gap: 1vw;
}

.categ {
    width: 65.94vw;
    display: flex;
    height: 3.18vw;
    background: #F2EDFE;
    border-radius: 1.56vw;
    font-size: 1.04vw;
    padding-left: 1.56vw;
}


.categ a {
    text-decoration: none;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #865DF8;
    margin-left: 54.69vw;
    margin-top: 0.78vw;
}

.menu_mob {
    display: none;

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

.sidebar a:hover {}






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

.texr-2 {
    font-size: 1vw;
    width: 20vw;
    height: 3vw;
    background-color: #F3EFFE;
    border-radius: 2vw;
    padding-left: 1vw;
}

@media ((min-width: 320px) and (max-width: 766px)) {
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
        margin-bottom: 8vw;

    }

    .name_cart {
        margin-left: 65vw;
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

    .content {
        margin-left: 0.05vw;
    }

    label {
        margin-top: 1.56vw;
        display: block;
        margin-bottom: 1vw;
        width: 40vw;
        font-style: normal;
        font-weight: 400;
        font-size: 3vw;
        line-height: 3vw;
        color: #865DF8;
    }

    .in_name_category {
        margin-top: 1vw;
        width: 92vw;
        height: 3vw;
        background: #F3EFFE;
        border-radius: 4vw;
        margin-bottom: 4vw;
        padding: 2vw;
        font-size: 3vw;
        border: none;
    }

    .upload-btn {
        background: none;
    }

    .rrr {
        width: 46.88vw;
        height: 25vw;
        margin-top: 4vw;
        margin-left: -2vw;
    }

    .but_post {
        width: 20.38vw;
        height: 5vw;

        background: #865DF8;
        border-radius: 1.56vw;
        font-size: 2vw;
        color: white;
        padding: 0.52vw;
        border: none;
        cursor: pointer;
        font-family: 'Unbounded';
        margin-top: 2.60vw;
    }




    .category_delete {
        display: flex;
        flex-direction: column;
        gap: 1.5vw;
        margin-top: 5vw;
    }

    .categ {
        width: 93.75vw;
        display: flex;
        height: 6vw;
        background: #F2EDFE;
        border-radius: 5vw;
        font-size: 2vw;
        padding-left: 1.56vw;
    }


    .categ a {
        text-decoration: none;
        font-size: 2vw;
        line-height: 1.30vw;
        color: #865DF8;
        margin-left: 72vw;
        margin-top: 2vw;

    }

    .texr-post {

        padding: 3vw;
        width: 85vw;
        height: 30vw;
        font-size: 3vw;
    }


    select {
        width: 90vw;
        height: 10vw;
        font-size: 3vw;
    }

    option {
        font-size: 3vw;
    }

    .upload-btn img {
        width: 60vw;
        height: 30vw;

    }

    .but_post {
        width: 40vw;
        font-size: 3vw;
        height: 10vw;
        border-radius: 30vw;
    }

    label {
        margin-top: 3vw;
        margin-bottom: 5vw;
    }
}
</style>