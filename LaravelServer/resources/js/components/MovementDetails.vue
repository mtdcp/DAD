<template>
    <div class="jumbotron">
        <h2>Movement Details</h2> <br>
        <form>  
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">Description</span>
                <input
                    type="text" class="form-control" readonly="readonly"
                    name="description" id="description" v-model="movement.description" value="" aria-label="Default" 
                    aria-describedby="inputGroup-sizing-default"/>
            </div><br>
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">MB Entity Code</span>
                <input
                    type="text" class="form-control" readonly="readonly"
                    name="mb_entity_code" id="mb_entity_code" v-model="movement.mb_entity_code" value="" aria-label="Default" 
                    aria-describedby="inputGroup-sizing-default"/>
            </div><br>
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">Source Descritpion</span>
                <input
                    type="text" class="form-control" readonly="readonly"
                    name="source_description" id="source_description" v-model="movement.source_description" value="" aria-label="Default" 
                    aria-describedby="inputGroup-sizing-default"/>
            </div><br>
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">IBAN</span>
                <input
                    type="text" class="form-control" readonly="readonly"
                    name="iban" id="iban" v-model="movement.iban" value="" aria-label="Default" 
                    aria-describedby="inputGroup-sizing-default"/>
            </div><br>
            <div class="input-group">
                <span class="input-group-text" id="inputGroup-sizing-sm">MB Payment Reference</span>
                <input
                    type="text" class="form-control" readonly="readonly"
                    name="mb_payment_reference" id="mb_payment_reference" v-model="movement.mb_payment_reference" value="" aria-label="Default" 
                    aria-describedby="inputGroup-sizing-default"/>
            </div><br>
            <div align="center" v-if="movement.transfer != 0">
                <h4>User Photo</h4><br>
                <img v-if="userTransfer.photo != null" :src="userTransfer.photo" v-model="userTransfer.photo" height="128" width="128"/>
                <img v-else src="https://assets.boredpanda.com/blog/wp-content/themes/boredpanda/images/default-user-profile-image.png" width="128" height="128"/><br><br>
            </div>
            <div class="form-group">
                <a class="btn btn-light" v-on:click.prevent="close()">Close</a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: [
            "movement"
        ],
        data() {
            return {
                showSuccess: false,
                successMessage: '',
                showFailure: false,
                failMessage: '',
                userTransfer: ''
            }
        },
        methods: {
            getUserTransfer: function() {
                if(this.movement.transfer == '1'){
                    axios.get('api/users/' + this.movement.transfer_wallet_id)
                    .then(response=>{
                        this.userTransfer = response.data.data;
                    })
                    .catch(error=>{
                        console.log(error);
                    });
                }
            },
            close: function() {
                this.$emit('movement-details-close');
            }
        },
        mounted() {
            this.getUserTransfer();
            console.log(this.movement);
            console.log('Component MovementDetails mounted.')
        }
    }
</script>