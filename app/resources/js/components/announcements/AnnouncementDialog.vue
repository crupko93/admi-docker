<template>
    <v-layout row justify-center>
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


                        <!-- Users -->
                        <v-autocomplete multiple chips required color="primary lighten-2"
                                        @input="$v.form.users.$touch()" @blur="$v.form.users.$touch()"
                                        :items="users"
                                        :error-messages="usersErrors"
                                        item-text="name"
                                        item-value="name"
                                        :label="$t('users')+'*'"
                                        v-model="form.users"
                                        return-object
                                    >
                                        <template slot="selection" slot-scope="data">
                                            <v-chip close class="chip--select-multi"
                                                @click:close="removeSelectedUsers(data.item)"
                                                :input-value="data.selected"
                                            >
                                                {{ data.item.email }}
                                            </v-chip>
                                        </template>
                                        <template
                                            slot="item"
                                            slot-scope="data"
                                        >
                                            <template v-if="typeof data.item !== 'object'">
                                                <v-list-item-content v-text="data.item"></v-list-item-content>
                                            </template>
                                            <template v-else>
                                                <v-list-item-content>
                                                    <v-list-item-title v-html="data.item.email"></v-list-item-title>
                                                </v-list-item-content>
                                            </template>
                                        </template>
                        </v-autocomplete>

                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" outlined :disabled="isLoading"
                            @click.native="closeDialog"
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
    </v-layout>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
    name: 'AnnouncementDialog',

    data: () => ({
        ////////////////
        // State data //
        ////////////////
        form: {
            id         : null,
            body       : '',
            action_text: '',
            action_url : '',
            users: [],
        },

        users: [],
        announcementDialog: false,
        isLoading         : false

    }),

    validations () {
        return {
            form: {
                body: { required, maxLength: maxLength(300) }
            }
        };
    },

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
        initialize () {
            this.form = {
                id         : null,
                body       : '',
                action_text: '',
                action_url : '',
                users: [],
            };
            this.getUsers();
            this.$v.$reset();
        },

        /**
         * Open current dialog
         * */
        new () {
            this.initialize();
            this.announcementDialog = true;
        },

        /**
         * Open current dialog
         * */
        edit (announcement) {
            this.announcementDialog = true;

            this.form.id          = announcement.id;
            this.form.icon        = announcement.icon;
            this.form.body        = announcement.body;
            this.form.action_text = announcement.action_text;
            this.form.action_url  = announcement.action_url;
        },

        /**
         * Remove user on chip 'close' click
         * */
        removeSelectedUsers (item) {
            const index = this.form.users.findIndex(user => user.email === item.email);
            if (index >= 0) this.form.users.splice(index, 1);
        },

        /**
         * Fetch all users available in the app
         * */
        getUsers () {
            this.isLoading = true;

            return API.users.all()
                      .then(response => {
                          this.users = response.data.users;
                      })
                      // .catch(Utils.standardErrorResponse)
                      .finally(() => this.isLoading = false);
        },

        save () {
            this.isLoading = true;
            this.$v.$touch();

            if (this.$v.$invalid) return;

            return API.announcements.save(this.form)
                .then(() => {
                    this.successCallback();
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {
                    this.isLoading = false;
                });
        },

        /**
         * Emit success and close dialog
         */
        successCallback () {
            Snotify.success('Announcement successfully saved!');
            this.isLoading = false;
            this.$emit('announcement-saved');
            this.closeDialog();
        },

        closeDialog () {
            this.announcementDialog = false;
            this.isLoading          = false;
            this.initialize();
        }
    }
};
</script>
