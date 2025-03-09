<template>
    <div class="outer-padding">
        <div class="container-main-block">
            <div class="main-block">
                sadasdasdasdaddsad
            </div>
            <button class="btn" @click="sendDataForRegistration">Registration</button>
            <button class="btn" @click="sendDataForAuth">Authorization</button>
            <button class="btn" @click="checkAuth">CheckAuth</button>
            <button class="btn" @click="logOut">LogOut</button>
        </div>
    </div>

</template>
<script>
import axios from 'axios';

export default {
    name: 'Main',
    data() {
        return {
            dataReg:
            {
                "name": "Кирилл",
                "surname": "Зятчин",
                "login": "thekirz",
                "email": "kirill@gmail.com",
                "password": "123123",
                "password_repeat": "123123"
            },
            dataLog:
            {
                "login": "thekirz",
                "password": "123123",
            }
        }
    },
    methods: {
        sendDataForRegistration() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                return axios.post('/signup', this.dataReg);
            }).then(response => {
                console.log('Все кул', response.data);
            }).catch(error => {
                console.error('Ошибочка:', error.response.data);
            })

        },
        sendDataForAuth() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                return axios.post('/login', this.dataLog);
            }).then(response => {
                console.log('Все кул', response.data);
            }).catch(error => {
                console.error('Ошибочка:', error.response.data);
            })

        },
        logOut()
        {
            axios.get('/logout').then(response =>{
                console.log(response.data);
            });
        },
        checkAuth() {
            axios.get('/check_auth').then(response=>{
                console.log(response.data);
            });
        }
    }
}
</script>
<style>
.btn {
    border: 1px solid red;
}
</style>