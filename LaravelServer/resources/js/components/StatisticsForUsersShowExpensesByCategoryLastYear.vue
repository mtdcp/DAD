<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Last Year: {{ incomesLastYearNr }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Last Year: {{ expensesLastYearNr }} </h5><br><br>

        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [expensesByCatLastYear[1], expensesByCatLastYear[2], expensesByCatLastYear[3], expensesByCatLastYear[4], expensesByCatLastYear[5], expensesByCatLastYear[6], expensesByCatLastYear[7], expensesByCatLastYear[8], expensesByCatLastYear[9], expensesByCatLastYear[10], expensesByCatLastYear[11], expensesByCatLastYear[12], expensesByCatLastYear[13], expensesByCatLastYear[14], expensesByCatLastYear[15], expensesByCatLastYear[16], expensesByCatLastYear[17], expensesByCatLastYear[18], expensesByCatLastYear[19], expensesByCatLastYear[20], expensesByCatLastYear[21], expensesByCatLastYear[22], expensesByCatLastYear[23], expensesByCatLastYear[24], expensesByCatLastYear[25], expensesByCatLastYear[26], expensesByCatLastYear[27], expensesByCatLastYear[28], expensesByCatLastYear[29]],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#ff3278', '#eeb1a2', '#00BFFF', '#f0d2bc', '#40e0d0', '#ff0578', '#c07d87', '#ee4f00', '#ff0578', '#b852d2', '#314a8b', '#810600', '#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#054e79', '#FF0000', '#FFD700', '#ADFF2F', '#40E0D0'],
                    label: 'Expenses per Category Last Year',
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
            'expensesByCatLastYear',
            'incomesLastYearNr',
            'expensesLastYearNr'
        ],
        data() {
            return {
                title: 'Expenses per Category Last Year',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersExpensesLastMonthCategory mounted.')
        }
    }
</script>