<template>
    <div>
        <v-tabs
            class="dashboard-tabs"
            v-model="tab"
            background-color="transparent"
            color="basil"
        >
            <v-tab>{{$t('flights')}}</v-tab>
            <v-tab>{{$t('customers')}}</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
            <v-tab-item transition="fade">
                <DashboardFlights></DashboardFlights>
            </v-tab-item>

            <v-tab-item transition="fade">
                <DashboardCustomers></DashboardCustomers>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>

<script>
import DashboardFlights   from './DashboardFlights';
import DashboardCustomers from './DashboardCustomers';

export default {
    name: 'Dashboard',

    components: {
        DashboardFlights,
        DashboardCustomers
    },

    data: () => ({
        isLoading: false,
        tab      : null

    })
};
</script>
