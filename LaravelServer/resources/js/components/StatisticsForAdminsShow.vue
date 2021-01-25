<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">Global Balance: {{ globalBalance }}€ </h5><br>
        <h5 class="text-center font-weight-bold text-monospace">Total Movements: {{ allMovements }} </h5><br><br>
        <CChartBar
            style="height:300px"
            :datasets="[
                {
                    data: [allUsers, admins, operators, users, activeCounts, allWallets],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99'],
                    label: 'Since always',
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
            'allUsers',
            'admins',
            'operators',
            'users',
            'activeCounts',
            'allWallets',
            'allMovements',
            'globalBalance'
        ],
        data() {
            return {
                labels: ['Accounts', 'Administrators', 'Operators', 'Users', 'Active Accounts', 'Wallets']
            }
        },
        mounted() {
            console.log('Component StatisticsShow mounted.')
        }
    }
</script>