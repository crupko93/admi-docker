<template>
    <v-card>
        <content-loader slot="default" :loading="isLoading"></content-loader>
        <v-card-title>
            <h3 class="mt-2 mb-2 primary--text text--darken-3 font-weight-regular">
                {{$t('total_flights')}}
            </h3>
        </v-card-title>
        <v-card-title style="height: 400px" class="justify-center align-center">
            <div class="credits-dashboard">
                <div>{{ flights }}</div>
            </div>
        </v-card-title>
    </v-card>
</template>

<script>
export default {
    name: 'GraphTotalFlights',

    props: ['refresh'],

    data: () => ({
        isLoading: false,
        flights: 21863
    }),

    methods: {
        getTotalFlights () {
            this.isLoading = true;
            this.flights = 21863;
            this.isLoading = false;
        }
    },

    watch: {
        refresh (val) {
            if (val) this.getTotalFlights();
        }
    },

    mounted () {
        this.getTotalFlights();
    }
};
</script>

<style lang="scss" scoped>
.credits-dashboard {
    background: url("/img/credits-bg.png") no-repeat center center;
    background-size: contain;
    width: 100%;
    height: 264px;
    position: relative;
    display: flex;
    align-content: center;
    justify-content: center;

    div {
        color: #f0fff4;
        display: block;
        font-size: 40px;
        font-weight: 600;
        align-self: center;
    }
}
</style>
