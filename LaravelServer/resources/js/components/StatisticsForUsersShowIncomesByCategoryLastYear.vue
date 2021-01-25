<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Last Year: {{ incomesLastYearNr }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Last Year: {{ expensesLastYearNr }} </h5><br><br>

        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [incomesByCatLastYear[1], incomesByCatLastYear[2], incomesByCatLastYear[3], incomesByCatLastYear[4], incomesByCatLastYear[5], incomesByCatLastYear[6], incomesByCatLastYear[7], incomesByCatLastYear[8], incomesByCatLastYear[9], incomesByCatLastYear[10], incomesByCatLastYear[11], incomesByCatLastYear[12], incomesByCatLastYear[13], incomesByCatLastYear[14], incomesByCatLastYear[15], incomesByCatLastYear[16], incomesByCatLastYear[17], incomesByCatLastYear[18], incomesByCatLastYear[19], incomesByCatLastYear[20], incomesByCatLastYear[21], incomesByCatLastYear[22], incomesByCatLastYear[23], incomesByCatLastYear[24], incomesByCatLastYear[25], incomesByCatLastYear[26], incomesByCatLastYear[27], incomesByCatLastYear[28], incomesByCatLastYear[29]],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#ff3278', '#eeb1a2', '#00BFFF', '#f0d2bc', '#40e0d0', '#ff0578', '#c07d87', '#ee4f00', '#ff0578', '#b852d2', '#314a8b', '#810600', '#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#054e79', '#FF0000', '#FFD700', '#ADFF2F', '#40E0D0'],
                    label: 'Expenses per Category Last Month',
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
            'incomesByCatLastYear',
            'incomesLastYearNr',
            'expensesLastYearNr'
        ],
        data() {
            return {
                title: 'Incomes per Category Last Year',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersExpensesLastYearCategory mounted.')
        }
    }
</script>