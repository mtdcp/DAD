<template>
    <div>
        <h3 class="text-center font-weight-bolder text-monospace">Welcome Stranger!</h3><br><br><br><br>
        <h2 class="text-center font-weight-bold text-monospace">VIRTUAL WALLETS TOTAL MONEY:</h2><br>

        <h1 align="center" >{{ walletsNumber }}€</h1>

    </div>
</template>

<script>
export default {
    data () {
        return {
            walletsNumber: ''
        }
    },
    methods: {
        getAllWalletsNumber: function() { 
            axios.get('api/wallets/all/count')
            .then(response=>{
                this.walletsNumber = response.data;});
        },
        getAllBalance: function() {
            axios.get('api/wallets/all/balance')
            .then(response=>{
                this.walletsNumber = response.data;});
        }
    },
    mounted() {
       // this.getAllWalletsNumber();
        this.getAllBalance();
        console.log('Component InitialPage mounted.')
    }
}
</script>

<style>
    .circle {
      height: 71px;
      width: 75px;
      border-style: solid;
      border-color: #0099ff;
      border-radius: 900%;
      margin-left: 530px;
      margin-top: -10px;
    }
</style>