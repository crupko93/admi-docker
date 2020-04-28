<template>
    <v-navigation-drawer v-model="notificationsDialog" class="aside-nav notifications-panel" absolute temporary right
        width="450" height="100vh"
    >
        <v-toolbar light color="alternative" class="notifications-modal">
            <v-btn icon light @click="closeNotifications">
                <v-icon>close</v-icon>
            </v-btn>

            <v-tabs v-model="notificationsTab" light color="white" slider-color="brown" grow>
                <v-tab ripple :key="0" @click="showNotifications">
                    <span class="notification-badge" v-if="unreadNotificationsCount > 0"></span>
                    Notifications
                </v-tab>

                <v-tab ripple :key="1" @click="showAnnouncements">
                    <span class="notification-badge" v-if="unreadAnnouncementsCount > 0"></span>
                    Announcements
                </v-tab>
            </v-tabs>
        </v-toolbar>

        <v-tabs-items v-model="notificationsTab">
            <v-tab-item :key="0">
                <v-card flat>
                    <!-- Informational Messages -->
                    <v-alert class="mt-0" :value="true" type="info"
                        v-if="!isLoading && notifications.system.length === 0"
                    >
                        We don't have anything to show you right now! But when we do, we'll be sure to let you know.
                        Talk to you
                        soon!
                    </v-alert>

                    <!-- Loading Messages -->
                    <v-card-text class="text-sm-center" v-if="isLoading">
                        <v-icon class="content-loader" light>cached</v-icon>
                        Loading Notifications
                    </v-card-text>

                    <!-- List of Notifications -->
                    <v-list three-line v-if="showingNotifications && notifications.system.length > 0">
                        <template v-for="(notification, index) in notifications.system">
                            <v-list-item class="notifications-list" v-if="notification.creator" :key="index">
                                <v-list-item-avatar>
                                    <profile-image :src="notification.creator.photo_url" :size="40"></profile-image>
                                </v-list-item-avatar>

                                <div class="notification-context">
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{ notification.creator.first_name }} {{ notification.creator.last_name }}
                                        </v-list-item-title>
                                        <v-list-item-sub-title v-html="notification.body"></v-list-item-sub-title>
                                        <span>
                                <small>
                                    {{ notification.created_at | relative }} -
                                    <a class="error--text" @click="deleteNotification(notification.id)">
                                        delete
                                    </a>
                                </small>
                            </span>
                                    </v-list-item-content>

                                    <!-- System Notification Action -->
                                    <v-btn class="mx-0" small :href="notification.action_url"
                                        target="_blank"
                                        v-if="notification.action_text">
                                        {{ notification.action_text }}
                                    </v-btn>
                                </div>
                            </v-list-item>

                            <v-list-item class="notifications-list" v-else :key="index">
                                <v-list-item-avatar>
                                    <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i :class="['fal', 'fa-stack-1x', notification.icon]"></i>
                                    </span>
                                </v-list-item-avatar>

                                <div class="notification-context">
                                    <v-list-item-content>
                                        <v-list-item-subtitle v-html="notification.body"></v-list-item-subtitle>
                                        <span>
                                            <small>
                                                {{ notification.created_at | relative }} -
                                                <a class="error--text" @click="deleteNotification(notification.id)">
                                                    delete
                                                </a>
                                            </small>
                                        </span>
                                    </v-list-item-content>

                                    <!-- Announcement Action -->
                                    <v-btn class="mx-0" small :href="notification.action_url"
                                        target="_blank"
                                        v-if="notification.action_text">
                                        {{ notification.action_text }}
                                    </v-btn>
                                </div>
                            </v-list-item>

                            <v-divider></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </v-tab-item>

            <v-tab-item :key="1">
                <v-card flat>
                    <!-- Informational Messages -->
                    <v-alert class="mt-0" :value="true" v-if="showingAnnouncements && notifications.announcements.length === 0" type="info">
                        We don't have anything to show you right now! But when we do, we'll be sure to let you know.
                        Talk to you
                        soon!
                    </v-alert>

                    <!-- List of Announcements -->
                    <v-list three-line v-if="showingAnnouncements">
                        <template v-for="(announcement, index) in notifications.announcements">
                            <v-list-item class="notifications-list" :key="index">
                                <v-list-item-avatar>
                                    <profile-image :src="announcement.creator.photo_url" :size="40"></profile-image>
                                </v-list-item-avatar>

                                <div class="notification-context">
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{ announcement.creator.first_name }} {{ announcement.creator.last_name }}
                                        </v-list-item-title>
                                        <v-list-item-subtitle v-html="announcement.body" class="mt-2 mb-5"></v-list-item-subtitle>
                                        <span><small>{{ announcement.created_at | relative }}</small></span>
                                    </v-list-item-content>

                                    <!-- Announcement Action -->
                                    <v-btn small :href="announcement.action_url" target="_blank" v-if="announcement.action_text">
                                        {{ announcement.action_text }}
                                    </v-btn>
                                </div>
                            </v-list-item>

                            <v-divider></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
    </v-navigation-drawer>
</template>

<script>
import * as moment    from 'moment';
import { mapGetters } from 'vuex';

export default {
    name: 'NotificationsList',

    data: () => ({
        notificationsTab   : 0,
        notificationsDialog: false,
        showingNotifications: true,
        showingAnnouncements: false,
        isLoading: false,
        notifications: {
            announcements: [],
            system       : []
        }
    }),

    computed: {
        ...mapGetters({
            user         : 'auth/user',
            announcements: 'notifications/announcements',
            system       : 'notifications/system'
        }),

        /**
         * The number of unread announcements.
         */
        unreadAnnouncementsCount () {
            if (this.announcements && this.user) {
                if (this.announcements.length && ! this.user.last_read_announcements_at) {
                    return this.announcements.length;
                }

                return _.filter(this.announcements, announcement => {
                    return moment.utc(this.user.last_read_announcements_at).isBefore(
                        moment.utc(announcement.created_at)
                    );
                }).length;
            }

            return 0;
        },


        /**
         * The number of unread notifications.
         */
        unreadNotificationsCount () {
            if (this.system) {
                return _.filter(this.system, notification => {
                    return ! notification.read;
                }).length;
            }

            return 0;
        },
    },

    methods: {
        getNotifications () {
            API.notifications.all()
                .then(response => {
                    this.$store.dispatch('notifications/setNotifications', {
                        announcements: response.data.announcements,
                        system       : response.data.system
                    });
                    this.notifications.announcements = response.data.announcements;
                    this.notifications.system        = response.data.system;
                })
                .catch(Utils.standardErrorResponse);
        },
        
        closeNotifications () {
            this.notificationsDialog = false;
            this.notificationsTab    = '0';

            this.showNotifications();
        },

        deleteNotification (id) {
            return API.users.deleteNotification(id).then(() => {
                this.notifications.system = _.reject(this.notifications.system, object => {
                    return object.id === id;
                });

                Snotify.success('Notification deleted!');
            }).catch(Utils.standardErrorResponse);
        },

        /**
         * Show the user notifications.
         */
        showNotifications () {
            this.showingNotifications = true;
            this.showingAnnouncements = false;
        },


        /**
         * Show the product announcements.
         */
        showAnnouncements () {
            this.showingNotifications = false;
            this.showingAnnouncements = true;

            this.updateLastReadAnnouncementsTimestamp();
        },


        /**
         * Update the last read announcements timestamp.
         */
        updateLastReadAnnouncementsTimestamp () {
            return API.announcements.markAnnouncementsAsRead()
                .then(response => {
                    this.$store.dispatch('auth/setUser', response.data);
                    Bus.$emit('refresh-notifications-count');
                });
        },

        markNotificationsAsRead (id) {
            return API.notifications.markAsRead({id})
                .then(response => {
                    this.$store.dispatch('notifications/setNotifications', {
                        announcements: response.data.announcements,
                        system       : response.data.system
                    });
                })
                .catch(Utils.standardErrorResponse);
        }
    },

    mounted () {
        this.getNotifications();

        this.$nextTick(() => {
            Bus.$on('notifications-toggle', () => {
                this.notificationsDialog = !this.notificationsDialog;

                if (this.notificationsDialog) {
                    this.getNotifications();
                }
            });
        });
    }
};
</script>
