<template>
    <div class="name_cart">
        <p>Авторизация</p>
    </div>
    <div class="bckgr">
        <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main" />
    </div>
    <div class="bckgr2">
        <img :src="linkApp + '/img/back2.png'" alt="" class="backgr_main2" />
    </div>
    <form class="regs" @submit.prevent="sendDataForAuth">
        <label for="" class="Auth at">Авторизация</label>

        
        <div class="form-group">

            <input v-model="dataLog.login" type="login" class="form" id="login" name="login" value=""
                placeholder="Логин" />

            <input v-model="dataLog.password" type="password" class="form" id="passsword" name="password" value=""
                placeholder="Пароль" />
        </div>






        <div class="form-group">
            <div class="error-message" v-if="error">
                Логин или пароль неверен
            </div>
            <button class="btn-reg" type="submit" name="sendMe" value="1">
                Войти
            </button>
        </div>

        <label for="" class="auth">У вас нет аккаунта?
            <RouterLink class="auth2" to="/registration">
                <span style="color: #a185e9"> Зарегистрироваться</span>
            </RouterLink>
        </label>
    </form>
</template>
<script>
import { RouterLink } from 'vue-router';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: "Auth",
    data() {
        return {
            linkApp: "",
            dataLog: {
                login: "",
                password: "",
            },
            error: null,
        };
    },
    methods: {
        sendDataForAuth() {
            axios.post("/login", this.dataLog)
            .then((response) => {
                this.$router.push('/');
                this.$store.commit('authStore/setUser', response.data);
            })
            .catch(e=>{
                this.error= e.response.data;
            })
            ;
        },

    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    created() {

        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
    mounted()
    {
        if (this.isAuthenticated) {
            this.$router.push('/')
        }
    }
};
</script>
<style scoped>
.error-message {
    width: 100%;
    display: flex;
    justify-content: center;
    color: red;
    font-size: 1vw;

    white-space: nowrap;

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
    margin-top: 13vw;
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
    display: flex;
    flex-direction: column;
    gap: 1vw;
    margin-bottom: 1vw;
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
