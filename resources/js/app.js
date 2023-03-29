/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
window.Vue = require('vue').default;
window.io = require('socket.io-client');
window.axios = require('axios');
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};
// main.js
import Echo from "laravel-echo";
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import { routes } from './routes';
import { store } from './store';
import vuetify from './vuetify';
import Toasted from 'vue-toasted';
import moment from "moment";
import Vuelidate from 'vuelidate';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VModal from 'vue-js-modal';
import DatetimePicker from 'vuetify-datetime-picker';





Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(Toasted, { position: 'bottom-center', duration: 2500, singleton: true });
Vue.use(Vuelidate);
Vue.use(VueSweetalert2);
Vue.use(VModal, { dynamicDefault: { draggable: true, resizable: true } });
Vue.use(DatetimePicker);


// User router
const Router = new VueRouter({
    mode: 'history',
    saveScrollPosition: false,
    routes: routes,
    vuetify: vuetify
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('admin-layout', require('./components/Layouts/AdminLayout.vue').default);
Vue.component('user-layout', require('./components/Layouts/UserLayout.vue').default);
Vue.component('bottom-nav-layout', require('./components/Global/BottomNavLayout.vue').default);
Vue.component('loading', require('./components/Global/Loading.vue').default);
Vue.component('login', require('./components/Auth/Login.vue').default);
Vue.component('forgot-password', require('./components/Auth/ForgotPassword.vue').default);
Vue.component('register', require('./components/Auth/Register.vue').default);
Vue.component('footer-layout', require('./components/Global/Footer.vue').default);



if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        namespace: 'App\\Events',
        broadcaster: 'socket.io',
        host: window.location.hostname + ":2096",
        authEndpoint: "/broadcasting/auth"
    });
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: Router,
    store: store,
    vuetify: vuetify
});
