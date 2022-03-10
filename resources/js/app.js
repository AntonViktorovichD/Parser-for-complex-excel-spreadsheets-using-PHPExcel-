require('./bootstrap');
import {createApp} from 'vue';
import message from './components/counter.vue';

const app = createApp({});
app.component('bind-attribute', message);

app.mount('#bind-attribute');
