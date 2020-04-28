<template>
    <v-container class="main-content" grid-list-lg>
        <v-layout row wrap>
            <v-flex xs12 md4>
                <v-card height="100%" color="#1F7087">
                    <content-loader slot="default" :loading="loading"></content-loader>

                    <div class="signals-total">
                        <div v-if="graphs.total_customers">
                            <h1 class="white--text font-weight-black">
                                {{ graphs.total_customers }}
                            </h1>
                        </div>

                        <h3 v-else class="white--text font-weight-regular">
                            {{$t('no_data_available')}}
                        </h3>

                        <h4 class="mt-2 mb-2 teal--text text--lighten-4 font-weight-regular text-uppercase">
                            {{$t('total_customers')}}
                        </h4>
                    </div>
                </v-card>
            </v-flex>

            <v-flex xs12 md8>
                <v-card>
                    <content-loader slot="default" :loading="loading"></content-loader>
                    <v-card-title>
                        <h3 class="mt-2 mb-2 primary--text text--darken-3 font-weight-regular">
                            {{$t('total_customers_by_spending')}}
                        </h3>
                    </v-card-title>

                    <v-card-text>
                        <GraphCustomersBySpending
                            v-if="graphs.top_customers_by_spending"
                            :height="130"
                            :data="graphs.top_customers_by_spending"
                        ></GraphCustomersBySpending>

                        <h3 v-else class="grey--text font-weight-regular text-xs-center pt-5 pb-5">
                            {{$t('no_data_available')}}
                        </h3>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <v-layout row wrap>
            <v-flex>
                <v-card>
                    <v-card-title>
                        <h3 class="mt-2 mb-2 primary--text text--darken-3 font-weight-regular">
                            {{$t('top_ten_customers_by_spending')}}
                        </h3>
                    </v-card-title>

                    <v-card-text>
                        <v-data-table
                            hide-default-footer
                            :headers="topClientsHeaders"
                            :items="graphs.top_customers"
                            class="elevation-0"
                        >
                                <template v-slot:item.name="{item}">
                                    {{item.first_name+' '+item.last_name}}
                                </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

    </v-container>
</template>

<script>
import GraphCustomersBySpending from './GraphCustomersBySpending';

export default {
    name: 'DashboardCustomers',

    components: {
        GraphCustomersBySpending
    },

    data: () => ({
        loading: false,

        topClientsHeaders: [
            {text: 'Name', align: 'left', sortable: false, value: 'name'},
            {text: 'Email', align: 'left', sortable: false, value: 'email'},
            {text: 'Total Cancelled', align: 'center', sortable: false, value: 'total_cancelled'},
            {text: 'Total Purchased', align: 'center', sortable: false, value: 'total_purchased'}
        ],
        graphs           : {
            total_customers          : 0,
            top_customers_by_spending: [],
            top_customers            : []
        }
    }),

    methods: {
        retrieveChartsData () {
            this.loading                          = true;
            this.graphs.total_customers           = 19210;
            this.graphs.top_customers_by_spending = [
                {
                    status: 'cancelled',
                    count: 87,
                    name: 'Cancelled',
                    color: '#bca68d'
                },
                {
                    status: 'pending',
                    count: 312,
                    name: 'Pending',
                    color: '#fba484'
                },
                {
                    status: 'processed',
                    count: 124,
                    name: 'Processed',
                    color: '#8485fa'
                }
            ];
            this.graphs.top_customers             = [
                {
                    id: 23,
                    first_name: 'Virgi',
                    last_name: 'Kshlerin',
                    email: 'stoltenberg@gerlach.com',
                    total_cancelled: 4,
                    total_purchased : 25,
                    color : '#bca68d'
                },
                {
                    id: 22,
                    username: 'carlos.stark',
                    first_name: 'Nicolas',
                    last_name: 'Murray',
                    email: 'waters.joanne@gmail.com',
                    total_cancelled: 0,
                    total_purchased : 31,
                    color : '#fba484'
                },
                {
                    id: 21,
                    username: 'vandervort.shakira',
                    first_name: 'Cleora',
                    last_name: 'Harvey',
                    total_cancelled: 12,
                    total_purchased : 53,
                    color : '#8485fa'
                }
            ];

            this.loading = false;
        }
    },

    mounted () {
        this.retrieveChartsData();
    }
};
</script>

<style lang="scss" scoped>
    .signals-total {
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
        padding: 1rem;

        h1 {
            font-size: 90px;
            line-height: 1;
        }
    }
</style>
