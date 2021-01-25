<template>
    <div class="text-center"> 
        <br><br><br><br>
        <h3 class="font-weight-bold text-monospace">Do you really want to logout?</h3><br>
        <button class="btn btn-danger" v-on:click.prevent="logout">Yes</button>
        <button class="btn btn-success" v-on:click.prevent="cancel">No</button>

        <br><br>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>
    </div>
</template>

<script>
    window.Vue = require('vue');

    export default {
        data() {
            return {
                failMessage: '',
                showFailure: false
            }
        },
        methods: {
            logout: function() {
                axios.post('/api/logout').then(response => {
                    this.$socket.emit('user_exit', this.$store.state.user.id);
                    this.$store.commit("removeToken");
                    this.$store.commit("removeUser");
                    this.$router.push('/');
                }).catch(error => {
                    console.log(error);
                    this.showFailure = true;
                    this.failMessage = 'Error logging out! Try again later'
                });
            },
            cancel: function() {
                this.$router.back();
            }
        },
        sockets: {
            income_movement_added (destUser) {
                this.$toasted.show( 'New income movement added to your virtual wallet!' );
            },
            movement_added (fromUser) {
                    this.$toasted.show( 'New movement added to your virtual wallet - ' + fromUser +' made a transfer to you' );
            }
        },
        mounted() {
                console.log('Component Logout mounted.')
            }
    }
</script>
