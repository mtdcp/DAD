<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Since Always: {{ incomes }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Since Always: {{ expenses }} </h5><br><br>

        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [expensesByCat[1], expensesByCat[2], expensesByCat[3], expensesByCat[4], expensesByCat[5], expensesByCat[6], expensesByCat[7], expensesByCat[8], expensesByCat[9], expensesByCat[10], expensesByCat[11], expensesByCat[12], expensesByCat[13], expensesByCat[14], expensesByCat[15], expensesByCat[16], expensesByCat[17], expensesByCat[18], expensesByCat[19], expensesByCat[20], expensesByCat[21], expensesByCat[22], expensesByCat[23], expensesByCat[24], expensesByCat[25], expensesByCat[26], expensesByCat[27], expensesByCat[28], expensesByCat[29]],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#ff3278', '#eeb1a2', '#00BFFF', '#f0d2bc', '#40e0d0', '#ff0578', '#c07d87', '#ee4f00', '#ff0578', '#b852d2', '#314a8b', '#810600', '#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#054e79', '#FF0000', '#FFD700', '#ADFF2F', '#40E0D0'],
                    label: 'Expenses per Category',
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
            'expensesByCat',
            'incomes',
            'expenses'
        ],
        data() {
            return {
                title: 'Expenses per Category Since Always',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersExpensesCategory mounted.')
        }
    }
</script>