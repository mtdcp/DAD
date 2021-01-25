<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">Global Balance Last Year: {{ globalBalanceLastYear }}€ </h5><br>
        <h5 class="text-center font-weight-bold text-monospace">Total Movements Last Year: {{ allMovementsLastYear }} </h5><br><br>
        <CChartBar
            style="height:300px"
            :datasets="[
                {
                     data: [allUsersLastYear, adminsLastYear, operatorsLastYear, usersLastYear, activeCountsLastYear, allWalletsLastYear],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99'],
                    label: 'Last Year',
                }
            ]"
            :labels="labels"
            :options="{ maintainAspectRatio: false }"
        /><br><br>
    </div>
</template>

<script>
    window.Vue = require('vue');

    export default {
        props: [
            'usersLastYear',
            'allUsersLastYear',
            'allWalletsLastYear',
            'operatorsLastYear',
            'adminsLastYear',
            'activeCountsLastYear',
            'globalBalanceLastYear',
            'allMovementsLastYear'
        ],
        data() {
            return {
                labels: ['Accounts', 'Administrators', 'Operators', 'Users', 'Active Accounts', 'Wallets']
            }
        },
        mounted() {
            console.log('Component StatisticsShowLastWeek mounted.')
        }
    }
</script>