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
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>

        <div class="form-container">
            <div class="container_create">
                <p class="title">Добавить категорию</p>
                <div style="display: flex;">
                    <input class="input_text" v-model="category" type="text" placeholder="Введите название новой категории">
                    <button class="but_post" @click="addCategory" type="submit">Создать</button>
                </div>

            </div>


            <p class="title">Все категории</p>
            <div class="container_categories">
                <div class="category" @click="deleteCategory(category)" :key="category.id"
                    v-for="category in categories"><div style="font-size: 1.2vw;">{{ category.name }}</div><div style="font-size: 1.3vw;">✕</div></div>
            </div>



        </div>
    </div>
</template>
<script>
import SideMenu from '../components/sideMenu.vue';

export default {
    name: 'AddCategory',
    data() {
        return {
            linkApp: '',
            categories: [],
            category: '',
        }
    },
    methods: {
        getCategories() {
            axios.get('/get/categories').then(response => {
                this.categories = response.data;
            })
        },
        addCategory() {
            axios.post('/category/create', { 'name': this.category })
                .then(response => {
                    this.categories.push(response.data.category);
                    this.category = '';
                })
        },
        deleteCategory(category) {
            let confirm = window.confirm(`Вы уверены, что хотите удалить категорию "${category.name}"?`);
            if (confirm) {
                axios.delete(`/category/${category.id}/delete`)
                    .then(response => {
                        this.categories = this.categories.filter(categoryDB => { return categoryDB.id != category.id });
                    })
            }

        }
    },
    components: {
        SideMenu
    },
    mounted() {
        this.getCategories();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}`;
    },
}
</script>
<style scoped>
.logo {
    width: 8.49vw;
    height: 1.56vw;
}

.back_arrow {
    width: 1.20vw;
    height: 1.98vw;
}

.block1 {
    width: 100%;
    margin-top: 2.08vw;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.tip {
    font-size: 1vw;
    margin-bottom: 1vw;
}

.title {
    margin-top: 3vw;
    color: #C68DFE;
    font-size: 1.2vw;

}

.container_create {

    margin-top: 1vw;
    display: flex;
    flex-direction: column;
    gap: 1vw;
}

.input_text {
    flex: none;
    font-size: 1vw;
    cursor: pointer;
    width: 30vw;
    padding: 0.5vw 1vw;
    border-radius: 0.5vw;
    background: rgba(198, 141, 254, 0.26);

}

.input_text::placeholder {
    color: #939393;
}

.container_categories {
    margin-top: 2vw;
    width: 44vw;
    display: flex;
    gap: 1.5vw;
    flex-direction: column;
}

.category {
    font-size: 1vw;
    cursor: pointer;
    padding: 0.8vw 2vw;
    border-radius: 0.5vw;
    background: rgba(198, 141, 254, 0.26);
    color: #F2EDFE;
    display: flex;
    justify-content: space-between;
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
    flex: none;
    width: 13.38vw;
    height: 2.86vw;

margin-left: 1vw;
    background: #865DF8;
    border-radius: 0.5vw;
    font-size: 1.04vw;
    color: white;
    padding: 0.52vw;
    border: none;
    cursor: pointer;
    font-family: 'Unbounded';

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

.container {
    padding-left: 0.2vw;
    padding-right: 3.91vw;
}
</style>