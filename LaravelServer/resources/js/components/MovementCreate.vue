<template>
    <div class="jumbotron">
        <h2>Create Movement</h2> <br>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div><br>
        <form method="post" >
            <div class="form-group">
                <label for="inputTransfer">Type of Movement:</label>
                <select class="form-control" id="inputTransfer" name="transfer" v-model="transfer" required>
                    <option disabled selected value=""> Select one type of movement </option>
                    <option value="0"> Payment to an external entity </option>
                    <option value="1"> Transfer </option>
                </select>
                <div class="error" v-if="!$v.transfer.required">Field is required</div>
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
                <label for="inputCategory">Category:</label><br>
                <select id="categories" name="categories" v-model="category_id">
                    <option disabled selected value=""> Select one category </option>
                    <option v-for="category in categoriesList"> {{ category }} </option>
                </select>
                <div class="error" v-if="!$v.category_id.required">Field is required</div>
            </div>
            <div class="form-group">
                <label for="inputDescription">Description:</label>
                <input
                    type="text" class="form-control" v-model="description"
                    name="description" id="inputDescription" value="" required/>
                <div class="error" v-if="!$v.description.required">Field is required</div>
            </div>
            <div class="form-group">
                <label for="inputTypePayment">Type of Payment:</label>
                <select class="form-control" id="inputTypePayment" name="type_payment" v-model="type_payment" :disabled="transfer!='0'">
                    <option disabled selected value=""> Select one type of payment </option>
                    <option value="bt"> Bank Transfer </option>
                    <option value="mb"> MB Payment </option>
                </select>
            </div>
            <div class="form-group">
                <label for="inputIban">IBAN:</label>
                <input
                    type="text" class="form-control" v-model="iban"
                    name="iban" id="inputIban"
                    placeholder="PT00000000000000000000000" value="" :disabled="type_payment!='bt'"/>
            </div>
            <div class="form-group">
                <label for="inputMBEntityCode">MB Entity Code:</label>
                <input
                    type="text" class="form-control" v-model="mb_entity_code"
                    name="mb_entity_code" id="inputMBEntityCode"
                    value="" :disabled="type_payment!='mb'"/>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_entity_code.numeric">Field must be numeric</div>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_entity_code.minLength">MB Entity Code digits are 5</div>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_entity_code.maxLength">MB Entity Code digits are 5</div>
            </div>
            <div class="form-group">
                <label for="inputMBPaymentReference">MB Payment Reference:</label>
                <input
                    type="text" class="form-control" v-model="mb_payment_reference"
                    name="mb_payment_reference" id="inputMBPaymentReference"
                    value="" :disabled="type_payment!='mb'"/>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_payment_reference.numeric">Field must be numeric</div>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_payment_reference.minLength">MB Payment Reference digits are 9</div>
                <div class="error" v-if="type_payment=='mb' && !$v.mb_payment_reference.maxLength">MB Payment Reference digits are 9</div>
            </div>
            <div class="form-group">
                <label for="inputEmail">Destination Email:</label>
                <input
                    type="email" class="form-control" v-model="transfer_wallet_id"
                    name="transfer_wallet_id" id="inputEmail"
                    placeholder="Email of virtual wallet" value="" :disabled="transfer!='1'"/>
                <div class="error" v-if="transfer=='1' && !$v.transfer_wallet_id.email">Field must be an email</div>
            </div>
            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="source_description"
                    name="sourceDescription" id="inputSourceDescription" value="" :disabled="transfer!='1'"/>
            </div>
            <div class="form-group">
                <a class="btn btn-secondary" v-on:click.prevent="saveMovement">Save</a>
                <a class="btn btn-light" v-on:click.prevent="cancel()">Cancel</a>
            </div>
        </form>
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
                transfer: '',
                value: 0,
                category_id: '',
                categoriesList: [],
                description: '',
                transfer_wallet_id: '',
                source_description: '',
                type_payment: '',
                iban: '',
                mb_entity_code: '',
                mb_payment_reference: '',
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: '',
            }
        },
        validations: {
            transfer: {
                required
            },
            value: {
                required,
                between: between(0.01, 5000),
            },
            category_id: {
                required
            },
            description: {
                required
            },
            mb_entity_code: {
                numeric,
                minLength: minLength(5),
                maxLength: maxLength(5)
            },
            mb_payment_reference: {
                numeric,
                minLength: minLength(9),
                maxLength: maxLength(9)
            },
            transfer_wallet_id: {
                email
            }
        },
        methods: {
            saveMovement: function() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'Movement not created - Invalid data AQUI';
                } else {
                    const fd = new FormData();
                    fd.append('transfer', this.transfer);
                    fd.append('value', this.value);
                    fd.append('category_id', this.category_id);
                    fd.append('description', this.description);
                    if(this.transfer == '1'){
                        if(this.transfer_wallet_id == this.$store.state.user.email) {
                            this.showSuccess = false;
                            this.showFailure = true;
                            this.failMessage = 'You can\'t do a transfer to your own account!';
                            return;
                        } else {
                            fd.append('transfer_wallet_id', this.transfer_wallet_id);
                        }
                        fd.append('source_description', this.source_description);
                    } else {
                        fd.append('type_payment', this.type_payment);
                        if(this.type_payment == 'bt'){
                            fd.append('iban', this.iban);
                        }
                        if(this.type_payment == 'mb'){
                            fd.append('mb_entity_code', this.mb_entity_code);
                            fd.append('mb_payment_reference', this.mb_payment_reference);
                        }
                    }

                    axios.post('api/movements', fd, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then(response=>{
                        this.$emit('movement-created');
                        if(this.transfer == '1' && this.transfer_wallet_id != this.$store.state.user.email) {
                            this.getUser(); 
                        }
                    })
                    .catch(error=>{
                        this.showSuccess = false;
                        this.showFailure = true;
                        this.failMessage = 'Movement not created - Invalid data';
                        console.log(error);
                    });
                }
            },
            cancel: function() {
                this.$emit('movement-canceled');
            },
            getCategories: function() {
                axios.get('api/categories')
                .then(response=>{
                    var idx = 0;
                    for(var name in response.data) {
                        this.categoriesList.push(response.data[idx].name);
                        idx++;
                    }
                })
                .catch(error=> {
                    console.log(error);
                });      
            },
            getUser: function() {
                axios.get('api/users/show/' + this.transfer_wallet_id).then(response=>{
                    this.$socket.emit('movement_added', response.data[0].id, this.$store.state.user.email);
                    if(this.value > 1000) {
                        this.$socket.emit('specialUser', 1);
                    }
                }).catch(error => {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.getCategories();
            console.log('Component MovementCreate mounted.')
        }
    }
</script>

<style>
    .error {
        color: red !important;
    }
</style>