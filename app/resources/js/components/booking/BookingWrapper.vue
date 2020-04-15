<template>
    <v-container fluid grid-list-xl>
        <v-layout row justify-center wrap>
            <v-flex xs12>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    name: 'BookingWrapper'
};
</script>
