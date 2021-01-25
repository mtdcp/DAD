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
        <h2>Create Operator or Administrator</h2> <br>
        <form method="post" >
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
                <label for="inputPhoto">Photo with .jpg extension</label><br>
                <input
                    type="file" v-on:change="onFileSelected"
                    id="inputPhoto" value="" accept=".jpg"/>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <select class="form-control" id="type" name="type" v-model="type" required>
                    <option value="a"> Administrator </option>
                    <option value="o"> Operator </option>
                </select>
                <div class="error" v-if="!$v.type.required">Field is required</div>
            </div>
            <div class="form-group">
                <a class="btn btn-secondary" v-on:click.prevent="saveUser">Save</a>
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
                name: '',
                email: '',
                password: '',
                image: null,
                type: '',
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: ''
            }
        },
        validations: {
            name: {
                required,
                minLength: minLength(3),
            },
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(3),
            },
            type: {
                required
            },
        },
        methods: {
            saveUser: function() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'Error while creating account - Invalid data';
                } else {
                    const fd = new FormData();
                    fd.append('name', this.name);
                    fd.append('email', this.email);
                    fd.append('password', this.password);
                    fd.append('image', this.image);
                    fd.append('type', this.type);

                    axios.post('api/users', fd, {
                        headers: {
                            'Content-type': 'multipart/form-data'
                        }
                    }).then(response=>{
                        this.$emit('user-created');
                    }).catch(error=>{
                        console.log(error);
                        this.$emit('user-not-created');
                    });
                }
            },
            cancel: function() {
                this.$emit('user-canceled');
            },
            onFileSelected: function(event) {
                this.image = event.target.files[0];
            },
        },
        mounted() {
            console.log('Component UserCreate mounted.')
        }
    }
</script>

<style>
    .error {
        color: red !important;
    }
</style>