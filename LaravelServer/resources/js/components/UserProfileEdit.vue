<template>
    <div>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
             <strong>{{ failMessage }}</strong>
        </div><br>

        <form>
            <h2 class="text-center font-weight-bold text-monospace">Edit my profile</h2> <br>
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input
                        type="text" class="form-control" v-model="user.name"
                        name="newname" id="inputName" 
                        placeholder="Fullname" value="user.name"/>
                </div>
                <div class="form-group">
                    <label for="inputPasswordOld">Old Password</label>
                    <input
                        type="password" class="form-control" v-model="oldPassword"
                        name="oldpassword" id="oldinputPassword" 
                        placeholder="Your old password" value=""/>
                </div>
                <div class="form-group">
                    <label for="inputPasswordNew">New Password</label>
                    <input
                        type="password" class="form-control" v-model="newPassword"
                        name="newpassword" id="newinputPassword" 
                        placeholder="Your new password" value=""/>
                    <div class="error" v-if="!$v.newPassword.minLength">Password min length is 3</div>
                </div>
                <div class="form-group">
                    <label for="inputPassword">New Password Confirmation</label>
                    <input
                        type="password" class="form-control" v-model="passwordConfirmation"
                        name="confirmpassword" id="newinputPasswordConfirm" 
                        placeholder="Confirm your new password" value=""/>
                    <div class="error" v-if="!$v.passwordConfirmation.sameAsPassword">Password confirmation does not match</div>
                </div>
                <div class="form-group">
                    <label for="inputPhoto">Photo with .jpg extension</label><br>
                    <input
                        type="file" v-on:change="onFileSelected" 
                        id="inputnewPhoto" value="" accept=".jpg" height="128" width="128"/>
                </div>
                <div class="form-group" v-if="user.type == 'User'">
                    <label for="inputName">NIF</label>
                    <input
                        type="text" class="form-control" v-model="user.nif"
                        name="newnif" id="inputNif" 
                        placeholder="NIF" value=""/>
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
    import { required, minLength, maxLength, between, numeric, email, sameAs } from 'vuelidate/lib/validators';
    Vue.use(Vuelidate);

    export default {
        props: [
            "user"
        ],
        data() {
            return {
                oldPassword: '',
                newPassword: '',
                image: null,
                passwordConfirmation: '',
                showFailure: false,
                failMessage: '',
            }
        },
        validations: {
                newPassword: {
                    minLength: minLength(3),
                },
                passwordConfirmation: {
                    sameAsPassword: sameAs('newPassword')
                },
            },
        methods: {
            saveUser: function() {
                if(this.oldPassword && this.newPassword.length < 3) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'Your new password must contain at least 3 characters';
                }else if(this.oldPassword && this.newPassword && this.newPassword != this.passwordConfirmation) {
                    this.showSuccess = false;
                    this.showFailure = true;
                    this.failMessage = 'New password doesn\'t match the confirmation';
                } else {
                    if(this.newPassword) {
                        this.user.password = this.newPassword;
                    }
                    axios.patch('api/users/' + this.user.id, {
                        'name': this.user.name,
                        'password': this.user.password,
                        'oldPassword': this.oldPassword,
                        'passwordConfirmation': this.passwordConfirmation,
                        'nif': this.user.nif,
                        'image': this.user.photo
                    }).then(response=>{
                        this.showFailure = false;

                        this.$store.commit("setUser", response.data);
                                
                        const fd = new FormData();
                        fd.append('image', this.image);

                        axios.post('api/users/' + this.user.id + '/photo', fd, {
                            headers: {
                                'Content-type': 'multipart/form-data'
                            }
                        }).then(response=>{
                            this.showFailure = false;
                                        
                            this.$emit('user-save');
                        })
                        .catch(error=>{
                            console.log(error);
                        });
                    })
                    .catch(error=>{
                        console.log(error);
                        this.showSuccess = false;
                        this.showFailure = true;
                        this.failMessage = 'Your profile was not updated - Invalid data';
                    });
                }
            },
            onFileSelected: function(event) {
                this.image = event.target.files[0];
            },
            cancel: function() {
                this.$emit('user-cancel'); 
            },
        }
    }
</script>

<style>
    .error {
        color: red !important;
    }
</style>