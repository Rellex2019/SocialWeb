<template>
    <SideMenu></SideMenu>
    <div class="content" id="content">

        <div class="container">
            <div class="block1">
                <div @click="$router.go(-1)"><img class="back_arrow" :src="linkApp + '/img/profile_img/back_arrow.png'">
                </div>
                <RouterLink to="/"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></RouterLink>
            </div>
        </div>




        <div class="form-container">

            <div class="image-preview" id="imagePreview">
                <button type="button" class="upload-btn" onclick="document.getElementById('imageUpload').click();">
                    <img class="pr_edit" v-if="fields.imagePreview" :src="fields.imagePreview"
                        alt="Предварительный просмотр" style="max-width: 200px; margin-top: 10px;">
                    <img class="pr_edit" v-else-if="user.info && user.info.avatar"
                        :src="linkApp + '/storage/' + user.info.avatar" alt="">
                    <img class="pr_edit" v-else :src="linkApp + '/img/img_acc.jpg'" alt="">
                </button>
                <input type="file" id="imageUpload" name="imageUpload" accept="image/*" style="display:none;"
                    @change="previewImage">
            </div>
            <form @submit.prevent="submitForm">
                <input v-model="fields.nameUser" class="in_name_category" id="postText" name="postText"
                    placeholder="Имя" required style="margin-bottom: 1.5vw; margin-top: 2vw;"></input>
                <input v-model="fields.surnameUser" class="in_name_category" id="postText" name="postText"
                    placeholder="Фамилия" required></input>

                <label for="postText">Цитата</label> <br>
                <textarea v-model="fields.quote" id="postText" name="postText" placeholder=""></textarea>
                <button class="btn_save_cont" type="submit">Сохранить</button>
            </form>
            <label for="postText">Логин</label>
            <form @submit.prevent="updateLogin">
                <div>
                    <div style="display: flex; align-items: center;">
                        <input class="in_name_category" id="postText" name="postText" v-model="inputLogin"
                            @input="checkAvailability" placeholder="Введите новый логин" />
                        <div class="avaible" v-if="loginAvailability && inputLogin.length != 0"> Логин свободен</div>
                        <div class="avaible notavaible" v-if="!loginAvailability && inputLogin.length != 0">Логин занят
                        </div>
                    </div>


                    <button type="submit" class="btn_save_cont" style="margin-top: 1vw;">Подтвердить</button>
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

            </div>
            <button @click="updatePassword" class="btn_save_cont" style="margin-top: 1vw;">Сменить</button></input>
            <div v-if="PasswordError" style="color: red;" class="">{{ PasswordError }}</div>
            <div v-if="PasswordSucces" style="color: green;" class="">{{ PasswordSucces }}</div>



            <button @click="isModal = true"  class="btn_save_cont btn-category" style="margin-top: 3vw;">Выбрать категории</button>



            <button @click="deleteAccount" class="but_post" style="background-color: red;">Удалить аккаунт</button>

        </div>
    </div>



    <div class="setup-complete-container" v-if="isModal">
        <div class="window">
            <div>Выберите интересущиюе вас категории</div>
            <input type="searсh" v-model="searchQuery" placeholder="Введите название категории"
                @input="filterCategories" class="input_text">
            <div class="container_categories">
                <div class="category" :key="category.id" v-for="category in filteredCategories"
                    :class="[{ selected: selectedCategories.includes(category.id) }]"
                    @click="toggleCategory(category.id)">{{ category.name }}</div>
            </div>
            <div style="width: 100%; display: flex; justify-content: space-between; align-items: end;">
                <button class="btn_save" @click="isModal = false">Нет, спасибо</button>
                <button @click="updateCategories" class="btn_save2">Сохранить</button>
            </div>

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
            filteredCategories: [],
            selectedCategories: [],
            searchQuery: '',
            isModal: false,
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
                    this.fields.nameUser = response.data.info.name;
                    this.fields.surnameUser = response.data.info.surname;
                    this.fields.quote = response.data.info.quote;
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
                    if(response.data && response.data.avatarPath){
                        this.$store.commit('authStore/updateAvatar', response.data.avatarPath);

                    }
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
                this.filteredCategories = response.data;
            })
        },
        getSelectedCategory() {
            axios.get('/user/category')
                .then(response => {
                    this.selectedCategories = response.data.map(category => {
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
        updateCategories() {
            axios.patch('/user/category/change', { 'ids': this.selectedCategories })
                .then(response => {
                })
        },
        filterCategories() {
            const query = this.searchQuery.toLowerCase();
            this.filteredCategories = this.categories.filter(category =>
                category.name.toLowerCase().includes(query)
            );
        },
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
.selected {
    color: #F4F4F4;
    background-color: #865DF8;
}

.btn_save {
    cursor: pointer;
    margin-left: 0.5vw;
    padding: 1vw 2vw;
    color: #865df8;
}

.btn_save2 {
    cursor: pointer;
    margin-right: 0.5vw;
    border-radius: 0.7vw;
    padding: 1vw 2vw;
    color: #f2edfe;
    background-color: #865df8;
}

.input_text {
    font-size: 0.8vw;
    cursor: pointer;
    width: 30vw;
    padding: 0.4vw 1vw;
    border-radius: 2vw;
    border: 1px solid #865DF8;
}

.container_categories {

    flex-wrap: wrap;
    display: flex;
    gap: 2vw;
}

.category {
    font-size: 0.9vw;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5vw 1vw;
    border-radius: 2vw;
    border: 1px solid #865DF8;
}

.setup-complete-container {
    position: fixed;
    display: flex;
    top: 0;
    width: 100vw;
    height: 100vh;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.2);
}

.window {
    padding: 1.2vw 1vw;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 1vw;
    width: 40vw;
    background-color: #f2edfe;
    border-radius: 1.5vw;
}


.avaible {
    margin-left: 1vw;
    text-wrap: nowrap;
    color: green;
}

.notavaible {
    color: red;
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
    margin-top: 1.5vw;
    margin-bottom: 0.7vw;
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
    border-radius: 0.52vw;
    margin-bottom: 0.78vw;
    border: none;
    padding: 0.52vw;
    font-size: 1.04vw;
    border: none;
    border: 0.05vw solid #C68DFE;
    color: #F4F4F4;
    background: rgba(198, 141, 254, 0.26);
    border-radius: 0.52vw;

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
    border-radius: 5.21vw;
    background: none;
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

.in_name_category {
    flex: none;
    padding: 0.52vw;
    font-size: 1.04vw;
    border: none;
    border: 0.05vw solid #C68DFE;
    color: #F4F4F4;

    width: 26.15vw;
    background: rgba(198, 141, 254, 0.26);
    border-radius: 0.52vw;


}

.in_name_category::placeholder {
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #c4c4c4;
}




.pr_edit {
    width: 7.8vw;
    height: 7.8vw;
    border-radius: 3.9vw;
    border: none;
    /* border-radius: 100px; */
    background-color: #181C22;
}


.main_title {
    width: 8.75vw;
    height: 2.08vw;
    font-weight: 400;
    font-size: 1.67vw;
    line-height: 2.08vw;
    color: #C68DFE;
}

.block1 {
    margin-top: 2vw;
    margin-bottom: 1.5vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    /* position: absolute; */
    position: fixed;
    margin-left: 41.67vw;
    margin-top: 15.63vw;
    width: 51.72vw;
    height: 51.04vw;
}


.navbar,
.add_post {
    display: none;
}


.btn_save_cont {
    background-color: #C68DFE;
    border-radius: 1.30vw;
    color: #FFFFFF;

    font-size: 1.3vw;
    padding: 0.3vw 1vw;

}

.btn-category{
    background-color: #FFFFFF;
    color: #C68DFE;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
}

/* -----------------------------------media----------------------------- */
@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;

    }

    .avatar_mobile {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .block1 {
        display: flex;
        align-items: center;
    }

    .main_title {
        font-weight: 400;
        font-size: 4.69vw;
        line-height: 5.94vw;
        color: #C68DFE;
        width: 35vw;
    }

    .add_post {
        display: block;
        width: 10.63vw;
        height: 10.63vw;
    }

    .logo,
    .friends {
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

    .back_arrow {
        width: 4.20vw;
        height: 6.98vw;
    }

    .pr_edit {
        width: 28.44vw;
        height: 28.44vw;
    }

    .in_name_category {
        padding: 1.52vw;
        font-size: 3.04vw;
        border: none;
        border: 0.05vw solid #C68DFE;
        color: #F4F4F4;
        width: 87.50vw;
        background: rgba(198, 141, 254, 0.26);
        border-radius: 1.52vw;
    }

    .in_name_category::placeholder {

        font-size: 3.04vw;
    }

    textarea {
        width: 87.50vw;
        border-radius: 1.52vw;
        font-size: 3.04vw;

    }

    label {
        margin-top: 4.04vw;
        display: block;
        font-style: normal;
        font-weight: 400;
        font-size: 3.04vw;
        line-height: 1.30vw;
        color: #865DF8;
        margin-bottom: 1.56vw;
    }

    .but_post {
        width: 90.63vw;
        height: 9.38vw;
        background: #C68DFE;
        border-radius: 3.13vw;
        font-weight: 300;
        font-size: 3.44vw;
        line-height: 5.94vw;
        color: #FFFFFF;
        margin-top: 6.25vw;
    }

    .btn_save_cont {
        background-color: #C68DFE;
        border-radius: 1.30vw;
        color: #FFFFFF;
        width: 30px;
        font-size: 3.3vw;
        padding: 0.3vw 1vw;
        margin-bottom: 6vw;
    }



}








* {
    font-family: "Unbounded", serif;
    font-optical-sizing: auto;
    color: black;
    text-decoration: none;
    margin: 0;
    padding: 0;
}


body {
    overflow-x: hidden;
    background-color: #181C22;
}

.container {
    padding-left: 2.8vw;
    padding-right: 3.91vw;
}

/* -------------------------------------------------------------------------- */
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

.sidebar a {
    display: block;
    padding: 0.52vw 0;
    color: #ffffff;
    text-decoration: none;
}

.sidebar a:hover {
    /* background-color: #ddd; */
}

.content {
    margin-left: 20.83vw;
    padding: 1.04vw;
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

@media ((min-width: 320px) and (max-width: 766px)) {
    .sidebar {
        display: none;
    }

    .content {
        margin-left: -1vw;
    }
}
</style>