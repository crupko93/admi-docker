<template>
    <v-content>
        <v-container fluid fill-height justify-center align-center class='login-page'>
            <vue-particles v-if="!isAuthenticated"></vue-particles>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </v-container>
    </v-content>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    data: () => ({
        isAuthenticated: false
    }),

    computed: mapGetters({
        check: 'auth/check'
    }),

    mounted () {
        this.isAuthenticated = this.check;
    },

    watch: {
        check: {
            handler () {
                this.isAuthenticated = this.check;
            },
            deep: true
        }
    }
};
</script>
