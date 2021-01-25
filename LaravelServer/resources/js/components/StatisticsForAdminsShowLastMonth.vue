<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">Global Balance Last Month: {{ globalBalanceLastMonth }}€ </h5><br>
        <h5 class="text-center font-weight-bold text-monospace">Total Movements Last Month: {{ allMovementsLastMonth }} </h5><br><br>
        <CChartBar
            style="height:300px"
            :datasets="[
                {
                    data: [allUsersLastMonth, adminsLastMonth, operatorsLastMonth, usersLastMonth, activeCountsLastMonth, allWalletsLastMonth],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99'],
                    label: 'Last Month',
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
        'usersLastMonth',
        'allUsersLastMonth',
        'allWalletsLastMonth',
        'operatorsLastMonth',
        'adminsLastMonth',
        'activeCountsLastMonth',
        'globalBalanceLastMonth',
        'allMovementsLastMonth'
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