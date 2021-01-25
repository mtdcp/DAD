<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Last Week: {{ incomesLastWeekNr }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Last Week: {{ expensesLastWeekNr }} </h5><br><br>

        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [expensesByCatLastWeek[1], expensesByCatLastWeek[2], expensesByCatLastWeek[3], expensesByCatLastWeek[4], expensesByCatLastWeek[5], expensesByCatLastWeek[6], expensesByCatLastWeek[7], expensesByCatLastWeek[8], expensesByCatLastWeek[9], expensesByCatLastWeek[10], expensesByCatLastWeek[11], expensesByCatLastWeek[12], expensesByCatLastWeek[13], expensesByCatLastWeek[14], expensesByCatLastWeek[15], expensesByCatLastWeek[16], expensesByCatLastWeek[17], expensesByCatLastWeek[18], expensesByCatLastWeek[19], expensesByCatLastWeek[20], expensesByCatLastWeek[21], expensesByCatLastWeek[22], expensesByCatLastWeek[23], expensesByCatLastWeek[24], expensesByCatLastWeek[25], expensesByCatLastWeek[26], expensesByCatLastWeek[27], expensesByCatLastWeek[28], expensesByCatLastWeek[29]],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#ff3278', '#eeb1a2', '#00BFFF', '#f0d2bc', '#40e0d0', '#ff0578', '#c07d87', '#ee4f00', '#ff0578', '#b852d2', '#314a8b', '#810600', '#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#054e79', '#FF0000', '#FFD700', '#ADFF2F', '#40E0D0'],
                    label: 'Expenses per Category Last Week',
                }
            ]"
            :labels="labels"
            :options="{ maintainAspectRatio: false }"
        />
    </div>
</template>

<script>
    export default {
        props: [
            'categories',
            'expensesByCatLastWeek',
            'incomesLastWeekNr',
            'expensesLastWeekNr'
        ],
        data() {
            return {
                title: 'Expenses per Category Last Week',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersExpensesLastWeekCategory mounted.')
        }
    }
</script>