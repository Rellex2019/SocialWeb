<template>


    <div class="content">
        <div class="container">
            <div class="block1">
                <a href="./welcome.html"><img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo"></a>
            </div>
        </div>
    </div>

    <div class="bckgr">
        <img :src="linkApp + '/img/reg_log_img/backgr.png'" alt="" class="backgr_main">
    </div>

    <form @submit.prevent="sendDataForRegistration" class="regs">
        <label for="" class="Auth">Регистрация</label>

        <div class="form-group">

            <span class="error-message" v-if="errors.name">{{ errors.name[0] }}</span>
            <input v-model="dataReg.name" :class="{ 'has-error': errors.name }" type="text" class="form" id="name"
                name="name" value="" placeholder="Имя" @input="checkErrors" />

            <span class="error-message" v-if="errors.surname">{{ errors.surname[0] }}</span>
            <input v-model="dataReg.surname" :class="{ 'has-error': errors.surname }" type="text" class="form"
                id="surname" name="surname" value="" placeholder="Фамилия" @input="checkErrors" />

            <span class="error-message" v-if="errors.email">{{ errors.email[0] }}</span>
            <input v-model="dataReg.email" :class="{ 'has-error': errors.email }" type="text" class="form" id="email"
                name="email" value="" placeholder="E-mail" @input="checkErrors" />

            <span class="error-message" v-if="errors.login">{{ errors.login[0] }}</span>
            <span class="login-message" v-if="dataReg.login" :class="{ 'login-red': !loginAvailable }">{{ loginAvailable
                ?
                'Логин свободен' : 'Логин занят' }}</span>
            <input v-model="dataReg.login" :class="{ 'has-error': errors.login }" @input="checkAvailability" type="text"
                class="form" id="login" name="login" value="" placeholder="Логин" />

            <span class="error-message" v-if="errors.password">{{ errors.password[0] }}</span>
            <input v-model="dataReg.password" :class="{ 'has-error': errors.password }" type="password" class="form"
                id="password" name="password" value="" @input="checkPassword" placeholder="Пароль" />


            <span class="error-message" v-if="errors.password_repeat">{{ errors.password_repeat[0] }}</span>
            <input v-model="dataReg.password_repeat" :class="{ 'has-error': errors.password_repeat }" type="password"
                class="form" id="password_repeat" @input="checkPassword" name="password_repeat" value=""
                placeholder="Повторите пароль" />
        </div>


        <div class="form-group">
            <button class="btn-reg" type="submit" name="sendMe">Зарегистрироваться</button>
        </div>
        <label class="lab_lonf" for="">**Продолжая, вы соглашаетесь с Условиями использования и Политикой
            конфиденциальности. </label>
        <label for="" class="silka_reg">Уже есть аккаунт?
            <RouterLink to='/auth' class="silka"><span style="color: #A185E9;"> Войти</span></RouterLink>
        </label>

    </form>
</template>
<script>
import axios from "axios";
import { RouterLink } from "vue-router";
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: "Registration",
    data() {
        return {
            linkApp: "",
            dataReg: {
                name: "",
                surname: "",
                login: "",
                email: "",
                password: "",
                password_repeat: "",
            },
            isLoadingAvailability: false,
            loginAvailable: '',


            errors: [],
        };
    },
    methods: {
        sendDataForRegistration() {
            // Сбрасываем ошибки перед проверкой
            this.errors = {};

            // Выполняем все проверки
            this.checkPassword();
            this.checkAvailability();

            // Проверяем обязательные поля
            if (!this.dataReg.name.trim()) this.errors.name = ['Поле обязательно для заполнения'];
            if (!this.dataReg.surname.trim()) this.errors.surname = ['Поле обязательно для заполнения'];
            if (!this.dataReg.email.trim()) this.errors.email = ['Поле обязательно для заполнения'];
            if (!this.dataReg.login.trim()) this.errors.login = ['Поле обязательно для заполнения'];

            // Если ошибок нет - отправляем форму
            if (Object.keys(this.errors).length === 0) {
                axios.post("/signup", this.dataReg)
                    .then((response) => {
                        this.$router.push('/');
                        this.$store.commit('authStore/setUser', response.data);
                    })
                    .catch(e => {
                        if (e.response && e.response.data.errors) {
                            this.errors = {
                                ...this.errors,
                                ...e.response.data.errors
                            };
                        }
                    });
            } else {
                console.log('Ошибки валидации:', this.errors);
            }
        },

        async checkAvailability() {
            this.isLoadingAvailability = true;
            const loginRegex = /^[a-zA-Z0-9]+$/;
            let loginValidate = loginRegex.test(this.dataReg.login);

            // Сбрасываем ошибку логина перед проверкой
            if (this.errors.login) delete this.errors.login;

            if (loginValidate && this.dataReg.login.trim()) {
                try {
                    const response = await axios.post("/check/login_availability", {
                        login: this.dataReg.login,
                    });
                    this.loginAvailable = response.data;
                } finally {
                    this.isLoadingAvailability = false;
                }
            } else if (this.dataReg.login.trim()) {
                this.loginAvailable = false;
                this.errors.login = ['Логин должен состоять из латинских букв и цифр'];
            }
        },

        checkPassword() {
            // Сбрасываем ошибки паролей перед проверкой
            if (this.errors.password) delete this.errors.password;
            if (this.errors.password_repeat) delete this.errors.password_repeat;

            if (!this.dataReg.password.trim()) {
                this.errors.password = ['Введите пароль'];
            } else if (this.dataReg.password.length < 8) {
                this.errors.password = ['Пароль должен быть не менее 8 символов'];
            }

            if (!this.dataReg.password_repeat.trim()) {
                this.errors.password_repeat = ['Повторите пароль'];
            } else if (this.dataReg.password !== this.dataReg.password_repeat) {
                this.errors.password_repeat = ['Пароли не совпадают'];
            }
        },

        checkErrors(e) {
            const field = e.target.name;
            // Удаляем ошибку только если поле не пустое
            if (this.dataReg[field].trim() && this.errors[field]) {
                delete this.errors[field];
            }
        }
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted() {
        if (this.isAuthenticated) {
            this.$router.push('/')
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
};
</script>
<style scoped>
.error-message{
    color: red;
}
.login-message{
    color: green;
}
.login-red{
    color: red;
}
.has-error{
    border: 1px solid red !important;
}
.form {
    padding-left: 0.78vw;
    border: none;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    width: 24.06vw;
    height: 2.86vw;

    background: #F2EDFE;
    border-radius: 0.52vw;


}

.block1 {
    width: 100vw;
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
}

.logo {
    height: 1.56vw;
    padding-right: 5.21vw;
}


.regs {
    display: grid;
    place-items: center;
}

.Auth {
    margin-top: 4vw;
    margin-bottom: 2.60vw;
    font-weight: 400;
    font-size: 1.67vw;
    color: #865DF8;
}

/* Для современных браузеров */
::placeholder {
    color: rgba(0, 0, 0, 0.56);
}

.form-group {
    gap: 1vw;
    display: flex;
    flex-direction: column;
}

.btn-reg {
    margin-top: 1.5vw;
    margin-bottom: 1vw;
    width: 24.06vw;
    height: 2.86vw;
    background: #C68DFE;
    border-radius: 0.52vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.30vw;
    color: #FFFFFF;
}

.btn-reg {
    display: inline-block;
    padding: 0.78vw 2.08vw;
    border: none;
    cursor: pointer;
}

.form {
    display: inline-block;
    cursor: pointer;
}

.lab_lonf {
    margin-top: 0.52vw;
    width: 19.11vw;
    font-weight: 300;
    font-size: 0.73vw;
    line-height: 0.83vw;
    color: #FFFFFF;
    text-align: center;
}

.silka_reg {
    margin-top: 1.82vw;
    width: 19.06vw;
    height: 1.04vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.56vw;
    text-align: center;
    color: #FFFFFF;
}

.reg_fon {
    margin-top: -5.21vw;
    margin-left: -5.21vw;
    position: absolute;
    z-index: -10;
}


.reg_fon-mob {
    display: none;
}

.name_cart {
    margin-left: 80.73vw;
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

.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    position: fixed;
    width: 93.07vw;
    height: 43.96vw;
}

.navbar {
    display: none;
}

@media ((min-width: 320px) and (max-width: 766px)) {

    /* -------------------------------------------------------------------- */
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

    /* ---------------------------------------------------------------- */
    .logo1 {
        display: none;
    }

    .logo {
        width: 23.49vw;
        height: 4.56vw;
        padding-right: 5.21vw;
    }

    .Auth {
        font-size: 6.55vw;
        margin-bottom: 10vw;
        color: #C68DFE;
    }

    .form {
        width: 89.33vw;
        height: 12.80vw;
        padding-left: 4vw;

        background: #F0EEEE;
        border: 0.05vw solid #A185E9;
        border-radius: 6vw;
        font-weight: 400;
        font-size: 4.18vw;
    }


    .btn-reg {
        width: 89.33vw;
        height: 12.80vw;
        font-size: 4.18vw;
        margin-top: 5vw;
        border-radius: 6vw;
    }

    .form-group {
        gap: 3vw;

    }

    .lab_lonf {
        margin-top: 5vw;
        font-size: 2.99vw;
        width: 93.33vw;
        line-height: 3.20vw;
    }

    .reg_fon {
        display: none;
    }

    .reg_fon-mob {
        margin-left: -20vw;
        z-index: -10;
        display: block;
        position: absolute;
        transform: rotate(-45deg);
        margin-top: 20vw;
    }

    .silka_reg {
        font-size: 4.18vw;
        margin-top: 6vw;
        width: 69vw;
        line-height: 5vw;
    }

    .auth {
        text-align: center;
        margin-top: 15vw;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth2 {
        text-align: center;
    }

    .at {
        margin-top: 40vw;
    }

    form {
        margin-bottom: 30vw;
    }
}
</style>
