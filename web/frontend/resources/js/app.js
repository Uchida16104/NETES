import { createApp } from 'vue'

createApp({
    data() {
        return {
            connected: true
        }
    },
    mounted() {
        document.body.addEventListener('htmx:responseError', () => {
            this.connected = false
        })
    }
}).mount('body')

