<template>
    <div>
        <h2 class="text-center font-weight-bold text-monospace">Login</h2>
        <form>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                    <input
                        type="email" class="form-control" v-model.trim="email"
                        name="email" id="inputEmail"
                        placeholder="yourAddress@example.com" value="" required/>
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                    <input
                    type="password" class="form-control" v-model.trim="password"
                    name="password" id="inputPassword"
                    placeholder="yourPassword" value="" required/>
            </div>
        </form>
        <div class="form-group">
            <button class="btn btn-primary" @click="login()">Login</button>
            <button class="btn btn-secondary" @click="back()">Back</button>
        </div>
        <br><br>
        <br><br>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'; 
    import Vuex from 'vuex';
    Vue.use(Vuex);

    export default {
        data() {
            return {
                email:'',
                password: '',
                showFailure: false,
                failMessage: '',
                userType: ''
            }
        },
        methods: {
            login: function() {
                axios.post('api/login', {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    this.$store.commit("setToken", response.data.access_token);
                    
                    axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token;
                    return axios.get('api/users/me').then(response => {
                        this.$store.commit("setUser", response.data.data);
                        this.$socket.emit('user_enter', response.data.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
                })
                .then(response => {
                    this.$router.push('/myProfile');
                })
                .catch(response => {
                    sessionStorage.removeItem('access-token');
                    this.$store.commit("removeToken");
                    console.log(response);
                    this.showFailure = true;
                    this.failMessage = 'Invalid Credentials!';
                });
            },
            back: function() {
                this.$router.push('/');
            },
        },
        mounted() {
            console.log('Component Login mounted.');
        }
    }
</script>

