<template>
    <div>
        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>

            <filter-bar></filter-bar><br><br>
                <vuetable ref="vuetable"
                    api-url="api/users"
                    :fields="columns"
                    :http-fetch="myFetch"
                    pagination-path="meta"
                    :filterable="true"
                    data-path="data"
                    :css="css.table"
                    preserveState="true"
                    saveState="true"
                    :sort-order="sortOrder"
                    :multi-sort="true"
                    :append-params="moreParams"
                    filterByColumn="true"
                    :sortable="true"
                    :per-page="5"
                    :show-sort-icons="true"
                    @vuetable:pagination-data="onPaginationData">

                    <template slot="photo" slot-scope="props">
                        <img v-if="props.rowData.photo != null" :src="`${props.rowData.photo}`" width="128" height="128"/>
                        <img v-else src="https://assets.boredpanda.com/blog/wp-content/themes/boredpanda/images/default-user-profile-image.png" width="128" height="128"/>
                    </template>


                    <template slot="actions" slot-scope="props">
                        <div class="custom-actions">
                            <button title="Desactivate this user" class="btn btn-sm btn-warning" v-if="props.rowData.balance == 'Balance = 0'" @click="deactivateUser(props.rowData)">Desactivate</button>
                            <button title="Activate this user" class="btn btn-sm btn-success" v-if="props.rowData.balance == 'Balance = 0'" @click="activateUser(props.rowData)">Activate</button>
                            <button title="Delete this user" class="btn btn-sm btn-danger" v-if="props.rowData.balance == 'Don\'t have wallet'" @click="deleteUser(props.rowData)">Delete</button>
                        </div>
                    </template>

                </vuetable>
                
                <div class="vuetable-pagination">
                    <vuetable-pagination-info ref="paginationInfo" info-class="pagination-info"></vuetable-pagination-info>
                    <vuetable-pagination ref="pagination" :css="css.pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
                </div>  
                <br><br><br>
        </div>   
</template>

<script>
    import Vue from 'vue';

    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';
    Vue.use(Vuetable);

    import FilterBar from './FilterBar.vue';
    import VueEvents from 'vue-events';
    Vue.use(VueEvents);

    export default {
        data() {
            return {
                showFailure: false,
                failMessage: '',
                columns: [
                    {
                        title: 'Name',
                        name: 'name',
                        sortField: 'name',
                        dataClass: 'center aligned',
                        sortable: true
                    }, 
                    {
                        title: 'Email',
                        name: 'email',
                        sortField: 'email',
                        dataClass: 'center aligned',
                        sortable: true
                    }, 
                    {
                        title: 'Type',
                        name: 'type',
                        sortField: 'type',
                        dataClass: 'center aligned',
                        sortable: true
                    },
                    {
                        title: 'Status',
                        name: 'active',
                        sortField: 'active',
                        dataClass: 'center aligned',
                        sortable: true
                    },
                    {
                        title: 'Photo',
                        name: 'photo',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'Wallet Balance',
                        name: 'balance',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'Actions',
                        name: 'actions',
                    },
                ],
                sortOrder: [
                    { field: 'name', direction: 'asc'}
                ],
                css: {
                    table: {
                        tableClass: 'table table-borderedless table-outlined',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down',
                        handleIcon: 'glyphicon glyphicon-menu-hamburger'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'page',
                        linkClass: 'link',
                        icons: {
                            first: '',
                            prev: '',
                            next: '',
                            last: '',
                        },
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'glyphicon glyphicon-chevron-left',
                        next: 'glyphicon glyphicon-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    }
                },
                moreParams: {

                }
            }
        },
        props: [
            "wallets"
        ],
        components: {
            'vuetable': Vuetable,
            'vuetable-pagination': VuetablePagination,
            'vuetable-pagination-info': VuetablePaginationInfo,
            'filter-bar': FilterBar
        },
        methods: {
            myFetch(apiUrl, httpOptions) {
                return axios.get(apiUrl, httpOptions)
            },
            deleteUser: function(user) {
                this.$emit('delete-click', user); 
                Vue.nextTick( () => this.$refs.vuetable.refresh() );
            },
            deactivateUser: function(user) {
                if(user.active == 'Not Active') {
                    this.showFailure = true;
                    this.failMessage = "User is already not active";
                } else {
                    this.$emit('desact-click', user);
                    Vue.nextTick( () => this.$refs.vuetable.refresh() );
                }
            },
            activateUser: function(user) {
                if(user.active == 'Active') {
                    this.showFailure = true;
                    this.failMessage = "User is already active";
                } else {
                    this.$emit('act-click', user);
                    Vue.nextTick( () => this.$refs.vuetable.refresh() );
                }
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData);
                this.$refs.paginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page);
            },
            onFilterSet (filterText) { 
                this.moreParams.filterName = filterText.filterName.charAt(0).toUpperCase() + filterText.filterName.slice(1),
                this.moreParams.filterEmail = filterText.filterEmail.charAt(0).toUpperCase() + filterText.filterEmail.slice(1),
                this.moreParams.filterType = filterText.filterType.charAt(0).toUpperCase() + filterText.filterType.slice(1),
                this.moreParams.filterStatus = filterText.filterStatus.charAt(0).toUpperCase() + filterText.filterStatus.slice(1)

                Vue.nextTick( () => this.$refs.vuetable.refresh());
            },
            onFilterReset () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.vuetable.refresh());
            }
        },
         sockets: {
            income_movement_added_special () {
                this.$toasted.show( 'New income movement added to a user virtual wallet!' );
            },
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
            this.$events.$on('filter-reset', eventData => this.onFilterReset());
            console.log('Component UserList mounted.')
        }
    }
</script>

<style>
    .pagination {
        margin: 0;
        float: right;
    }
    .pagination a.page {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.page.active {
        color: white;
        background-color: #337ab7;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.btn-nav {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }
    .pagination a.btn-nav.disabled {
        color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }
    .pagination-info {
        float: left;
    }
    .custom-actions button.ui.button {
        padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
        margin: auto !important;
    }
</style>