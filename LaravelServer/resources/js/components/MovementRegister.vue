<template>
    <div class="jumbotron">
        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div><br>
        <h2>Create Movement</h2> <br>
        <form method="post">
            <div class="form-group">
                <label for="inputEmail">Destination Email:</label>
                <input
                    type="text" class="form-control" v-model="wallet_id"
                    name="wallet_id" id="inputEmail"
                    placeholder="Email of virtual wallet" value="" required/>
                <div class="error" v-if="!$v.wallet_id.required">Field is required</div>
                <div class="error" v-if="!$v.wallet_id.email">Field must be an email</div>
            </div>
            <div class="form-group">
                <label for="inputValue">Value:</label>
                <input
                    type="text" class="form-control" v-model="value"
                    name="value" id="inputValue"
                    placeholder="Value between 0.01€ and 5000.00€" value="" required/>
                <div class="error" v-if="!$v.value.required">Field is required</div>
                <div class="error" v-if="!$v.value.between">Value must be between 0.01€ and 5000€</div>
            </div>
            <div class="form-group">
                <label for="inputTypePayment">Type of Payment:</label>
                <select class="form-control" id="inputTypePayment" name="type_payment" v-model="type_payment" required>
                    <option disabled selected value=""> Select one type of payment </option>
                    <option value="c"> Cash </option>
                    <option value="bt"> Bank Transfer </option>
                </select>
                <div class="error" v-if="!$v.type_payment.required">Field is required</div>
            </div>
            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="source_description"
                    name="sourceDescription" id="inputSourceDescription" value="" required/>
                <div class="error" v-if="!$v.source_description.required">Field is required</div>
                    
            </div>
            <div class="form-group">
                <label for="inputIban">IBAN:</label>
                <input
                    type="text" class="form-control" v-model="iban"
                    name="iban" id="inputIban"
                    placeholder="PT00000000000000000000000" value="" :disabled="type_payment!='bt'"/>
            </div>
        </form>
        <div class="form-group">
            <a class="btn btn-secondary" v-on:click.prevent="registerMovement()">Save</a>
            <a class="btn btn-light" v-on:click.prevent="cancel()">Cancel</a>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import Vuelidate from 'vuelidate';
    import { required, minLength, maxLength, between, numeric, email } from 'vuelidate/lib/validators';
    Vue.use(Vuelidate);

    export default {
        data() {
            return {
                value: 0,
                wallet_id: '',
                source_description: '',
                type_payment: '',
                iban: '',
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: '',
                username: '',
                userBalance: 0
            }
        },
        validations: {
            wallet_id: {
                required,
                email
            },
            value: {
                required,
                between: between(0.01, 5000),
            },
            type_payment: {
                required
            },
            source_description: {
                required
            }
        },
        methods: {
            registerMovement: function() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'Movement not created - Invalid data AQUI';
                } else {
                    const fd = new FormData();
                    fd.append('wallet_id', this.wallet_id);
                    fd.append('value', this.value);
                    fd.append('type_payment', this.type_payment);
                    fd.append('source_description', this.source_description);
                    if(this.type_payment == 'bt'){
                        fd.append('iban', this.iban);
                    }

                    axios.post('api/movements/operator', fd, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then(response=>{
                        this.showFailure = false;
                        this.showSuccess = true;
                        this.successMessage = 'Movement created successfully';   
                        this.getUser(); 
                        if(this.value > 1000) {
                            this.$socket.emit('income_movement_added_special', 1);
                        }
                    }).catch(error => {
                        console.log(error);
                        this.showSuccess = false;
                        this.showFailure = true;
                        this.failMessage = 'Movement not created - Invalid data';
                    });
                }
            },
            cancel: function() {
                this.$router.back();
            },
            getUser: function() {
                axios.get('api/users/show/' + this.wallet_id).then(response=>{
                    this.username = response.data[0].id;
                    this.$socket.emit('income_movement_added', response.data[0].id);
                    if(this.value > 1000) {
                        this.$socket.emit('specialUserIncome', 1);
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        },
        sockets: {
            not_logged_in () {
                axios.get('api/sendEmail/' + this.wallet_id + '/' + this.username).then(response=>{
                    this.$toasted.error('User is not online. Email notification sent!');
                    if(this.value > 1000) {
                                axios.get('api/sendEmailSpecial/admin1@mail.pt').then(response=>{
                            this.$toasted.error('Admin1 is not online. Email notification sent!');
                        }).catch(error => {
                            console.log(error);
                        });
                    }
                }).catch(error => {
                    console.log(error);
                });
            },
            logged_in () {
                this.$toasted.show('User is online. Notification sent sucessfully!');
                if(this.value > 1000) {
                        this.$toasted.show('Admin1 is online. Notification sent sucessfully!');
                    }
            },
            logged_in_special () {
                this.$toasted.show('Admin1 is online. Notification sent sucessfully!');
            },
            not_logged_in_special () {
                axios.get('api/sendEmailSpecial/admin1@mail.pt').then(response=>{
                    this.$toasted.error('Admin1 is not online. Email notification sent!');
                }).catch(error => {
                    console.log(error);
                });
            },

        },
        mounted() {
            console.log('Component MovementRegister mounted.')
        }
    }
</script>

<style>
    .error {
        color: red !important;
    }
</style>