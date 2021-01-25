<template>
    <div class="filter-bar-movements pull-center">
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>

        <div class="form-inline">
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterTextMovements">Search by id:</label>&nbsp&nbsp
                <input type="text" v-model="filterTextMovements.filterIdMovements" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="ID">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterTextMovements">Search by type:</label>&nbsp&nbsp
                <input type="text" v-model="filterTextMovements.filterTypeMovements" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="Type">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div><br><br><br>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterTextMovements">Search by category:</label>&nbsp&nbsp
                <input type="text" v-model="filterTextMovements.filterCategoryMovements" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="Category">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterTextMovements">Search by payment type:</label>&nbsp&nbsp
                <input type="text" v-model="filterTextMovements.filterTypePaymentMovements" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="Payment type">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div><br><br><br>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterTextMovements">Search by source/destination transfer email:</label>&nbsp&nbsp
                <input type="text" v-model="filterTextMovements.filterEmailMovements" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="Source/Destination transfer email">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div><br><br>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="date1">Start date:</label>&nbsp&nbsp
                    <input type="date" class="form-control" id="date1" v-model="filterTextMovements.filterStartDateMovements" style="width: 300px;"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div><br><br><br><br>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="date1">End date:</label>&nbsp&nbsp
                    <input type="date" class="form-control" id="date2" v-model="filterTextMovements.filterEndDateMovements" style="width: 300px;" @keyup.enter="doFilter">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <button title="Search" class="btn btn-primary"  @click="doFilter">Go</button>
            <button title="Get all movements" class="btn btn-default" @click="resetFilter">Reset</button>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                showFailure: false,
                failMessage: '',
                filterTextMovements: {
                    filterIdMovements: '',
                    filterTypeMovements: '',
                    filterCategoryMovements: '',
                    filterTypePaymentMovements: '',
                    filterEmailMovements: '',
                    filterStartDateMovements: '',
                    filterEndDateMovements: ''
                }
            }
        },
        methods: {
            doFilter () {
                if(this.filterTextMovements.filterStartDateMovements != '' && this.filterTextMovements.filterEndDateMovements == '') {
                    this.showFailure = true;
                    this.failMessage = 'End date missing!'
                } else if(this.filterTextMovements.filterStartDateMovements == '' && this.filterTextMovements.filterEndDateMovements != '') {
                    this.showFailure = true;
                    this.failMessage = 'Start date missing!'
                } else {
                    this.showFailure = false;
                    this.$events.fire('filter-movements-set', this.filterTextMovements)
                }
            },
            resetFilter () {
                this.filterTextMovements.filterIdMovements = '',
                    this.filterTextMovements.filterTypeMovements = '',
                    this.filterTextMovements.filterCategoryMovements = '',
                    this.filterTextMovements.filterTypePaymentMovements = '',
                    this.filterTextMovements.filterEmailMovements = '',
                    this.filterTextMovements.filterStartDateMovements = '',
                    this.filterTextMovements.filterEndDateMovements = '',
                this.$events.fire('filter-movements-reset')
                this.showFailure = false;
            },
        },
    }
</script>

<style>
    .filter-bar-movements {
        margin-bottom: 8px;
    }
</style>
