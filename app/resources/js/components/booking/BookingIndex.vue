<template>
    <v-card>
        <v-card-title>
            <h3 class="title">{{$t('user_management')}}</h3>
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
            >
                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <template slot="item" slot-scope="props">
                    <tr>
                        <td>{{ props.item.code }}</td>
                        <td class="text-center px-0">
                            <v-menu offset-y>
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                        v-on="on"
                                        color="primary" text icon
                                    >
                                        <v-icon>more_vert</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <!-- [ALL] Edit -->
                                    <v-list-item @click="$router.push({ name: 'booking-edit', params: { bookingId: '1' }})">
                                        <v-list-item-title>{{$t('edit')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [ALL] Delete -->
                                    <v-list-item @click="deleteBooking(props.item)">
                                        <v-list-item-title>{{$t('delete')}}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </div>
    </v-card>
</template>

<script>
import BookingDialog from './BookingEdit';
import lodash        from 'lodash';
import { mapGetters } from 'vuex';
export default {
    name: 'BookingIndex',

    data: vm => ({
        lodash,
        /////////////////
        // Remote data //
        /////////////////
        // List of bookings and total count (to be retrieved on pagination change)
        bookings    : [],
        bookingsCount: 0,

        ////////////////
        // State data //
        ////////////////
        // Booking data table loading state
        isLoading: false,
        isShowProfile: false,

        // Data table headers
        headers: [
            {
                text    : vm.$t('code'),
                value   : 'code',
                align   : 'left'
            }, {
                text    : vm.$t('actions'),
                value   : '',
                align   : 'center',
                sortable: false
            }
        ],

        // Booking data table search term
        searchTerm: '',

        // Booking selected from the table
        selectedBooking: null,

        // Booking data table pagination object
        pagination: {}
    }),

    computed: mapGetters({
        locale: 'lang/locale'
    }),

    methods: {
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
