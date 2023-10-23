require('./bootstrap');

window.Vue = require('vue').default;

// npm install vue bootstrap bootstrap-vue
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


// npm install vuex --save
import Vuex from 'vuex'
import store_data from './store.js'

// npm install vue-router
import VueRouter from 'vue-router'
import { routes } from './routes.js'


// npm install vue-i18n
import VueI18n from 'vue-i18n'


// npm install vue-sweetalert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// npm install vue-burger
import VBurger from 'vue-burger';

import index from "./components/index";
import sidebar from './partials/sidebar.vue';
import navbar from './partials/admin-navbar.vue';

// npm install --save-dev @fortawesome/fontawesome-free
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'

Vue.component('index', index);
Vue.component('sidebar', sidebar);
Vue.component('admin-navbar', navbar);

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueI18n)
Vue.use(VueSweetalert2);
Vue.use(VBurger);

const store = new Vuex.Store(store_data);

let lang = localStorage.getItem('lang') ? localStorage.getItem('lang') : 'en';

localStorage.setItem('lang', lang);

import ku from './messages/ku';
import ar from './messages/ar';
import en from './messages/en';

const messages = {
    en: en,
    ku: ku,
    ar: ar
}

const i18n = new VueI18n({
    locale: lang, // set locale
    messages, // set locale messages
})


const router = new VueRouter({
    mode: 'history',
    hash: false,
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!localStorage.getItem('loggedIn')) {
            next("/login");
        } else {
            next();
        }
    } else {
        next()
    }
});

const app = new Vue({
    el: '#app',
    router,
    store,
    i18n
});