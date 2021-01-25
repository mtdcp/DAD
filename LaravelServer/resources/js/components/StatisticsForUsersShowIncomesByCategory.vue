<template>
    <div>
        <h5 class="text-center font-weight-bold text-monospace">{{ title }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Since Always: {{ incomes }} </h5><br>
        <h5 class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Since Always: {{ expenses }} </h5><br><br>
        <CChartDoughnut 
            style="height:500px"
            :datasets="[
                {
                    data: [incomesByCat[1], incomesByCat[2], incomesByCat[3], incomesByCat[4], incomesByCat[5], incomesByCat[6], incomesByCat[7], incomesByCat[8], incomesByCat[9], incomesByCat[10], incomesByCat[11], incomesByCat[12], incomesByCat[13], incomesByCat[14], incomesByCat[15], incomesByCat[16], incomesByCat[17], incomesByCat[18], incomesByCat[19], incomesByCat[20], incomesByCat[21], incomesByCat[22], incomesByCat[23], incomesByCat[24], incomesByCat[25], incomesByCat[26], incomesByCat[27], incomesByCat[28], incomesByCat[29]],
                    backgroundColor: ['#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#ff3278', '#eeb1a2', '#00BFFF', '#f0d2bc', '#40e0d0', '#ff0578', '#c07d87', '#ee4f00', '#ff0578', '#b852d2', '#314a8b', '#810600', '#45b7cd', '#ff6384', '#FF7F00', '#99CC32', '#FFFF00', '#32CD99', '#054e79', '#FF0000', '#FFD700', '#ADFF2F', '#40E0D0'],
                    label: 'Incomes per Category',
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
            'incomesByCat',
            'incomes',
            'expenses'
        ],
        data() {
            return {
                title: 'Incomes per Category Since Always',
                labels: [this.categories[1], this.categories[2], this.categories[3], this.categories[4], this.categories[5], this.categories[6], this.categories[7], this.categories[8], this.categories[9], this.categories[10], this.categories[11], this.categories[12], this.categories[13], this.categories[14], this.categories[15], this.categories[16], this.categories[17], this.categories[18], this.categories[19], this.categories[20], this.categories[21], this.categories[22], this.categories[23], this.categories[24], this.categories[25], this.categories[26], this.categories[27], this.categories[28]]
            }
        },
        mounted() {
            console.log('Component StatisticsUsersIncomesCategory mounted.')
        }
    }
</script>