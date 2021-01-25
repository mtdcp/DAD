<template>

    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Last Week: {{ incomesLastWeekNr }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Last Week: {{ expensesLastWeekNr }} </h5><br><br>

        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [incomesByCatLastWeek[1], incomesByCatLastWeek[2], incomesByCatLastWeek[3], incomesByCatLastWeek[4], incomesByCatLastWeek[5], incomesByCatLastWeek[6], incomesByCatLastWeek[7], incomesByCatLastWeek[8], incomesByCatLastWeek[9], incomesByCatLastWeek[10], incomesByCatLastWeek[11], incomesByCatLastWeek[12], incomesByCatLastWeek[13], incomesByCatLastWeek[14], incomesByCatLastWeek[15], incomesByCatLastWeek[16], incomesByCatLastWeek[17], incomesByCatLastWeek[18], incomesByCatLastWeek[19], incomesByCatLastWeek[20], incomesByCatLastWeek[21], incomesByCatLastWeek[22], incomesByCatLastWeek[23], incomesByCatLastWeek[24], incomesByCatLastWeek[25], incomesByCatLastWeek[26], incomesByCatLastWeek[27], incomesByCatLastWeek[28], incomesByCatLastWeek[29]],
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
            'incomesByCatLastWeek',
            'incomesLastWeekNr',
            'expensesLastWeekNr'
        ],
        data() {
            return {
                title: 'Incomes per Category Last Week',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersExpensesLastWeekCategory mounted.')
        }
    }
</script>