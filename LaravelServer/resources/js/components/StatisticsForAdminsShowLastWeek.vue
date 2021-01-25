<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">Global Balance Last Week: {{ globalBalanceLastWeek }}€ </h5><br>
        <h5 class="text-center font-weight-bold text-monospace">Total Movements Last Week: {{ allMovementsLastWeek }} </h5><br><br>
        <CChartBar
            style="height:300px"
            :datasets="[
                {
                    data: [allUsersLastWeek, adminsLastWeek, operatorsLastWeek, usersLastWeek, activeCountsLastWeek, allWalletsLastWeek],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99'],
                    label: 'Last Week',
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
            'usersLastWeek',
            'allUsersLastWeek',
            'allWalletsLastWeek',
            'operatorsLastWeek',
            'adminsLastWeek',
            'activeCountsLastWeek',
            'globalBalanceLastWeek',
            'allMovementsLastWeek'
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