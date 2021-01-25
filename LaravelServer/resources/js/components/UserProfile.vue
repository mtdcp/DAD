<template>
    <div><br><br>
    <form v-if="!editingUser">  
        <h2 class="text-center font-weight-bold text-monospace">My profile info</h2><br><br>
            <div align="center">
                <img v-if="user.photo != null" :src="user.photo" v-model="user.photo" height="128" width="128"/>
                <img v-else src="https://assets.boredpanda.com/blog/wp-content/themes/boredpanda/images/default-user-profile-image.png" width="128" height="128"/><br><br>
            </div>
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">Name</span>
                    <input
                        type="text" class="form-control" readonly="readonly"
                        name="name" id="name" v-model="user.name" value="" aria-label="Default" 
                        aria-describedby="inputGroup-sizing-default"/>
                </div><br>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Email:</span>
                    <input
                        type="email" class="form-control" readonly="readonly"
                        name="email" id="email" v-model="user.email" value=""/>
                </div><br>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Type:</span>
                    <input
                        type="text" class="form-control" readonly="readonly"
                        id="type" name="type" v-model="user.type" value=""/>
                </div><br>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Status:</span>
                    <input
                        type="text" class="form-control" readonly="readonly"
                        name="status" id="status" v-model="user.active" value=""/>
                </div><br>
                <div class="input-group" v-if="user.type == 'User'">
                    <span class="input-group-text" id="inputGroup-sizing-sm">NIF:</span>
                    <input
                        type="text" class="form-control" readonly="readonly"
                        name="nif" id="nif" v-model="user.nif" value="" />
                </div><br><br>
                <a class="btn btn-primary" v-on:click="edit">Edit Profile</a>
        </form><br>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div><br>

        <profile-edit v-if="editingUser" :user="user" @user-save="update" @user-cancel="cancel"> </profile-edit>
    </div>
</template>

<script>
    import profileEdit from './UserProfileEdit.vue';

    export default {
        data() {
            return {
                user: '',
                editingUser: false,
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: ''
            }
        },
        components: {
        'profile-edit': profileEdit,
        },
        methods: {
            edit: function() {
                this.editingUser = true;
            },
            update: function() {
                this.editingUser = false;
                this.showSuccess = true;
                this.successMessage = 'Your profile info was updated';
                this.getUser();
            },
            cancel: function() {
                this.editingUser = false;
                this.getUser();
            },
            getUser: function() {
                axios.get('api/users/'+ this.$store.state.user.id)
                .then(response => {
                    this.user = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },
        sockets: {
            income_movement_added (destUser) {
                this.$toasted.show( 'New income movement added to your virtual wallet!' );
            },
            income_movement_added_special () {
                this.$toasted.show( 'New income movement added to a user virtual wallet!' );
            },
            movement_added (fromUser) {
                this.$toasted.show( 'New movement added to your virtual wallet - ' + fromUser +' made a transfer to you' );
            },
            movement_added_special () {
                this.$toasted.show( 'New movement added to a virtual wallet');
            },
        },
        mounted() {
            this.getUser();
            console.log('Component UserProfile mounted.')
        }
    }
</script>