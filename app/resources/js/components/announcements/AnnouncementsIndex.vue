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
                    <v-btn color="primary" fab outlined small @click="openAnnouncementDialog()">
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
                                <v-btn v-on="on" icon class="mx-0" @click="openAnnouncementDialog(item)">
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

        <!-- Edit Announcement Dialog -->
        <v-dialog v-model="announcementDialog" max-width="600">
            <v-card>
                <v-card-title class="headline">
                    <h3 class="title">
                        <span v-if="form.id">Update Announcement</span>
                        <span v-else>Create Announcement</span>
                    </h3>
                </v-card-title>
                <v-form>
                    <v-card-text>
                        <!-- Announcement -->
                        <v-textarea required label="Announcement*"
                            @input="$v.form.body.$touch()" @blur="$v.form.body.$touch()"
                            :error-messages="bodyErrors" counter="300"
                            v-model="form.body"
                        ></v-textarea>

                        <!-- Action Text -->
                        <v-text-field label="Action Button Text" v-model="form.action_text">
                        </v-text-field>

                        <!-- Action URL -->
                        <v-text-field label="Action Button URL" v-model="form.action_url">
                        </v-text-field>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" outlined :disabled="isLoading"
                            @click.native="closeAnnouncementDialog"
                        >
                            Cancel
                        </v-btn>

                        <v-btn color="primary" @click="save" :disabled="isLoading">
                            <span v-if="form.id">Update</span>
                            <span v-else>Save</span>
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
    name: 'AnnouncementsIndex',

    validations: {
        form: {
            body: { required, maxLength: maxLength(300) }
        }
    },

    data: () => ({
        announcementDialog: false,
        isLoading         : false,

        // Data table pagination object
        pagination: {},

        // List of announcements and total count (to be retrieved on pagination change)
        announcements     : [],
        announcementsCount: 0,

        form: {
            id         : null,
            body       : '',
            action_text: '',
            action_url : ''
        },

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

    computed: {
        bodyErrors () {
            if (!this.$v.form.body.$dirty) return [];
            const errors = [];
            !this.$v.form.body.required && errors.push('Announcement text is required!');
            !this.$v.form.body.maxLength && errors.push('The announcement cannot have more than 300 characters!');
            return errors;
        }
    },

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

        save () {
            this.$v.$touch();

            if (this.$v.$invalid) return;

            return API.announcements.save(this.form)
                .then(() => {
                    this.getAnnouncements();
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {
                    Snotify.success('Announcement successfully saved!');
                    this.closeAnnouncementDialog();
                });
        },

        openAnnouncementDialog (announcement) {
            this.announcementDialog = true;

            if (!announcement) return;

            this.form.id = announcement;

            this.form.icon        = announcement.icon;
            this.form.body        = announcement.body;
            this.form.action_text = announcement.action_text;
            this.form.action_url  = announcement.action_url;
        },

        closeAnnouncementDialog () {
            this.announcementDialog   = false;

            this.form = {
                id         : null,
                body       : '',
                action_text: '',
                action_url : ''
            };

            this.$v.$reset();
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
