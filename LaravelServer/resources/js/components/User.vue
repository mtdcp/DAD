<template>
    <div>
        <br><br>

        <button class="btn btn-info" v-if="$store.state.user" v-on:click.prevent="createUser">Create Operator or Administrator</button>

        <br><br>
        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>

        <user-create v-if="creatingUser" @user-created="userCreation" @user-not-created="userNotCreated" @user-canceled="cancelCreation"></user-create>

        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <user-list :wallets="wallets" @delete-click="deleteUser" @desact-click="desactUser" @act-click="actUser"></user-list>
    </div>
</template>

<script>
    window.Vue = require('vue');

    import UserListComponent from './UserList.vue';
    import UsersCreateComponent from './UserCreate.vue';

    export default {
        data () {
            return {
                wallets: [],
                editingUser: false,
                user: '',
                currentUser: null,
                showSuccess: false,
                successMessage: '',
                title: 'All Users Accounts',
                showFailure: false,
                failMessage: '',
                creatingUser: false
            }
        },
        components: {
            'user-list': UserListComponent,
            'user-create': UsersCreateComponent
        },
        methods: {
            getWallets: function(){
                axios.get('api/wallets')
                .then(response=>{
                    this.wallets = response.data.data;
                });
            },
            createUser: function(){
                this.creatingUser = true;
                this.currentUser = null;
                this.editingUser = false;
            },
            deleteUser: function(user){
                if(user.id == this.$store.state.user.id) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'You can\'t delete your own account!';
                } else {
                    axios.delete('api/users/'+ user.id)
                    .then(response => {
                        this.showSuccess = true;
                        this.successMessage = 'User Deleted';
                    })
                    .catch(response => {
                        this.showSuccess = false;
                        this.showFailure = true;
                        this.failMessage = 'This user can\'t be deleted - his wallet has money';
                    });
                }
            },
            desactUser: function(userReceived) {
                axios.put('api/users/' + userReceived.id, userReceived).then(response => {
                    this.showSuccess = true;
                    this.showFailure = false;
                    this.successMessage = 'User Desactivated';
                })
                .catch(error => {
                    console.log(error);
                });
            },
            actUser: function(userReceived) {
                axios.put('api/users/' + userReceived.id, userReceived).then(response => {
                    this.showSuccess = true;
                    this.showFailure = false;
                    this.successMessage = 'User Activated';
                })
                .catch(error => {
                    console.log(error);
                });
            },
            userCreation: function() {
                this.creatingUser = false;
                this.showSuccess = true;
                this.showFailure = false;
                this.successMessage = 'User Created';
            },
            userNotCreated: function() {
                //this.creatingUser = false;
                this.showSuccess = false;
                this.showFailure = true;
                this.failMessage = 'User was not created - Invalid Data...';
            },
            cancelCreation: function() {
                this.creatingUser = false;
            },
            logout: function() {
                axios.post('/api/logout').then(response => {
                    localStorage.removeItem('access-token');
                    this.$store.commit("removeToken");
                    alert('Logout successfully');
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getWallets();
            console.log('Component User mounted.')
        }
    }
</script>
