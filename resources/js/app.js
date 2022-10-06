import './bootstrap';

import { createApp } from 'vue';

import app from './componts/app.vue';

import router from './router';

import '../sass/app.scss'

import "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";

import * as bootstrap from 'bootstrap'

createApp(app).use(router).mount('#app');