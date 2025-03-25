<template>
    <SideMenu />

    <div class="content" id="content">
        <div class="name_cart">
            <p>Управление категориями</p>
        </div>
        <a href=""><img class="menu_mob" :src="linkApp + '/img/icons/menu_mob.png'" alt=""></a>

        <div class="form-container">
            <p class="title">Удаление категорий</p>
            <p class="tip">Нажмите на категорию для удаления</p>
            <div class="container_categories">
                <div class="category" @click="deleteCategory(category)" :key="category.id"
                    v-for="category in categories">{{ category.name }}</div>
            </div>


            <div class="container_create">
                <p class="title">Добавление категорий</p>
                <input class="input_text" v-model="category" type="text" placeholder="Введите название новой категории">
                <button class="but_post" @click="addCategory" type="submit">Создать</button>
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
.tip {
    font-size: 1vw;
    margin-bottom: 1vw;
}

.title {
    font-size: 1.3vw;
    margin-bottom: 2vw;
}

.container_create {

    margin-top: 4vw;
    display: flex;
    flex-direction: column;
    gap: 1vw;
}

.input_text {
    font-size: 1vw;
    cursor: pointer;
    width: 30vw;
    padding: 0.5vw 1vw;
    border-radius: 2vw;
    border: 1px solid #865DF8;
}

.container_categories {

    flex-wrap: wrap;
    width: 65vw;
    display: flex;
    gap: 2vw;
}

.category {
    font-size: 1vw;
    cursor: pointer;
    padding: 0.8vw 2vw;
    border-radius: 2vw;
    border: 1px solid #865DF8;
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
    margin-left: 55.90vw;
    margin-bottom: 2.60vw;
    margin-top: 2.08vw;
    display: flex;
    justify-content: center;
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