<template>
    <SideMenu></SideMenu>
    <div class="content" id="content">
        <div class="name_cart">
            <p>Мой профиль</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>

        <div class="form-container">

            <div class="image-preview" id="imagePreview">
                <button type="button" class="upload-btn" onclick="document.getElementById('imageUpload').click();">
                    <img class="pr_edit" v-if="fields.imagePreview" :src="fields.imagePreview"
                        alt="Предварительный просмотр" style="max-width: 200px; margin-top: 10px;">
                    <img class="pr_edit" v-else-if="user.user_info && user.user_info.avatar"
                        :src="linkApp + '/storage/' + user.user_info.avatar" alt="">
                    <img class="pr_edit" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                </button>
                <input type="file" id="imageUpload" name="imageUpload" accept="image/*" style="display:none;"
                    @change="previewImage">
            </div>
            <form @submit.prevent="submitForm">
                <input v-model="fields.nameUser" class="in_name_category" id="postText" name="postText"
                    placeholder="Имя" required></input>
                <input v-model="fields.surnameUser" class="in_name_category" id="postText" name="postText"
                    placeholder="Фамилия" required></input>

                <label for="postText">Цитата</label> <br>
                <textarea v-model="fields.quote" id="postText" name="postText" placeholder=""></textarea>
                <button class="but_post" type="submit">Сохранить</button>
            </form>
            <label for="postText">Логин</label>
            <form @submit.prevent="updateLogin">
                <div style="display: flex; align-items: center;"><input class="in_name_category" id="postText"
                        style="display: flex; align-items: center;" name="postText" v-model="inputLogin"
                        @input="checkAvailability" placeholder="Введите новый логин">
                    </input>
                    <div class="avaible" v-if="loginAvailability && inputLogin.length != 0">свободен</div>
                    <div class="avaible notavaible" v-if="!loginAvailability && inputLogin.length != 0">занят</div>
                    <button type="submit" class="button_succes">Подтвердить</button>
                </div>
            </form>
            <div v-if="loginError" style="color: red;" class="">{{ loginError }}</div>
            <div v-if="loginSucces" style="color: green;" class="">{{ loginSucces }}</div>
            <label for="postText">Старый пароль</label>
            <div style="display: flex; align-items: center;"><input @input="handleChangePassword"
                    class="in_name_category" id="postText" name="postText" v-model="passwordOld"
                    placeholder="Подтвердите свой пароль" required></input></div>
            <label for="postText">Новый пароль</label>
            <div style="display: flex; align-items: center;"><input @input="handleChangePassword"
                    class="in_name_category" id="postText" name="postText" v-model="passwordNew"
                    placeholder="Введите новый пароль" required>
                <button @click="updatePassword" class="button_succes">Сменить</button></input>
            </div>
            <div v-if="PasswordError" style="color: red;" class="">{{ PasswordError }}</div>
            <div v-if="PasswordSucces" style="color: green;" class="">{{ PasswordSucces }}</div>
            <label for="postText">Выбор категорий</label>
            <div 
            v-for="category in categories" 
            :key="category.id" 
            :class="['category', { selected: selectedCategories.includes(category.id)}]"
            @click="toggleCategory(category.id)"
        >
            {{ category.name }}
        </div>
        <button @click="updateCategories" class="button_succes">Сохранить категории</button>


            <button @click="deleteAccount" class="but_post" style="background-color: red;">Удалить аккаунт</button>

        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import SideMenu from '../components/sideMenu.vue';
import axios from 'axios';

export default {
    name: 'ProfileEdit',
    components: {
        SideMenu
    },
    data() {
        return {
            profileId: this.$route.params.id,
            fields: {
                nameUser: '',
                surnameUser: '',
                quote: '',
                imagePreview: null,
                imageFile: null,
            },

            user: [],

            inputLogin: '',
            linkApp: "",

            loginAvailability: null,
            loginError: '',
            loginSucces: '',
            isLoadingAvailability: false,

            passwordOld: '',
            passwordNew: '',
            PasswordError: '',
            PasswordSucces: '',


            categories: [],
            selectedCategories: []
        }
    },
    methods: {
        async checkAvailability() {
            this.loginError = '';
            this.loginSucces = '';
            this.isLoadingAvailability = true;
            await axios.post('/check/login_availability', { 'login': this.inputLogin })
                .then(response => {
                    this.loginAvailability = response.data;
                })
                .finally(() => {
                    this.isLoadingAvailability = false;
                })
                .catch(error => {
                    this.loginAvailability = null;
                })
        },
        async updateLogin() {
            if (this.loginAvailability && this.inputLogin.length > 0) {
                await axios.patch('/user/login/edit', { 'login': this.inputLogin })
                    .then(response => {
                        this.loginSucces = response.data.message;
                    })
                    .catch(e => {
                        this.loginError = e.response.data.message;
                    })
            }
            else if (!this.inputLogin) {
                this.loginError = 'Поле не может быть пустым';
            }
            else {
                this.loginError = 'Логин занят';
            }
        },
        async updatePassword() {
            if (this.passwordOld.length > 0 && this.passwordNew.length > 0) {
                await axios.patch('/user/password/edit', { 'password_old': this.passwordOld, 'password_new': this.passwordNew })
                    .then(response => {
                        this.PasswordSucces = response.data.message;
                    })
                    .catch(e => {
                        this.PasswordError = e.response.data.message;
                    })
            }
            else {
                this.PasswordError = 'Поля не могут быть пустыми';
            }
        },
        async getUser() {
            await axios.get(`/user/info/${this.profileId}`)
                .then(response => {
                    this.user = response.data;
                    this.fields.nameUser = response.data.user_info.name;
                    this.fields.surnameUser = response.data.user_info.surname;
                    this.fields.quote = response.data.user_info.quote;
                })
        },
        handleChangePassword() {
            this.PasswordError = '';
            this.PasswordSucces = '';
        },

        previewImage(event) {
            const file = event.target.files[0];
            this.fields.imageFile = file;


            const reader = new FileReader();
            reader.onload = (e) => {
                this.fields.imagePreview = e.target.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        },
        async submitForm() {
            const formData = new FormData();
            formData.append('name', this.fields.nameUser);
            formData.append('surname', this.fields.surnameUser);
            formData.append('quote', this.fields.quote);
            if (this.fields.imageFile) {

                formData.append('imageUpload', this.fields.imageFile);
            }
            await axios.post('/user/info/edit', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error.response.data);
                });
        },
        async deleteAccount() {
            let succes = window.confirm('Вы правда хотите удалить аккаунт и связанные с ним данные?');
            if (succes) {
                await axios.delete('/user/account/delete')
                    .then(response => {
                    })
                await axios.get("/logout").then((response) => {
                    this.$router.push("/auth");
                    this.$store.commit('authStore/logout');
                });
            }
        },
        getCategories() {
            axios.get('/get/categories').then(response => {
                this.categories = response.data;
            })
                .catch((error) => {
                    this.categories = error.response;
                })
        },
        getSelectedCategory()
        {
            axios.get('/user/category')
            .then(response=>{
                this.selectedCategories = response.data.map(category=>{
                    return category.id
                });
            })
        },
        toggleCategory(id) {

            const index = this.selectedCategories.indexOf(id);
            console.log(this.selectedCategories);
            if (index === -1) {
                this.selectedCategories.push(id);
            } else {
                this.selectedCategories.splice(index, 1);
            }
        },
        updateCategories()
        {
            axios.patch('/user/category/change', {'ids': this.selectedCategories})
            .then(response =>{
            })
        }
    },
    mounted() {
        this.getCategories();
        if (this.isAuthenticated) {
            this.getUser();
            this.getSelectedCategory();
        }

    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated']),
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
}
</script>
<style scoped>
.categories {
    display: flex;
    flex-direction: column;
}

.category {
    padding: 10px;
    margin: 5px;
    border: 1px solid #865DF8;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.category.selected {
    background-color: #865DF8;
    color: white;
}
body {
    margin: 0;
    font-family: "Unbounded", serif;
    transition: background-color 0.3s ease;
}

.avaible {
    padding: 0px 20px 0px 20px;
    margin-top: 15px;
    border-radius: 25px;
    background-color: green;
}

.notavaible {
    background-color: rgb(159, 28, 28);
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
    margin-top: 1.04vw;
    display: block;


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
    background: none;

    border: none;
    cursor: pointer;
    border-radius: 5.21vw;
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

.button_succes {
    margin-left: 40px;
    margin-top: 1vw;
    padding: 0px 10px 0px 10px;
    height: 2vw;
    background: #865DF8;
    border-radius: 1.56vw;
    font-size: 1.04vw;
    color: white;
    border: none;
    cursor: pointer;
    font-family: 'Unbounded';
}

.but_post:hover {
    background-color: #754ce6;
}

.in_name_category {
    margin-top: 1.56vw;
    width: 31.25vw;
    height: 1.56vw;
    background: #F3EFFE;
    border-radius: 1.56vw;
    margin-bottom: 0.78vw;
    padding: 0.52vw;
    font-size: 1.04vw;
    border: none;
}

.pr_edit {
    width: 8.33vw;
    height: 8.33vw;
    border: none;
    border-radius: 5.21vw;
}




.menu_mob {
    display: none;
}

@media (max-width:320px) {
    .menu_mob {
        display: block;
    }

    .content {
        margin-left: 0.05vw;
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

    .file-input {
        margin-bottom: 0.78vw;
    }

    .upload-btn {
        margin-left: 35vw;
        background: none;
        border: none;
        cursor: pointer;

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

    textarea {
        width: 92vw;
        height: 20vw;

        background: #F3EFFE;
        border-radius: 1.56vw;

        margin-bottom: 0.78vw;
        padding: 1.04vw;
        font-size: 3vw;
        border: none;
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

    label {
        font-size: 3vw;
    }

    .pr_edit {
        width: 20.31vw;
        height: 20.31vw;
        border: none;
        border-radius: 30vw;
    }

    .form-container {
        margin-top: 10vw;
        padding: 1.04vw;
        border-radius: 0.26vw;



    }



}
</style>