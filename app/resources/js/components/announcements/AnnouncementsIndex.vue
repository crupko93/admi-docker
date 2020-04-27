<template>
    <div class="settings-tab-section">
        <v-card>
            <v-card-title>
                <h3 class="title">{{this.$t('announcements')}}</h3>

                <v-alert :value="true" type="info">
                    Announcements you create here will be sent to the "Announcements" section of the
                    notifications modal window, informing your users about new features and improvements to your
                    application.
                </v-alert>
            </v-card-title>

            <v-layout wrap>
                <v-flex xs12 class="text-right pr-4">
                    <v-btn color="primary" fab outlined small @click="$refs.announcementDialog.new()">
                        <v-icon>far fa-plus</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>

            <v-data-table
                :loading="isLoading"
                :headers="headers"
                :items="announcements"
                :server-items-length="announcementsCount"
                :options.sync="pagination"
                :footer-props="{itemsPerPageOptions: [20, 50, 100]}"
            >

                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <!-- Photo -->
                <template v-slot:item.user_id="{ item }">
                    <profile-image :src="item.creator.photo_url" :size="30"></profile-image>
                </template>

                <!-- Date -->
                <template v-slot:item.created_at="{ item }">
                    {{ item.created_at | datetime }}
                </template>

                <!-- Body -->
                <template v-slot:item.body="{ item }">
                    {{ _.truncate(item.body, {length: 45}) }}
                </template>

                <!-- Actions -->
                <template v-slot:item.actions="{ item }">
                    <div class="text-xs-center mx-0">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" @click="$refs.announcementDialog.edit(item)">
                                    <v-icon color="primary">far fa-pen</v-icon>
                                </v-btn>
                            </template>
                            <span>Edit Announcement</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" icon class="mx-0" @click="deleteAnnouncement(item)">
                                    <v-icon color="error">far fa-times</v-icon>
                                </v-btn>
                            </template>
                            <span>Delete Announcement</span>
                        </v-tooltip>
                    </div>
                </template>
            </v-data-table>
        </v-card>

        <!-- Announcement Dialog -->
        <AnnouncementDialog ref="announcementDialog" @announcement-saved="getAnnouncements"></AnnouncementDialog>
    </div>
</template>

<script>
import AnnouncementDialog from './AnnouncementDialog';
export default {
    name: 'AnnouncementsIndex',
    components: {AnnouncementDialog},
    data: () => ({
        isLoading         : false,

        // Data table pagination object
        pagination: {},

        // List of announcements and total count (to be retrieved on pagination change)
        announcements     : [],
        announcementsCount: 0,

        headers: [
            {
                text    : 'Creator',
                value   : 'user_id',
                align   : 'left',
                sortable: false
            },
            {
                text    : 'Date',
                value   : 'created_at',
                align   : 'left',
                sortable: false
            },
            {
                text    : 'Announcement',
                value   : 'body',
                align   : 'left',
                sortable: false
            },
            {
                text    : 'Actions',
                value   : 'actions',
                align   : 'center',
                sortable: false
            }
        ]
    }),

    methods: {
        getAnnouncements () {
            this.isLoading = true;

            return API.announcements.all(Object.assign(this.pagination, {
                role  : this.role,
                search: this.searchTerm || null
            }))
                .then(response => {
                    this.$store.dispatch('notifications/setNotifications', {
                        announcements: response.data.announcements,
                    });

                    this.announcements      = response.data.announcements;
                    this.announcementsCount = response.data.meta.total;
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => this.isLoading = false);
        },

        deleteAnnouncement (announcement) {
            return swal({
                title    : 'Warning',
                text     : 'Are you sure you want to delete this announcement?',
                icon     : 'warning',
                className: 'swal-warning',
                buttons  : {
                    cancel: {
                        text     : 'Cancel',
                        className: 'outlined',
                        visible  : true
                    },
                    confirm: {
                        text      : 'Yes',
                        className : 'outlined',
                        closeModal: false
                    }
                }
            }).then(confirm => {
                if (!confirm) return;

                return API.announcements.delete(announcement.id)
                    .then(() => {
                        this.getAnnouncements();

                        Snotify.success('Announcement deleted!');

                        swal.close();
                    })
                    .catch(Utils.standardErrorResponse);
            });
        }
    },

    mounted () {
        this.getAnnouncements();
    }
};
</script>
