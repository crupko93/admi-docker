<template>
    <v-card>
        <v-card-title>
            <h3 class="title">{{$t('bookings_management')}}</h3>
        </v-card-title>
        <div>
            <v-layout wrap>
                <v-flex xs12 class="text-right pr-4">
                    <v-btn color="primary" fab outlined small @click="$router.push({ name: 'booking-edit', params: { bookingId: null }})">
                        <v-icon>far fa-plus</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>

            <v-card-text>
                <v-text-field flat solo-inverted single-line hide-details clearable
                    class="datatable-search"
                    append-icon="search" :label="$t('search')"
                    v-model="searchTerm"
                ></v-text-field>
            </v-card-text>

            <v-data-table
                :loading="isLoading"
                :headers="headers"
                :items="bookings"
                :server-items-length="bookingsCount"
                :options.sync="pagination"
                :footer-props="{itemsPerPageOptions: [20, 50, 100]}"
                @click:row="$router.push({ name: 'booking-edit', params: { bookingId: '1' }})"
                class="booking-table"
            >
                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <!-- Operator -->
                <template v-slot:item.operator="{ item }">
                    <v-chip small color="grey lighten-2" class="text--darken-3">
                        <v-avatar><profile-image :src="item.operator.src"></profile-image></v-avatar>
                        {{item.operator.first_name}}
                    </v-chip>
                </template>

                <!-- Date -->
                <template v-slot:item.date="{ item }">
                    <div class="text-md-center">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn small v-on="on" text icon :color="dateColor(item.date)">
                                    <v-icon small>
                                        fas fa-circle
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>{{ dateFormat(item.date) }}</span>
                        </v-tooltip>
                    </div>
                </template>

                <!-- Company -->
                <template v-slot:item.company="{ item }">
                    {{ item.airline + item.supplier }}
                </template>

                <!-- Total -->
                <template v-slot:item.total="{ item }">
                    {{ item.total | currency }}
                </template>

                <!-- Services -->
                <template v-slot:item.services="{ item }">
                    <span v-for="service in item.services" :key="service">
                        <v-icon small v-if="service === 'flight'" color="green">
                            fas fa-plane
                        </v-icon>
                        <v-icon small v-if="service === 'insurance'" color="green">
                            fas fa-shield
                        </v-icon>
                        <v-icon small v-if="service === 'hotel'" color="green">
                            fas fa-bed
                        </v-icon>
                        <v-icon small v-if="service === 'car'" color="green">
                            fas fa-car
                        </v-icon>
                    </span>
                </template>
            </v-data-table>
        </div>
    </v-card>
</template>

<script>
import lodash         from 'lodash';
import { mapGetters } from 'vuex';

export default {
    name: 'BookingIndex',

    data: vm => ({
        lodash,
        /////////////////
        // Remote data //
        /////////////////
        // List of bookings and total count (to be retrieved on pagination change)
        bookings    : [
            {
                status: 'Nou',
                operator: {
                    first_name: 'Sanda'
                },
                date: '2020-04-26',
                airline: 'FR',
                supplier: 'U2',
                pnr: 'AMR28X',
                payment_status: 'Aprobata',
                total: '210',
                services: [
                    'flight', 'insurance', 'hotel', 'car'
                ],
                source: 'fly-go.it'
            },

            {
                status: 'Nou',
                operator: {
                    first_name: 'Claudio'
                },
                date: '2020-04-27T00:00:00+02:00',
                airline: 'AZ',
                supplier: 'FR',
                pnr: 'AMR28X',
                payment_status: 'Aprobata',
                total: '129.90',
                services: [
                    'flight', 'insurance', 'car'
                ],
                source: 'fly-go.it'
            },

            {
                status: 'Nou',
                operator: {
                    first_name: 'Sanda'
                },
                date: '2020-04-27T00:00:00+03:00',
                airline: 'FR',
                supplier: 'U2',
                pnr: 'AMR28X',
                payment_status: 'Aprobata',
                total: '156',
                services: [
                    'flight'
                ],
                source: 'fly-go.it'
            },
        ],
        bookingsCount: 1,

        ////////////////
        // State data //
        ////////////////
        // Booking data table loading state
        isLoading: false,
        isShowProfile: false,

        // Data table headers
        headers: [
            {
                text    : 'Status',
                value   : 'status',
                align   : 'left'
            }, {
                text    : 'Operator',
                value   : 'operator',
                align   : 'left'
            }, {
                text    : 'Date',
                value   : 'date',
                align   : 'center'
            }, {
                text    : 'Company',
                value   : 'company',
                align   : 'left'
            }, {
                text    : 'PNR',
                value   : 'pnr',
                align   : 'left'
            }, {
                text    : 'Payment Status',
                value   : 'payment_status',
                align   : 'left'
            }, {
                text    : 'Total',
                value   : 'total',
                align   : 'left'
            }, {
                text    : 'Services',
                value   : 'services',
                align   : 'left'
            }, {
                text    : 'Source',
                value   : 'source',
                align   : 'left'
            }
        ],

        // Booking data table search term
        searchTerm: '',

        // Booking selected from the table
        selectedBooking: null,

        // Booking data table pagination object
        pagination: {}
    }),

    computed: {
        ...mapGetters({
            locale: 'lang/locale'
        }),
    },

    methods: {
        /**
         * Convert time diff to string with details about duration
         * @param date
         * @returns {string}
         */
        dateFormat (date) {
            let diffTime = this.moment().diff(date);
            let duration = this.moment.duration(diffTime);
            let years = duration.years(),
                days = duration.days(),
                hours = duration.hours(),
                minutes = duration.minutes(),
                seconds = duration.seconds();

            return years + (years > 0 && ' years ') + days + (days > 0 && ' days ') + hours + (hours > 0 && ' hours ')
                + minutes + (minutes > 0 && ' minutes ') + seconds + (seconds > 0 && ' seconds');
        },

        /**
         * Return color of date icon based on time diff between that date and now
         * @param date
         * @returns {string}
         */
        dateColor (date) {
            if (this.moment().diff(date, 'hours') > 5) {
                if (this.moment().diff(date, 'hours') > 10) {
                    return 'red darken-4';
                } else {
                    return 'orange';
                }
            }
            return 'green';
        },

        retrieveBookings () {},

        updateBooking (booking) {},

        deleteBooking (booking) {
            swal({
                text: this.$t('booking_will_be_deleted').replace('*name*', booking.name),

                title    : this.$t('are_you_sure'),
                icon     : 'info',
                className: 'swal-info',
                buttons  : {
                    cancel : {
                        text      : this.$t('cancel'),
                        visible   : true,
                        closeModal: true
                    },
                    confirm: {
                        text      : this.$t('ok'),
                        closeModal: false
                    }
                }
            })
                .then(confirm => {

                });
        },
    },

    watch: {
        pagination: {
            handler () {

            },
            deep: true
        },

        searchTerm: {
            handler:
                lodash.debounce(function () {

                }, 500)

        },

        locale: {
            handler () {
                this.headers = [
                    {
                        text    : this.$t('code'),
                        value   : 'code',
                        align   : 'left'
                    }, {
                        text    : this.$t('actions'),
                        value   : '',
                        align   : 'center',
                        sortable: false
                    }
                ];
            },
            deep: true
        }
    }
};
</script>
