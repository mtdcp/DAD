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
        <h2>Create Account</h2> <br>
        <form method="post">
            <div class="form-group">
                <label for="inputName">Name</label>
                <input
                    type="text" class="form-control" v-model="name"
                    name="name" id="inputName"
                    placeholder="Fullname" value="" required/>
                <div class="error" v-if="!$v.name.required">Field is required</div>
                <div class="error" v-if="!$v.name.minLength">Name min length is 3</div>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                    type="email" class="form-control" v-model="email"
                    name="email" id="inputEmail"
                    placeholder="Email address" value=""/>
                <div class="error" v-if="!$v.email.required">Field is required</div>
                <div class="error" v-if="!$v.email.email">Field must be an email</div>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input
                    type="password" class="form-control" v-model="password"
                    name="password" id="inputPassword"
                    placeholder="A strong password..." value="" required/>
                <div class="error" v-if="!$v.password.required">Field is required</div>
                <div class="error" v-if="!$v.password.minLength">Password min length is 3</div>
            </div>
            <div class="form-group">
                <label for="inputNif">NIF</label>
                <input
                    type="nif" class="form-control" v-model="nif"
                    name="nif" id="inputNif"
                    placeholder="NIF" value="" required/>
                <div class="error" v-if="!$v.nif.required">Field is required</div>
                <div class="error" v-if="!$v.nif.minLength">NIF must have 9 digits</div>
                <div class="error" v-if="!$v.nif.maxLength">NIF must have 9 digits</div>
                <div class="error" v-if="!$v.nif.numeric">Field must be numeric</div>
            </div>
            <div class="form-group">
                <label for="inputPhoto">Photo with .jpg extension</label><br>
                <input
                    type="file" v-on:change="onFileSelected"
                    id="inputPhoto" value="" accept=".jpg"/>
            </div>
        </form>
        <div class="form-group">
            <a class="btn btn-secondary" v-on:click.prevent="saveUser()">Save</a>
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
                name: '',
                email: '',
                password: '',
                nif: '',
                image: null,
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: '',
                userExists: true
            }
        },
        validations: {
            name: {
                required,
                minLength: minLength(3),
            },
            email: {
                required,
                email,
            },
            password: {
                required,
                minLength: minLength(3),
            },
            nif: {
                required,
                numeric,
                minLength: minLength(9),
                maxLength: maxLength(9),
            },
        },
        methods: {
            saveUser: function() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'Error while creating your account - Invalid data';
                } else {
                    const fd = new FormData();
                    fd.append('name', this.name);
                    fd.append('email', this.email);
                    fd.append('password', this.password);
                    fd.append('nif', this.nif);
                    fd.append('image', this.image);

                    axios.post('api/users/create', fd, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then(response=>{
                        const fdWallet = new FormData();
                        fdWallet.append('id', response.data.id);
                        fdWallet.append('email', this.email);
                        fdWallet.append('balance', 0.00);

                        axios.post('api/wallets', fdWallet, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        }).then(response=>{
                            this.showFailure = false;
                            this.showSuccess = true;
                            this.successMessage = 'Your account was created successfully';
                            this.$router.push('/login');
                        }).catch(error => {
                            console.log(error);
                            this.showSuccess = false;
                            this.showFailure = true;
                            this.failMessage = 'Error while creating your wallet - Invalid data';
                        }); 
                    }).catch(error => {
                        console.log(error);
                        this.showSuccess = false;
                        this.showFailure = true;
                        this.failMessage = 'Error while creating your account - Invalid data';
                    });
                }
            },
            cancel: function() {
                this.$router.push('/');
            },
            onFileSelected: function(event) {
                this.image = event.target.files[0];
            },
        },
        mounted() {
            console.log('Component UserAnonymousCreate mounted.')
        }
    }
</script>

<style>
    .error {
        color: red !important;
    }
</style>