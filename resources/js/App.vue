
<template>
<router-view />
</template>

<script>


export default {
    name: 'App',
    components:{

    },
    beforeCreate()
    {
        this.$store.commit('authStore/initializeStore');

    }
    
};

</script>

<style>
    .outer-padding{
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0px 50px;
    }
</style>
