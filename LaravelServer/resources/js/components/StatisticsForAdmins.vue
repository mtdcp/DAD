<template>
    <div>
        <br><br>

        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <button v-if="!always" title="Since Always" class="btn btn-sm btn-info" v-on:click.prevent="allData()">Since Always</button>
        <button v-if="!lastWeek" title="Last Week" class="btn btn-sm btn-info" v-on:click.prevent="lastWeekData()">Last Week</button>
        <button v-if="!lastMonth" title="Last Month" class="btn btn-sm btn-info" v-on:click.prevent="lastMonthData()">Last Month</button>
        <button v-if="!lastYear" title="Last Year" class="btn btn-sm btn-info" v-on:click.prevent="lastYearData()">Last Year</button><br><br>

        <stats-show v-if="always" :allUsers="allUsers" :admins="admins" :operators="operators" :users="users" :activeCounts="activeCounts" :allWallets="allWallets" :allMovements="allMovements" :globalBalance="globalBalance"></stats-show>

        <stats-show-last-week v-if="lastWeek" :allUsersLastWeek="allUsersLastWeek" :adminsLastWeek="adminsLastWeek" :operatorsLastWeek="operatorsLastWeek" :usersLastWeek="usersLastWeek" :activeCountsLastWeek="activeCountsLastWeek" :allWalletsLastWeek="allWalletsLastWeek" :allMovementsLastWeek="allMovementsLastWeek" :globalBalanceLastWeek="globalBalanceLastWeek"></stats-show-last-week>

        <stats-show-last-month v-if="lastMonth" :allUsersLastMonth="allUsersLastMonth" :adminsLastMonth="adminsLastMonth" :operatorsLastMonth="operatorsLastMonth" :usersLastMonth="usersLastMonth" :activeCountsLastMonth="activeCountsLastMonth" :allWalletsLastMonth="allWalletsLastMonth" :allMovementsLastMonth="allMovementsLastMonth" :globalBalanceLastMonth="globalBalanceLastMonth"></stats-show-last-month>

        <stats-show-last-year v-if="lastYear" :allUsersLastYear="allUsersLastYear" :adminsLastYear="adminsLastYear" :operatorsLastYear="operatorsLastYear" :usersLastYear="usersLastYear" :activeCountsLastYear="activeCountsLastYear" :allWalletsLastYear="allWalletsLastYear" :allMovementsLastYear="allMovementsLastYear" :globalBalanceLastYear="globalBalanceLastYear"></stats-show-last-year>
    </div>
</template>

<script>
    window.Vue = require('vue');
    import StatisticsShowComponent from './StatisticsForAdminsShow.vue';
    import StatisticsShowComponentLastWeek from './StatisticsForAdminsShowLastWeek.vue';
    import StatisticsShowComponentLastMonth from './StatisticsForAdminsShowLastMonth.vue';
    import StatisticsShowComponentLastYear from './StatisticsForAdminsShowLastYear.vue';

    import { CChartBar } from '@coreui/coreui-vue-chartjs';
    Vue.component('CChartBar', CChartBar);

    export default {
        data () {
            return {
                users: '',
                allWallets: '',
                allUsers: '',
                operators: '',
                admins: '',
                activeCounts: '',
                globalBalance: '',
                allMovements: '',
                lastWeek: false,
                lastMonth: false,
                lastYear: false,
                always: true,
                usersLastWeek: '',
                allUsersLastWeek: '',
                allWalletsLastWeek: '',
                operatorsLastWeek: '',
                adminsLastWeek: '',
                activeCountsLastWeek: '',
                globalBalanceLastWeek: '',
                allMovementsLastWeek: '',
                usersLastMonth: '',
                allUsersLastMonth: '',
                allWalletsLastMonth: '',
                operatorsLastMonth: '',
                adminsLastMonth: '',
                activeCountsLastMonth: '',
                globalBalanceLastMonth: '',
                allMovementsLastMonth: '',
                usersLastYear: '',
                allUsersLastYear: '',
                allWalletsLastYear: '',
                operatorsLastYear: '',
                adminsLastYear: '',
                activeCountsLastYear: '',
                globalBalanceLastYear: '',
                allMovementsLastYear: '',
                title: 'Global Statistics',
            }
        },
        components: {
            'stats-show': StatisticsShowComponent,
            'stats-show-last-week': StatisticsShowComponentLastWeek,
            'stats-show-last-month': StatisticsShowComponentLastMonth,
            'stats-show-last-year': StatisticsShowComponentLastYear
        },
        methods: {
            getAllUsersNumber: function() {
                axios.get('api/users/all/count')
                    .then(response=>{
                        this.allUsers = response.data;});
            },
            getAdminsNumber: function() {
                axios.get('api/users/admins/count')
                    .then(response=>{
                        this.admins = response.data;});
            },
            getOperatorsNumber: function() {
                axios.get('api/users/operators/count')
                    .then(response=>{
                        this.operators = response.data;});
            },
            getUsersNumber: function() { 
                axios.get('api/users/users/count')
                    .then(response=>{
                        this.users = response.data;});
            },
            getActivesNumber: function() { 
                axios.get('api/users/active/count')
                    .then(response=>{
                        this.activeCounts = response.data;});
            },
            getAllWalletsNumber: function() { 
                axios.get('api/wallets/all/count')
                    .then(response=>{
                        this.allWallets = response.data;});
            },
            getAllWalletsBalance: function() { 
                axios.get('api/wallets/all/balance')
                    .then(response=>{
                        this.globalBalance = response.data;});
            },
            getAllMovementsNumber: function() { 
                axios.get('api/movements/all/count')
                    .then(response=>{
                        this.allMovements = response.data;});
            },
            getAllUsersNumberLastWeek: function() {
                axios.get('api/users/all/lastWeek/count')
                    .then(response=>{
                        this.allUsersLastWeek = response.data;});
            },
            getAdminsNumberLastWeek: function() {
                axios.get('api/users/admins/lastWeek/count')
                    .then(response=>{
                        this.adminsLastWeek = response.data;});
            },
            getOperatorsNumberLastWeek: function() {
                axios.get('api/users/operators/lastWeek/count')
                    .then(response=>{
                        this.operatorsLastWeek = response.data;});
            },
            getUsersNumberLastWeek: function() { 
                axios.get('api/users/users/lastWeek/count')
                    .then(response=>{
                        this.usersLastWeek = response.data;});
            },
            getActivesNumberLastWeek: function() { 
                axios.get('api/users/active/lastWeek/count')
                    .then(response=>{
                        this.activeCountsLastWeek = response.data;});
            },
            getAllWalletsNumberLastWeek: function() { 
                axios.get('api/wallets/all/lastWeek/count')
                    .then(response=>{
                        this.allWalletsLastWeek = response.data;});
            },
            getAllWalletsBalanceLastWeek: function() { 
                axios.get('api/wallets/all/lastWeek/balance')
                    .then(response=>{
                        this.globalBalanceLastWeek = response.data;});
            },
            getAllMovementsNumberLastWeek: function() { 
                axios.get('api/movements/all/lastWeek/count')
                    .then(response=>{
                        this.allMovementsLastWeek = response.data;});
            },
            getAllUsersNumberLastMonth: function() {
                axios.get('api/users/all/lastMonth/count')
                    .then(response=>{
                        this.allUsersLastMonth = response.data;});
            },
            getAdminsNumberLastMonth: function() {
                axios.get('api/users/admins/lastMonth/count')
                    .then(response=>{
                        this.adminsLastMonth = response.data;});
            },
            getOperatorsNumberLastMonth: function() {
                axios.get('api/users/operators/lastMonth/count')
                    .then(response=>{
                        this.operatorsLastMonth = response.data;});
            },
            getUsersNumberLastMonth: function() { 
                axios.get('api/users/users/lastMonth/count')
                    .then(response=>{
                        this.usersLastMonth = response.data;});
            },
            getActivesNumberLastMonth: function() { 
                axios.get('api/users/active/lastMonth/count')
                    .then(response=>{
                        this.activeCountsLastMonth = response.data;});
            },
            getAllWalletsNumberLastMonth: function() { 
                axios.get('api/wallets/all/lastMonth/count')
                    .then(response=>{
                        this.allWalletsLastMonth = response.data;});
            },
            getAllWalletsBalanceLastMonth: function() { 
                axios.get('api/wallets/all/lastMonth/balance')
                    .then(response=>{
                        this.globalBalanceLastMonth = response.data;});
            },
            getAllMovementsNumberLastMonth: function() { 
                axios.get('api/movements/all/lastMonth/count')
                    .then(response=>{
                        this.allMovementsLastMonth = response.data;});
            },
            getAllUsersNumberLastYear: function() {
                axios.get('api/users/all/lastYear/count')
                    .then(response=>{
                        this.allUsersLastYear = response.data;});
            },
            getAdminsNumberLastYear: function() {
                axios.get('api/users/admins/lastYear/count')
                    .then(response=>{
                        this.adminsLastYear = response.data;});
            },
            getOperatorsNumberLastYear: function() {
                axios.get('api/users/operators/lastYear/count')
                    .then(response=>{
                        this.operatorsLastYear = response.data;});
            },
            getUsersNumberLastYear: function() { 
                axios.get('api/users/users/lastYear/count')
                    .then(response=>{
                        this.usersLastYear = response.data;});
            },
            getActivesNumberLastYear: function() { 
                axios.get('api/users/active/lastYear/count')
                    .then(response=>{
                        this.activeCountsLastYear = response.data;});
            },
            getAllWalletsNumberLastYear: function() { 
                axios.get('api/wallets/all/lastYear/count')
                    .then(response=>{
                        this.allWalletsLastYear = response.data;});
            },
            getAllWalletsBalanceLastYear: function() { 
                axios.get('api/wallets/all/lastYear/balance')
                    .then(response=>{
                        this.globalBalanceLastYear = response.data;});
            },
            getAllMovementsNumberLastYear: function() { 
                axios.get('api/movements/all/lastYear/count')
                    .then(response=>{
                        this.allMovementsLastYear = response.data;});
            },
            lastWeekData: function() {
                this.lastWeek = true;
                this.lastMonth = false;
                this.always = false;
                this.lastYear = false;
                this.getAllUsersNumberLastWeek();
                this.getAdminsNumberLastWeek();
                this.getOperatorsNumberLastWeek();
                this.getUsersNumberLastWeek();
                this.getActivesNumberLastWeek();
                this.getAllWalletsNumberLastWeek();
                this.getAllWalletsBalanceLastWeek();
                this.getAllMovementsNumberLastWeek();
            },
            lastMonthData: function() {
                this.lastMonth = true;
                this.always = false;
                this.lastWeek = false;
                this.lastYear = false;
                this.getAllUsersNumberLastMonth();
                this.getAdminsNumberLastMonth();
                this.getOperatorsNumberLastMonth();
                this.getUsersNumberLastMonth();
                this.getActivesNumberLastMonth();
                this.getAllWalletsNumberLastMonth();
                this.getAllWalletsBalanceLastMonth();
                this.getAllMovementsNumberLastMonth();
            },
            lastYearData: function() {
                this.lastMonth = false;
                this.always = false;
                this.lastWeek = false;
                this.lastYear = true;
                this.getAllUsersNumberLastYear();
                this.getAdminsNumberLastYear();
                this.getOperatorsNumberLastYear();
                this.getUsersNumberLastYear();
                this.getActivesNumberLastYear();
                this.getAllWalletsNumberLastYear();
                this.getAllWalletsBalanceLastYear();
                this.getAllMovementsNumberLastYear();
            },
            allData: function() {
                this.always = true;
                this.lastMonth = false;
                this.lastWeek = false;
                this.lastYear = false;
                this.getAllUsersNumber();
                this.getAdminsNumber();
                this.getOperatorsNumber();
                this.getUsersNumber();
                this.getActivesNumber();
                this.getAllWalletsNumber();
                this.getAllWalletsBalance();
                this.getAllMovementsNumber();
            },
        },
        mounted() {
            this.allData();
            console.log('Component Statistics mounted.')
        }
    }
</script>

 