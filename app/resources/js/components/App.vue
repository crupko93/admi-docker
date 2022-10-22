<template>
    <v-app id="app">
        <vue-snotify></vue-snotify>
        <notifications-list></notifications-list>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </v-app>
</template>

<script>
import NotificationsList from './common/NotificationsList';
export default {
    components: {NotificationsList}
};
</script>
