<template>
    <div style="background-color: orange;">
        <button @click="pushBack()" style="border: 1px solid purple;"><- Назад</button>
                <p>Чатик</p>
    </div>
    <div class="container" ref="messagesContainer">
        <div v-for="message in allMessages" :key="message.id" style="border: 1px solid red;">
            <div v-html="message.content"></div>
            <img v-if="message.file_path" :src="linkApp + '/' + message.file_path"
                style="max-width: 450px; max-height: 500px;">
        </div>
    </div>
    <textarea name="" id="" @keydown="handleKeyDown" v-model="inputData"
        style="border: 1px solid black; width: 600px;">Fdsfdfs</textarea>
    <input type="file" @change="handleFileUpload" />
    <button @click="sendMessage" style="border: 1px solid black;">Отправить</button>
</template>
<script>
export default {
    name: 'Chat',
    data() {
        return {
            friendId: this.$route.params.id,
            inputData: '',
            selectedFile: null,
            allMessages: [],
            linkApp: '',
        }
    },
    methods: {
        getMessages() {
            axios.get(`/chat/${this.friendId}/messages`)
                .then(response => {
                    this.allMessages = response.data;
                })
        },
        async sendMessage() {
            const formData = new FormData();
            formData.append('content', this.inputData.replace(/\n/g, '<br>'));
            if (this.selectedFile) {
                formData.append('file', this.selectedFile);
            }


            await axios.post(`/chat/${this.friendId}/message/send`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
                .then(response => {
                    console.log(response.data);
                    this.getMessages();
                    this.inputData = '';
                    this.selectedFile = null;
                    this.scrollToBottom();
                });

        },
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0]; // Получаем выбранный файл
        },
        pushBack() {
            this.$router.go(-1);
        },
        handleKeyDown(event) {
            if (event.shiftKey && event.key === 'Enter') {
                event.preventDefault();
                this.sendMessage();
            }
        },
        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight; // Прокручиваем вниз
            }
        }
    },

    mounted() {
        console.log(this.friendId);
        this.getMessages();
    },
    created() {
        this.linkApp = `${import.meta.env.VITE_APP_URL}/storage`;
    }

}
</script>
<style></style>