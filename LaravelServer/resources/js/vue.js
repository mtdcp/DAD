/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import UsersComponent from './components/User.vue';
const userListEdit = Vue.component('user-edit-list', UsersComponent);

import UserProfileComponent from './components/UserProfile.vue';
const userProfile = Vue.component('user-profile', UserProfileComponent);

import StatisticsForAdminsComponent from './components/StatisticsForAdmins.vue';
const statsForAdmins = Vue.component('stats', StatisticsForAdminsComponent);

import StatisticsForUsersComponent from './components/StatisticsForUsers.vue';
const statsForUsers = Vue.component('stats-users', StatisticsForUsersComponent);

import LoginComponent from './components/Login.vue';
const login = Vue.component('login', LoginComponent);

import LogoutComponent from './components/Logout.vue';
const logout = Vue.component('logout', LogoutComponent);

import CreateAccountComponent from './components/UserAnonymousCreate.vue';
const createAccount = Vue.component('createAccount', CreateAccountComponent);

import InitialComponent from './components/InitialPage.vue';
const initialPage = Vue.component('initial-page', InitialComponent);

import PrivateInfoPage from './components/PrivateInfoPage.vue';
const privateInfoPage = Vue.component('private-info-page', PrivateInfoPage);

import MovementComponent from './components/Movement.vue';
const movement = Vue.component('movement', MovementComponent);

import MovementRegisterComponent from './components/MovementRegister.vue';
const movementRegister = Vue.component('movement-register', MovementRegisterComponent);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Vuex from 'vuex';
Vue.use(Vuex);

import Toasted from "vue-toasted";

Vue.use(Toasted, {
    theme: "outline",
    position: "bottom-center",
    duration: 8000,
    type: "info"
});

import VueSocketIO from "vue-socket.io";

Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://127.0.0.1:8080'
})); 

const store = new Vuex.Store(
    {
      state: {
            token: "",
            user: null
      },
      mutations: {
            setToken(state, token) {
                state.token = token;
                sessionStorage.setItem('access-token', token);
            },
            setUser(state, user) {
                state.user =  user;
                sessionStorage.setItem('user', JSON.stringify(user));
            },
            removeUser(state) {
                state.user = null;
                sessionStorage.removeItem('user');
            },
            removeToken(state) {
                state.token = null;
                sessionStorage.removeItem('access-token');
            },
            loadTokenFromStorage(state) {
                let tokenFromStorage = sessionStorage.getItem('access-token');
                if(tokenFromStorage) {
                    state.token = tokenFromStorage;
                    axios.defaults.headers.common.Authorization = "Bearer " + tokenFromStorage;
                }
            },
            loadUserFromStorage(state) {
                let userFromStorage = sessionStorage.getItem('user');
                if(userFromStorage) {
                    state.user = JSON.parse(userFromStorage);
                }
            }
        }
    });

const routes = [
    { path: '/', name: 'beggining', component: initialPage },
    { path: '/login', name: 'login', component: login },
    { path: '/logout', name: 'logout', component: logout },
    { path: '/statistics', name: 'statistics', component: statsForAdmins },
    { path: '/usersStatistics', name: 'users statistics', component: statsForUsers },
    { path: '/users', name: 'users', component: userListEdit },
    { path: '/createAccount', name: 'create account', component: createAccount },
    { path: '/myProfile', name: 'profile', component: userProfile },
    { path: '/movements', name: 'movement', component: movement },
    { path: '/privateInfoPage', name: 'privateInfo', component: privateInfoPage },
    { path: '/movementRegister', name: 'movementRegister', component: movementRegister }
];


const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.name == "logout" || to.name == "profile") {
        store.commit('loadUserFromStorage');
        if (!store.state.user) {
            next("/login");
            return;
        }
    }
    next();
});

router.beforeEach((to, from, next) => {
    if (to.name == "create account" || to.name == "login") {
        store.commit('loadUserFromStorage');
        if (store.state.user) {
            next("/myProfile");
            return;
        }
    }
    next();
});

router.beforeEach((to, from, next) => {
    if (to.name == "users" || to.name == "statistics") {
        store.commit('loadUserFromStorage');
        if (!store.state.user || store.state.user.type != "Administrator" ) {
            next("/privateInfoPage");
            return;
        }
    }
    next();
});

router.beforeEach((to, from, next) => {
    if (to.name == "movement" || to.name == "users statistics") {
        store.commit('loadUserFromStorage');
        if (!store.state.user || store.state.user.type != "User" ) {
            next("/privateInfoPage");
            return;
        }
    }
    next();
});

router.beforeEach((to, from, next) => {
    if (to.name == "movementRegister") {
        store.commit('loadUserFromStorage');
        if (!store.state.user || store.state.user.type != "Operator" ) {
            next("/privateInfoPage");
            return;
        }
    }
    next();
});

const app = new Vue({
    data() {
      return {
        loginPage: false
      }
    },
    router,
    store,
    el: '#app',
    created() {
        this.$store.commit('loadTokenFromStorage');
        this.$store.commit('loadUserFromStorage');
    }
}).$mount('#app');
