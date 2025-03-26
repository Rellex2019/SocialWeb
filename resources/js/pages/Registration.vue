<template>
    <div class="name_cart">
        <p>Регистрация</p>
    </div>
    <div class="bckgr">
        <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main" />
    </div>
    <div class="bckgr2">
        <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2" />
    </div>

    <form class="regs" @submit.prevent="sendDataForRegistration">
        <label for="" class="Auth">Добро пожаловать!</label>

        <div class="form-group">

            <span class="error-message" v-if="errors.name">{{ errors.name[0] }}</span>
            <input v-model="dataReg.name" :class="{ 'has-error': errors.name }" type="text" class="form" id="name"
                name="name" value="" placeholder="Имя" @input="checkErrors" />
        </div>


        <div class="form-group">
            <span class="error-message" v-if="errors.surname">{{ errors.surname[0] }}</span>
            <input v-model="dataReg.surname" :class="{ 'has-error': errors.surname }" type="text" class="form"
                id="surname" name="surname" value="" placeholder="Фамилия" @input="checkErrors" />
        </div>


        <div class="form-group">
            <span class="error-message" v-if="errors.email">{{ errors.email[0] }}</span>
            <input v-model="dataReg.email" :class="{ 'has-error': errors.email }" type="text" class="form" id="email"
                name="email" value="" placeholder="E-mail" @input="checkErrors" />
        </div>


        <div class="form-group">
            <span class="error-message" v-if="errors.login">{{ errors.login[0] }}</span>
            <span class="login-message" v-if="dataReg.login" :class="{ 'login-red': !loginAvailable }">{{ loginAvailable
                ?
                '✓' : '✕' }}</span>
            <input v-model="dataReg.login" :class="{ 'has-error': errors.login }" @input="checkAvailability" type="text"
                class="form" id="login" name="login" value="" placeholder="Логин" />
        </div>



        <div class="form-group">
            <span class="error-message" v-if="errors.password">{{ errors.password[0] }}</span>
            <input v-model="dataReg.password" :class="{ 'has-error': errors.password }" type="password" class="form"
                id="password" name="password" value="" @input="checkPassword" placeholder="Пароль" />
        </div>


        <div class="form-group">
            <span class="error-message" v-if="errors.password_repeat">{{ errors.password_repeat[0] }}</span>
            <input v-model="dataReg.password_repeat" :class="{ 'has-error': errors.password_repeat }" type="password"
                class="form" id="password_repeat" @input="checkPassword" name="password_repeat" value=""
                placeholder="Повторите пароль" />
        </div>



        <div class="form-group">
            <button class="btn-reg" type="submit" name="sendMe" value="1">
                Зарегистрироваться
            </button>
        </div>
        <label class="lab_lonf" for="">Продолжая, вы соглашаетесь с Условиями использования и Политикой
            конфиденциальности.
        </label>
        <label for="" class="silka_reg">Уже есть аккаунт?
            <RouterLink class="silka" to="/auth"><span style="color: #a185e9"> Войти</span>
            </RouterLink>
        </label>
    </form>
</template>
<script>
import axios from "axios";
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
.login-message {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2vw;
    height: 2vw;
    border-radius: 1vw;
    background-color: green;
    color: white;
    font-size: 1.2vw;
    position: absolute;
    /* Позиционирование ошибки */
    right: 105%;
    top: 25%;
    white-space: nowrap;
}

.login-red {
    background-color: red;
}

.form-group {
    position: relative;
    margin-bottom: 1rem;
}

.has-error {
    border: 1px solid red !important;
}

.error-message {
    color: red;
    font-size: 0.875rem;
    position: absolute;
    /* Позиционирование ошибки */
    left: 105%;
    top: 25%;
    white-space: nowrap;
    /* Отступ от поля */
}



body {
    margin: 0;
    font-family: "Unbounded", serif;
}

.form {
    padding-left: 0.78vw;

    width: 21.09vw;
    height: 3.07vw;
    left: 39.11vw;
    top: 18.33vw;
    background: #f0eeee;
    border: none;

    border-radius: 1.56vw;
    font-weight: 400;
    font-size: 1.04vw;
}

.regs {
    display: grid;

    place-items: center;
}

.Auth {
    margin-top: 3vw;
    margin-bottom: 2.6vw;
    font-weight: 400;
    font-size: 1.67vw;
    color: #865df8;
}

/* Для современных браузеров */
::placeholder {
    color: #d1c0ff;
}

.form-group {
    gap: 1vw;
    display: flex;
    flex-direction: column;
}

.btn-reg {
    margin-top: 1.5vw;
    margin-bottom: 1vw;
    font-style: normal;
    font-weight: 400;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
    width: 21.09vw;
    height: 3.07vw;
    background: #865df8;
    border-radius: 1.56vw;
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
    width: 21.77vw;
    margin-top: 0.52vw;
    font-weight: 400;
    font-size: 0.63vw;
    line-height: 0.78vw;
    text-align: center;
    color: #000000;
}

.silka_reg {
    margin-top: 0.52vw;
    font-weight: 400;
    font-size: 0.83vw;
    line-height: 1.04vw;
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
    margin-bottom: 2.6vw;
    margin-top: 2.08vw;
    display: flex;
    justify-content: center;
    width: 10.42vw;
    height: 2.08vw;
    background: #865df8;
    border-radius: 1.56vw;
    font-weight: 300;
    font-size: 1.04vw;
    line-height: 1.3vw;
    color: #ffffff;
    align-items: center;
}

.bckgr {
    position: relative;
    z-index: -30;
}

.backgr_main {
    /* position: absolute; */
    position: fixed;
    margin-left: 7.81vw;
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
    margin-left: 46.88vw;
    margin-top: 15.63vw;
    width: 51.72vw;
    height: 51.04vw;
}

@media ((min-width: 320px) and (max-width: 766px)) {
    .Auth {
        font-size: 4vw;
    }

    .name_cart {
        margin-left: 65vw;
        width: 30.25vw;
        height: 4.25vw;
        background: #865df8;
        border-radius: 5vw;
        font-weight: 300;
        font-size: 3vw;
        line-height: 1.3vw;
        color: #ffffff;
        align-items: center;
    }

    .form-group input {
        width: 93.75vw;
        height: 10vw;
        font-size: 3vw;
        border-radius: 30vw;
        padding-left: 4vw;
        margin-top: 1vw;
    }

    .form-group {
        margin-top: 10vw;
    }

    .btn-reg {
        width: 93.75vw;
        height: 10vw;
        font-size: 3vw;
        border-radius: 30vw;
        margin-top: -6vw;
    }

    .lab_lonf {
        font-size: 3vw;
        width: 95vw;
        line-height: 4vw;
    }

    .silka_reg {
        font-size: 3vw;
        margin-top: 5vw;
    }

    .auth {
        font-size: 3vw;
        margin-top: 2vw;
    }
}
</style>
