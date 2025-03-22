<template>



  
  <div class="content">
    <div class="container">
      <div class="block1">
        <img :src="linkApp + '/img/welcome_img/logo.png'" alt="" class="logo">
      </div>
    </div>
  </div>

  <div class="bckgr">
    <img :src="linkApp + '/img/reg_log_img/backgr.png'" alt="" class="backgr_main">
  </div>
  <form @submit.prevent="sendDataForAuth" class="regs">
    <label for="" class="Auth at">Авторизация</label>

    <div class="form-group">

      <input v-model="dataLog.login" type="text" class="form" id="login" name="login" value="" placeholder="Логин">
      <input v-model="dataLog.password" type="password" class="form" id="passsword" name="password" value="" placeholder="Пароль">

    </div>

    <div class="form-group">
      <button class="btn-reg" type="submit" name="sendMe" value="1">Войти</button>
    </div>

    <label for="" class=" silka_reg auth">У вас нет аккаунта? <RouterLink to="/registration" class="auth2">
        <span style="color: #A185E9;"> Зарегистрироваться</span></RouterLink></label>

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
    };
  },
  methods: {
    sendDataForAuth() {
      axios.post("/login", this.dataLog)
        .then((response) => {
          this.$router.push('/');
          this.$store.commit('authStore/setUser', response.data);
        });
    },

  },
  computed: {
    ...mapGetters('authStore', ['isAuthenticated', 'user']),
  },
  created() {

    this.linkApp = `${import.meta.env.VITE_APP_URL}`;
  },
  mounted() {
    if (this.isAuthenticated) {
      this.$router.push('/')
    }
  }
};
</script>
<style scoped>




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
  margin-top: 2.08vw;
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
}

.logo {
  width: 8.49vw;
  height: 1.56vw;
  padding-right: 5.21vw;
}


.regs {
  display: grid;
  place-items: center;
}

.Auth {
  margin-top: 8vw;
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
