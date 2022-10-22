<template>
    <v-container class="main-content" grid-list-lg>
        <!-- Interval period selector -->
        <v-layout row wrap>
            <v-spacer></v-spacer>

            <v-btn small outlined
                class="mr-2"
                color="primary"
                :disabled="isLoading"
                @click="retrieveGraphData(interval)"
            >
                <v-icon small left v-if="!isLoading">fal fa-sync</v-icon>
                <v-icon small left v-else>fal fa-sync fa-spin</v-icon>
                {{$t('refresh')}}
            </v-btn>

            <v-menu offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn v-on="on" small outlined color="gray">
                        <v-icon left>fal fa-clock</v-icon>
                        {{ intervalOptions[interval] }}
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item v-for="(option, value) in intervalOptions"
                        @click="interval = value"
                        :key="value"
                    >
                        <v-list-item-title>{{ option }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-layout>

        <!-- Total Flights -->
        <v-layout row wrap>
            <v-flex xs12 md4>
                <GraphTotalFlights :refresh="isLoading"></GraphTotalFlights>
            </v-flex>

            <v-flex xs12 md8>
                <v-card>
                    <content-loader slot="default" :loading="isLoading"></content-loader>
                    <v-card-title>
                        <h3 :class="[color + '--text']" class="mt-2 mb-2 text--darken-3 font-weight-regular">
                            {{$t('total_flights_by_period')}}
                        </h3>
                    </v-card-title>
                    <v-card-text>
                        <GraphFlightsTotal v-if="graphs.flights_by_period.length"
                            :height="200" :data="graphs.flights_by_period"
                        ></GraphFlightsTotal>

                        <h2 v-else class="grey--text font-weight-regular text-xs-center pt-5 pb-5">
                            {{$t('no_data_available_for_period')}}
                        </h2>
                    </v-card-text>
                </v-card>

            </v-flex>
        </v-layout>

        <!-- Flights by Status -->
        <v-layout row wrap justify-center>
            <v-flex xs12 md6>
                <v-card>
                    <content-loader slot="default" :loading="isLoading"></content-loader>
                    <v-card-title>
                        <h3 :class="[color + '--text']" class="mt-2 mb-2 text--darken-3 font-weight-regular">
                            {{$t('flights_by_status')}}
                        </h3>
                    </v-card-title>
                    <v-card-text>
                        <GraphFlightsStatus v-if="graphs.flights_by_status.length"
                            :height="200" :data="graphs.flights_by_status"
                        ></GraphFlightsStatus>

                        <h2 v-else class="grey--text font-weight-regular text-xs-center pt-5 pb-5">
                            {{$t('no_data_available_for_period')}}
                        </h2>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>

import Chart              from 'chart.js';
import GraphTotalFlights   from './GraphTotalFlights.vue';
import GraphFlightsStatus from './GraphFlightsStatus.vue';
import GraphFlightsTotal           from './GraphFlightsTotal.vue';

export default {
    name: 'DashboardFlights',

    components: {
        GraphTotalFlights,
        GraphFlightsStatus,
        GraphFlightsTotal
    },

    data: () => ({
        isLoading: false,

        /////////////////
        // Remote data //
        /////////////////
        // Dashboard graph data
        graphs: {
            flights_by_status          : [],
            flights_by_period          : []
        },

        ////////////////
        // State data //
        ////////////////
        // Currently selected interval option
        interval: 'monthly',

        // List of selectable interval options
        intervalOptions: {
            last_7_days : 'Last 7 days (grouped by day)',
            last_30_days: 'Last 30 days (grouped by week)',
            monthly     : 'Monthly (grouped by month, current year)',
            yearly      : 'Yearly (grouped by year, all time)'
        }
    }),

    computed: {
        color: () => 'primary'
    },

    methods: {
        retrieveGraphData (interval) {
            this.isLoading = true;

            this.graphs.flights_by_status = [
                {
                    status: 'cancelled',
                    count: 291,
                    name: 'Cancelled',
                    color: '#BCAAA4'
                },
                {
                    status: 'pending',
                    count: 154,
                    name: 'Pending',
                    color: '#FBD38D'
                },
                {
                    status: 'processed',
                    count: 432,
                    name: 'Processed',
                    color: '#A3BFFA'
                },
            ];
            this.graphs.flights_by_period = [
                {
                    count: 32,
                    month_raw: 3,
                    display: 'March'
                },
                {
                    count: 41,
                    month_raw: 2,
                    display: 'February'
                },
                {
                    count: 72,
                    month_raw: 1,
                    display: 'January'
                },
            ];

            this.isLoading = false;
        }
    },

    watch: {
        interval () {
            this.retrieveGraphData(this.interval);
        }
    },

    created () {
        Chart.scaleService.updateScaleDefaults('linear', {
            ticks: {
                min     : 0,
                stepSize: 1
            }
        });

        this.retrieveGraphData(this.interval);
    }
};
</script>

<style scoped>

</style>
