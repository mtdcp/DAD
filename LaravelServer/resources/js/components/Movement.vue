<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Virtual Wallet:</th>
                    <th>Current Balance:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ email }}</td>
                    <td>{{ currentBalance }}€</td>
                </tr>
            </tbody>
        </table><br><br>

        <button class="btn btn-info" v-if="$store.state.user" v-on:click.prevent="createMovement">Create Movement</button>

        <br><br>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <movement-details v-if="moreDetails" :movement="movement" @movement-details-close="closeMovementDetails"></movement-details>

        <div class="jumbotron">
            <h2>{{ title }}</h2>
        </div>

        <movement-list @more-details-click="movementDetails" @update-balance="getBalance"  @movement-cancel="cancelCreation" :creatingMovement="creatingMovement"></movement-list>
    </div>
</template>

<script>
    window.Vue = require('vue');

    import MovementListComponent from './MovementList.vue';
    import MovementDetailsComponent from './MovementDetails.vue';

    export default {
        data () {
            return {
                email: '',
                title: 'All movements from your virtual wallet',
                currentBalance: 0.0,
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: '',
                moreDetails: false,
                movement: '',
                creatingMovement: false,
            }
        },
        components: {
            'movement-list': MovementListComponent,
            'movement-details': MovementDetailsComponent
        },
        methods: {
            getBalance: function() {
                this.creatingMovement = false;
                axios.get('api/movements/balance/' + this.$store.state.user.id)
                .then(response=>{
                    this.currentBalance = response.data.end_balance;
                });
            },
            movementDetails: function(movement){
                this.movement = movement;
                this.moreDetails = true;
            },
            createMovement: function(){
                this.creatingMovement = true;
            },
            closeMovementDetails: function() {
                this.moreDetails = false;
            },
            cancelCreation: function() {
                this.creatingMovement = false;
            },
        },
        sockets: {
            income_movement_added (destUser) {
                this.$toasted.show( 'New income movement added to your virtual wallet!' );
                this.getBalance();
            },
              income_movement_added_special () {
                this.$toasted.show( 'New income movement added to your virtual wallet!' );
                this.getBalance();
            },
            movement_added (fromUser) {
                this.$toasted.show( 'New movement added to your virtual wallet - ' + fromUser +' made a transfer to you' );
                this.getBalance();
            },
            movement_added_special () {
                this.$toasted.show( 'New movement added to your virtual wallet made a transfer to you' );
                this.getBalance();
            }
        },
        mounted() {
            this.email = this.$store.state.user.email;
            this.getBalance();
            console.log('Component Movement mounted.')
        }
    }
</script>
