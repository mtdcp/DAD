<template>
    <div>
        <br><br>

        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <button v-if="!incomeByCategory" title="Incomes per Category" class="btn btn-sm btn-info" v-on:click="incomePerCat()">Incomes per Category</button>
        <button v-if="!expenseByCategory" title="Expenses per Category" class="btn btn-sm btn-info" v-on:click="expensePerCat()">Expenses per Category</button>
        <button v-if="!expensesLastWeek" title="Expenses per Category Last Week" class="btn btn-sm btn-info" v-on:click="expensePerCatLastWeek()">Expenses per Category Last Week</button>
        <button v-if="!incomesLastWeek" title="Incomes per Category Last Week" class="btn btn-sm btn-info" v-on:click="incomePerCatLastWeek()">Incomes per Category Last Week</button>
        <button v-if="!expensesLastMonth"title="Expenses per Category Last Month" class="btn btn-sm btn-info" v-on:click="expensePerCatLastMonth()">Expenses per Category Last Month</button><br><br>
        <button v-if="!incomesLastMonth" title="Incomes per Category Last Month" class="btn btn-sm btn-info" v-on:click="incomePerCatLastMonth()">Incomes per Category Last Month</button> 
        <button v-if="!expensesLastYear" title="Expenses per Category Last Year" class="btn btn-sm btn-info" v-on:click="expensePerCatLastYear()">Expenses per Category Last Year</button>
        <button v-if="!incomesLastYear" title="Incomes per Category Last Year" class="btn btn-sm btn-info" v-on:click="incomePerCatLastYear()">Incomes per Category Last Year</button>  <br><br><br>

        <h5 v-if="begin" class="text-center font-weight-bold text-monospace" style="color:green;">Incomes Since Always: {{ incomes }} </h5><br>
        <h5 v-if="begin" class="text-center font-weight-bold text-monospace" style="color:red;">Expenses Since Always: {{ expenses }} </h5>

        <stats-users-income-cat-show v-if="incomeByCategory" :categories="categories" :incomesByCat="incomesByCat" :expenses="expenses" :incomes="incomes"></stats-users-income-cat-show>

        <stats-users-expense-cat-show v-if="expenseByCategory" :categories="categories" :expensesByCat="expensesByCat" :expenses="expenses" :incomes="incomes"></stats-users-expense-cat-show>

        <stats-users-expense-cat-show-week v-if="expensesLastWeek" :categories="categories" :expensesLastWeekNr="expensesLastWeekNr" :incomesLastWeekNr="incomesLastWeekNr" :expensesByCatLastWeek="expensesByCatLastWeek" ></stats-users-expense-cat-show-week>

        <stats-users-income-cat-show-week v-if="incomesLastWeek" :categories="categories" :expensesLastWeekNr="expensesLastWeekNr" :incomesLastWeekNr="incomesLastWeekNr" :incomesByCatLastWeek="incomesByCatLastWeek"></stats-users-income-cat-show-week>

        <stats-users-expense-cat-show-month v-if="expensesLastMonth" :categories="categories" :expensesLastMonthNr="expensesLastMonthNr" :incomesLastMonthNr="incomesLastMonthNr" :expensesByCatLastMonth="expensesByCatLastMonth"></stats-users-expense-cat-show-month>

        <stats-users-income-cat-show-month v-if="incomesLastMonth" :categories="categories" :expensesLastMonthNr="expensesLastMonthNr" :incomesLastMonthNr="incomesLastMonthNr" :incomesByCatLastMonth="incomesByCatLastMonth"></stats-users-income-cat-show-month>

        <stats-users-expense-cat-show-year v-if="expensesLastYear" :categories="categories" :expensesLastYearNr="expensesLastYearNr" :incomesLastYearNr="incomesLastYearNr" :expensesByCatLastYear="expensesByCatLastYear"></stats-users-expense-cat-show-year>

        <stats-users-income-cat-show-year v-if="incomesLastYear" :categories="categories" :expensesLastYearNr="expensesLastYearNr" :incomesLastYearNr="incomesLastYearNr" :incomesByCatLastYear="incomesByCatLastYear"></stats-users-income-cat-show-year>
    </div>
</template>

<script>
    window.Vue = require('vue');
    import StatisticsShowUsersIncomeCategoryComponent from './StatisticsForUsersShowIncomesByCategory.vue';
    import StatisticsShowUsersExpenseCategoryComponent from './StatisticsForUsersShowExpensesByCategory.vue';
    import StatisticsShowUsersExpenseLastWeekCategoryComponent from './StatisticsForUsersShowExpensesByCategoryLastWeek.vue';
    import StatisticsShowUsersIncomeLastWeekCategoryComponent from './StatisticsForUsersShowIncomesByCategoryLastWeek.vue';
    import StatisticsShowUsersExpenseLastMonthCategoryComponent from './StatisticsForUsersShowExpensesByCategoryLastMonth.vue';
    import StatisticsShowUsersIncomeLastMonthCategoryComponent from './StatisticsForUsersShowIncomesByCategoryLastMonth.vue';
    import StatisticsShowUsersExpenseLastYearCategoryComponent from './StatisticsForUsersShowExpensesByCategoryLastYear.vue';
    import StatisticsShowUsersIncomeLastYearCategoryComponent from './StatisticsForUsersShowIncomesByCategoryLastYear.vue';

    import { CChartDoughnut } from '@coreui/coreui-vue-chartjs';
    Vue.component('CChartDoughnut', CChartDoughnut);

    export default {
        data () {
            return {
                expenses: 0,
                incomes: 0,
                expensesLastWeekNr: 0,
                incomesLastWeekNr: 0,
                expensesLastMonthNr: 0,
                incomesLastMonthNr: 0,
                expensesLastYearNr: 0,
                incomesLastYearNr: 0,
                begin: true,
                incomeByCategory: false,
                expenseByCategory: false,
                expensesLastWeek: false,
                incomesLastWeek: false,
                expensesLastMonth: false,
                incomesLastMonth: false,
                expensesLastYear: false,
                incomesLastYear: false,
                categories: [],
                incomesByCat: [],
                expensesByCat: [],
                incomesByCatLastWeek: [],
                expensesByCatLastWeek: [],
                incomesByCatLastMonth: [],
                expensesByCatLastMonth: [],
                incomesByCatLastYear: [],
                expensesByCatLastYear: [],
                title: 'Your Wallet Statistics',
            }
        },
        components: {
            'stats-users-income-cat-show': StatisticsShowUsersIncomeCategoryComponent,
            'stats-users-expense-cat-show': StatisticsShowUsersExpenseCategoryComponent,
            'stats-users-expense-cat-show-week': StatisticsShowUsersExpenseLastWeekCategoryComponent,
            'stats-users-income-cat-show-week': StatisticsShowUsersIncomeLastWeekCategoryComponent,
            'stats-users-expense-cat-show-month': StatisticsShowUsersExpenseLastMonthCategoryComponent,
            'stats-users-income-cat-show-month': StatisticsShowUsersIncomeLastMonthCategoryComponent,
            'stats-users-expense-cat-show-year': StatisticsShowUsersExpenseLastYearCategoryComponent,
            'stats-users-income-cat-show-year': StatisticsShowUsersIncomeLastYearCategoryComponent,
        },
        methods: {
            getIncomesData() {
                axios.get('api/movements/income/' + this.$store.state.user.id + '/count')
                    .then(response=>{
                        this.incomes = response.data;});
            },
            getExpensesData() {
                axios.get('api/movements/expense/' + this.$store.state.user.id + '/count')
                    .then(response=>{
                        this.expenses = response.data;});
            },
            getIncomesLastWeekData() {
                axios.get('api/movements/income/' + this.$store.state.user.id + '/count/lastWeek')
                    .then(response=>{
                        this.incomesLastWeekNr = response.data;});
            },
            getExpensesLastWeekData() {
                axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/lastWeek')
                    .then(response=>{
                        this.expensesLastWeekNr = response.data;});
            },
            getIncomesLastMonthData() {
                axios.get('api/movements/income/' + this.$store.state.user.id + '/count/lastMonth')
                    .then(response=>{
                        this.incomesLastMonthNr = response.data;});
            },
            getExpensesLastMonthData() {
                axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/lastMonth')
                    .then(response=>{
                        this.expensesLastMonthNr = response.data;});
            },
            getIncomesLastYearData() {
                axios.get('api/movements/income/' + this.$store.state.user.id + '/count/lastYear')
                    .then(response=>{
                        this.incomesLastYearNr = response.data;});
            },
            getExpensesLastYearData() {
                axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/lastYear')
                    .then(response=>{
                        this.expensesLastYearNr = response.data;});
            },
            getIncomesByCategory() {
                for(var category in this.categories) {
                    axios.get('api/movements/income/' + this.$store.state.user.id + '/count/' + category)
                    .then(response=>{
                        this.incomesByCat.push(response.data);
                    });
                }
            },
            getExpensesByCategory() {
                for(var category in this.categories) {
                    axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/' + category)
                    .then(response=>{
                        this.expensesByCat.push(response.data);
                    });
                }
            },
            getIncomesByCategoryLastWeek() {
                for(var category in this.categories) {
                    axios.get('api/movements/income/' + this.$store.state.user.id + '/count/' + category + '/lastWeek')
                    .then(response=>{
                        this.incomesByCatLastWeek.push(response.data);
                    });
                }
            },
            getExpensesByCategoryLastMonth() {
                for(var category in this.categories) {
                    axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/' + category + '/lastMonth')
                    .then(response=>{
                        this.expensesByCatLastMonth.push(response.data);
                    });
                }
            },
            getIncomesByCategoryLastMonth() {
                for(var category in this.categories) {
                    axios.get('api/movements/income/' + this.$store.state.user.id + '/count/' + category + '/lastMonth')
                    .then(response=>{
                        this.incomesByCatLastMonth.push(response.data);
                    });
                }
            },
            getExpensesByCategoryLastYear() {
                for(var category in this.categories) {
                    axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/' + category + '/lastYear')
                    .then(response=>{
                        this.expensesByCatLastYear.push(response.data);
                    });
                }
            },
            getIncomesByCategoryLastYear() {
                for(var category in this.categories) {
                    axios.get('api/movements/income/' + this.$store.state.user.id + '/count/' + category + '/lastYear')
                    .then(response=>{
                        this.incomesByCatLastYear.push(response.data);
                    });
                }
            },
            getExpensesByCategoryLastWeek() {
                for(var category in this.categories) {
                    axios.get('api/movements/expense/' + this.$store.state.user.id + '/count/' + category + '/lastWeek')
                    .then(response=>{
                        this.expensesByCatLastWeek.push(response.data);
                    });
                }
            },
            getCategories: function() {
                axios.get('api/categories')
                .then(response=>{
                    var idx = 0;
                    for(var name in response.data) {
                        this.categories.push(response.data[idx].name);
                        idx++;
                    }
                           
                })
                .catch(error=> {
                    console.log(error);
                });   
            },
            incomePerCat: function() {
                this.incomeByCategory = true;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getIncomesByCategory();
            },
            expensePerCat: function() {
                this.incomeByCategory = false;
                this.expenseByCategory = true;
                this.expensesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getExpensesByCategory();
            },
            expensePerCatLastWeek: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = true;
                this.incomesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getExpensesByCategoryLastWeek();
            },
            incomePerCatLastWeek: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = true;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getIncomesByCategoryLastWeek();
            },
            expensePerCatLastMonth: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= true;
                this.expensesLastYear= false;
                this.begin = false;
                this.getExpensesByCategoryLastMonth();
            },
            incomePerCatLastMonth: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = false;
                this.incomesLastMonth = true;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getIncomesByCategoryLastMonth();
            },
            expensePerCatLastYear: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = false;
                this.expensesLastMonth= false;
                this.expensesLastYear= true;
                this.begin = false;
                this.getExpensesByCategoryLastYear();
            },
            incomePerCatLastYear: function () {
                this.incomeByCategory = false;
                this.expenseByCategory = false;
                this.expensesLastWeek = false;
                this.incomesLastWeek = false;
                this.incomesLastMonth = false;
                this.incomesLastYear = true;
                this.expensesLastMonth= false;
                this.expensesLastYear= false;
                this.begin = false;
                this.getIncomesByCategoryLastYear();
            }
        },
        sockets: {
            income_movement_added (destUser) {
                this.$toasted.show( 'New income movement added to your virtual wallet!' );
            },
            movement_added (fromUser) {
                this.$toasted.show( 'New movement added to your virtual wallet - ' + fromUser +' made a transfer to you' );
            }
        },
        mounted() {
            this.getIncomesData();
            this.getExpensesData();
            this.getIncomesLastWeekData();
            this.getExpensesLastWeekData();
            this.getIncomesLastMonthData();
            this.getExpensesLastMonthData();
            this.getIncomesLastYearData();
            this.getExpensesLastYearData();
            this.getCategories();
            console.log('Component StatisticsUsers mounted.')
        }
    }
</script>

 