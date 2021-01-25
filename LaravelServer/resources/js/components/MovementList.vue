<template>
    <div>
        <movement-create v-if="creatingMovement" @movement-created="movementCreation" @movement-canceled="cancelCreation"></movement-create>

        <div class="alert alert-danger" v-if="showFailure">
            <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
            <strong>{{ failMessage }}</strong>
        </div>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <filter-bar-movements></filter-bar-movements>
        <br><br>
        <vuetable ref="vuetableMovements"
                  :api-url="apiURL"
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

            <template slot="actions" slot-scope="props">
                <div class="custom-actions">
                    <button title="More Details" class="btn btn-sm btn-info" @click="moreDetails(props.rowData)">More Details</button>
                    <button title="Edit movement" class="btn btn-sm btn-warning" @click="editMovement(props.rowData)">Edit Movement</button>
                </div>
            </template>
        </vuetable>

        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="paginationInfo" info-class="pagination-info"></vuetable-pagination-info>
            <vuetable-pagination ref="pagination" :css="css.pagination"
                                 @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
        </div>

        <br><br><br><br><br><br> 
        <movement-edit v-if="editingMovement" :movementToEdit="movementToEdit" @save-movement="saveMovement" @cancel-editing="cancel" @fill-spinner="fillSpinner" :categoriesList="categoriesList"></movement-edit>
    </div>
</template>

<script>
    import Vue from 'vue';

    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';

    import MovementEdit from './MovementEdit.vue';
    import MovementCreateComponent from './MovementCreate.vue';

    Vue.use(Vuetable);

    import FilterBarMovements from './FilterBarMovements.vue';
    import VueEvents from 'vue-events';
    Vue.use(VueEvents);

    export default {
        data() {
            return {
                showFailure: false,
                failMessage: '',
                editingMovement: false,
                categoriesList: [],
                movementToEdit: '',
                showSuccess: false,
                successMessage: '',
                apiURL: "api/movements/" + this.$store.state.user.id,
                columns: [
                    {
                        title: 'ID',
                        name: 'id',
                        dataClass: 'center aligned'
                    },
                    {
                        title: 'Type',
                        name: 'type',
                        dataClass: 'center aligned'
                    },
                    {
                        title: 'Destination Email',
                        name: 'email',
                        dataClass: 'center aligned'
                    },
                    {
                        title: 'Payment type',
                        name: 'type_payment',
                        dataClass: 'center aligned'
                    },
                    {
                        title: 'Category',
                        name: 'category_id',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'Date',
                        name: 'date',
                        sortField: 'date',
                        dataClass: 'center aligned',
                        sortable: true
                    },
                    {
                        title: 'Value',
                        name: 'value',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'Start Balance',
                        name: 'start_balance',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'End Balance',
                        name: 'end_balance',
                        dataClass: 'center aligned',
                    },
                    {
                        title: 'Actions',
                        name: 'actions',
                    },
                ],
                sortOrder: [
                    {field: 'date', direction: 'desc'}
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
                moreParams: {}
            }
        },
        props: [
            "creatingMovement"
        ],
        components: {
            'vuetable': Vuetable,
            'vuetable-pagination': VuetablePagination,
            'vuetable-pagination-info': VuetablePaginationInfo,
            'filter-bar-movements': FilterBarMovements,
            'movement-edit': MovementEdit,
            'movement-create': MovementCreateComponent
        },
        methods: {
            myFetch(apiUrl, httpOptions) {
                return axios.get(apiUrl, httpOptions)
            },
            editMovement: function(movement) {
                this.editingMovement = true;
                this.movementToEdit = movement;
                this.fillSpinner();
            },
            fillSpinner: function() {
                axios.get('api/categories')
                .then(response=>{
                    var idx = 0;
                    for(var name in response.data) {
                        this.categoriesList.push(response.data[idx].name);
                        idx++;
                    }
                })
                .catch(error=> {
                    console.log(error);
                });      
            },
            saveMovement: function() {
                axios.put('api/movements/' + this.movementToEdit.id, this.movementToEdit)
                .then(response=>{
                        this.showFailure = false;
                        this.showSuccess = true;
                        this.successMessage = 'Movement edited successfully';
                        this.editingMovement = false;
                        Vue.nextTick( () => this.$refs.vuetableMovements.refresh());
                })
                .catch(error=> {
                    this.showFailure = true;
                    this.showSuccess = false;
                    this.failMessage = 'Description max characteres is 255!';
                    console.log(error);
                });    
            },
            cancel: function() {
                this.editingMovement = false;
                Vue.nextTick( () => this.$refs.vuetableMovements.reload());
            },
            onPaginationData(paginationData) {
                this.$refs.pagination.setPaginationData(paginationData);
                this.$refs.paginationInfo.setPaginationData(paginationData);
            },
            onChangePage(page) {
                this.$refs.vuetableMovements.changePage(page);
            },
            onFilterSet(filterTextMovements) {
                this.moreParams.filterIdMovements = filterTextMovements.filterIdMovements,
                this.moreParams.filterTypeMovements = filterTextMovements.filterTypeMovements.charAt(0).toUpperCase() + filterTextMovements.filterTypeMovements.slice(1),
                this.moreParams.filterCategoryMovements = filterTextMovements.filterCategoryMovements,
                this.moreParams.filterTypePaymentMovements = filterTextMovements.filterTypePaymentMovements.charAt(0).toUpperCase() + filterTextMovements.filterTypePaymentMovements.slice(1),
                this.moreParams.filterEmailMovements = filterTextMovements.filterEmailMovements,
                this.moreParams.filterStartDateMovements = filterTextMovements.filterStartDateMovements,
                this.moreParams.filterEndDateMovements = filterTextMovements.filterEndDateMovements

                Vue.nextTick(() => this.$refs.vuetableMovements.refresh());
            },
            onFilterReset() {
                this.moreParams = {}
                Vue.nextTick(() => this.$refs.vuetableMovements.refresh());
            },
            moreDetails: function(movement) {
                this.$emit('more-details-click', movement);
            },
            movementCreation: function() {
                this.showSuccess = true;
                this.showFailure = false;
                this.successMessage = 'Movement Created';
                Vue.nextTick( () => this.$refs.vuetableMovements.refresh());
                this.$emit('update-balance');
            },
            movementNotCreated: function() {
                this.showSuccess = false;
                this.showFailure = true;
                this.failMessage = 'Movement not created - Invalid Data!';
            },
            cancelCreation: function() {
                this.$emit('movement-cancel');
            },
        },
        sockets: {
            income_movement_added (destUser) {
               Vue.nextTick( () => this.$refs.vuetableMovements.refresh());
            },
            movement_added (destUser, fromUser) {
               Vue.nextTick( () => this.$refs.vuetableMovements.refresh());
            },
            not_logged_in_transfer (destUser) {
                axios.get('api/users/' + destUser).then(response=>{
                    axios.get('api/sendEmail/' + response.data.data.email + '/' + response.data.data.name).then(response=>{
                        this.$toasted.error('User is not online. Transfer made successfully - Email notification sent!');
                    }).catch(error => {
                        console.log(error);
                    });
                })
                .catch(error => {
                    console.log(error);
                });
            },
            logged_in_transfer () {
                this.$toasted.show('User is online. Transfer made sucessfully!');
            },
            logged_in_special () {
                this.$toasted.show('Admin1 is online. Notification sent sucessfully!');
            },
            not_logged_in_special () {
                axios.get('api/sendEmailSpecial/admin1@mail.pt').then(response=>{
                    this.$toasted.error('Admin1 is not online. Email notification sent!');
                }).catch(error => {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.apiURL = "api/movements/" + this.$store.state.user.id;
            this.$events.$on('filter-movements-set', eventData => this.onFilterSet(eventData));
            this.$events.$on('filter-movements-reset', e => this.onFilterReset());
            console.log('Component Movement mounted.')
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

    /* Popup container */
    .popup {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    /* The actual popup (appears on top) */
    .popup .popuptext {
        visibility: hidden;
        width: 160px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 8px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -80px;
    }

    /* Popup arrow */
    .popup .popuptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    /* Toggle this class when clicking on the popup container (hide and show the popup) */
    .popup .show {
        visibility: visible;
        -webkit-animation: fadeIn 1s;
        animation: fadeIn 1s
    }

    /* Add animation (fade in the popup) */
    @-webkit-keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
