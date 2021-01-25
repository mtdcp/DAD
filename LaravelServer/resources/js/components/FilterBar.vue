<template>
    <div class="filter-bar pull-center">
        <div class="form-inline">
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterText">Search by name:</label>&nbsp&nbsp
                <input type="text" v-model="filterText.filterName" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="name">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div>
            <div class="form-group">
                <label for="filterText">Search by email:</label>&nbsp&nbsp
                <input type="text" v-model="filterText.filterEmail" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="email">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div><br><br><br><br>
            <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <label for="filterText">Search by type:</label>&nbsp&nbsp
                <input type="text" v-model="filterText.filterType" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="type">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </div>
            <div class="form-group">
                <label for="filterText">Search by status:</label>&nbsp&nbsp
                <input type="text" v-model="filterText.filterStatus" class="form-control" style="width: 300px;" @keyup.enter="doFilter" placeholder="status">&nbsp&nbsp&nbsp&nbsp
            </div>
            <button title="Search" class="btn btn-primary"  @click="doFilter">Go</button>
            <button title="Get all users" class="btn btn-default" @click="resetFilter">Reset</button>
        </div>
    </div>
</template>

<script>
    export default {
      data () {
        return {
          filterText: {
            filterName: '',
            filterEmail: '',
            filterType: '',
            filterStatus: ''
          }
        }
      },
      methods: {
        doFilter () {
          this.$events.fire('filter-set', this.filterText)
        },
        resetFilter () {
          this.filterText.filterName = '', 
          this.filterText.filterEmail = '',
          this.filterText.filterType = '',
          this.filterText.filterStatus = ''
          this.$events.fire('filter-reset')
        }
      }
    }
</script>

<style>
    .filter-bar {
      margin-bottom: 8px;
    }
</style>