<template>
    <v-container fluid grid-list-xl>
        <v-layout row justify-center wrap>
<!--          <v-flex lg3 sm4 xs12>-->
<!--            <sidemenu :threshold="67" :offset="90" :items="items"></sidemenu>-->
<!--          </v-flex>-->
            <v-flex xs12>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import Sidemenu from '$comp/common/Sidemenu';

export default {
    components: {
        Sidemenu
    },

    data: () => ({
        items: []
    }),

    mounted () {
        this.navigation();
    },

    methods: {
        navigation () {
            this.items = [
                {title: this.$t('profile'), icon: 'visibility', to: {name: 'profile'}},
                {title: this.$t('edit'), icon: 'edit', to: {name: 'profile-edit'}}
            ];
        }
    }
};
</script>
