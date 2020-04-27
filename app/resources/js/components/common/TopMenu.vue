<template>
    <v-app-bar dark :clipped-left="$vuetify.breakpoint.mdAndUp" fixed app hide-on-scroll color="primary" class="align-center" style="width: 100%">
        <v-app-bar-nav-icon @click.stop="navToggle"></v-app-bar-nav-icon>

        <v-toolbar-title class="white--text d-flex align-center">
            <img src="/img/logo-sign.png" alt="Admin logo" style="max-height: 56px" class="mr-2">
            <div class="flex-row">{{ siteName }}</div>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-tooltip bottom color="alternative dark">
            <template v-slot:activator="{ on }">
                <span v-on="on">
                    <v-btn icon @click="openNotifications" v-if="unreadNotificationsCount + unreadAnnouncementsCount === 0">
                        <v-icon>fal fa-bell</v-icon>
                        <span>{{ unreadNotificationsCount + unreadAnnouncementsCount }}</span>
                    </v-btn>

                    <v-btn icon @click="openNotifications" v-if="unreadNotificationsCount + unreadAnnouncementsCount > 0">
                        <v-icon class="shake error--text">fal fa-bell</v-icon>
                        <span class="bounce">{{ unreadNotificationsCount + unreadAnnouncementsCount }}</span>
                    </v-btn>
                </span>
            </template>
            <span>Notifications</span>
        </v-tooltip>
        <locale-dropdown></locale-dropdown>
    </v-app-bar>
</template>

<script>
import { settings }   from '~/config';
import LocaleDropdown from './LocaleDropdown';
import * as moment    from 'moment';
import { mapGetters } from 'vuex';

export default {
    components: {
        LocaleDropdown
    },

    data: () => ({
        siteName: settings.siteName,
        unreadAnnouncementsCount: 0,
        unreadNotificationsCount: 0
    }),

    computed: {
        ...mapGetters({
            user         : 'auth/user',
            announcements: 'notifications/announcements',
            system       : 'notifications/system'
        }),


    },

    methods: {
        navToggle () {
            this.$emit('nav-toggle');
        },

        openNotifications () {
            Bus.$emit('notifications-toggle');
        },

        /**
         * The number of unread announcements.
         */
        setUnreadAnnouncementsCount () {
            if (this.announcements && this.user) {
                if (this.announcements.length && !this.user.last_read_announcements_at) {
                    this.unreadAnnouncementsCount = this.announcements.length;
                } else {
                    this.unreadAnnouncementsCount = _.filter(this.announcements, announcement => {
                        return moment.utc(this.user.last_read_announcements_at).isBefore(
                            moment.utc(announcement.created_at)
                        );
                    }).length;
                }
            } else {
                this.unreadAnnouncementsCount = 0;
            }
        },


        /**
         * The number of unread notifications.
         */
        setUnreadNotificationsCount () {
            if (this.system) {
                this.unreadNotificationsCount = _.filter(this.system, notification => {
                    return ! notification.read;
                }).length;
            } else {
                this.unreadNotificationsCount = 0;
            }
        }
    },

    mounted () {
        Bus.$on('refresh-notifications-count', () => {
            this.setUnreadAnnouncementsCount();
            this.setUnreadNotificationsCount();
        });
    },

    watch: {
        announcements: {
            handler () {
                this.setUnreadAnnouncementsCount();
            },
            deep: true
        },

        system: {
            handler () {
                this.setUnreadNotificationsCount();
            },
            deep: true
        }
    }
};
</script>
