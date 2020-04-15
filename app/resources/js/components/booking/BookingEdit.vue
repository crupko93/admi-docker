<template>
    <v-layout row justify-center>
        <v-flex xs12>
            <v-icon class="mr-2">fas fa-receipt</v-icon>
            Booking B001202
        </v-flex>
    </v-layout>
</template>

<script>

export default {
    name: 'BookingEdit',

    props: { bookingId: String },

    data: () => ({
        /////////////////
        // Remote data //
        /////////////////
        bookingId : null,

        ////////////////
        // State data //
        ////////////////
        form: {},

        isLoading : false

    }),

    methods: {
        initialize () {
            this.form = {
                id         : '',
                name       : ''
            };
        },

        /**
         * Open current dialog
         * */
        new () {
            this.bookingId     = null;
        },

        /**
         * Open current dialog and fetch booking based on 'id' provided from props
         * */
        edit (id) {
            this.bookingId     = id;
        },

        submit () {
            this.successCallback();
        },

        /**
         * Emit success and close dialog
         */
        successCallback () {
            Snotify.success('Booking saved!', this.$t('success') + '!');
            this.$emit('booking-save');
            this.closeDialog();
        },

        closeDialog () {
            this.isLoading     = false;
            this.initialize();
        }
    },

    mounted () {
        console.log(this.props.bookingId);
    }
};
</script>
