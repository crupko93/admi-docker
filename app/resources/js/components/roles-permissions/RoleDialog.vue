<template>
    <v-layout row justify-center>
        <v-dialog v-model="roleDialog" transition="dialog-bottom-transition" max-width="600">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        <span class="headline" v-if="roleId">Edit Role</span>
                        <span class="headline" v-else>Add Role</span>
                    </v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <!-- Name -->
                                <v-text-field required name="name" label="Name*"
                                    @input="$v.form.name.$touch()"
                                    @blur="$v.form.name.$touch()"
                                    :error-messages="nameErrors"
                                    v-model="form.name"
                                ></v-text-field>
                            </v-flex>
                        </v-layout>

                        <v-layout row wrap>
                            <v-flex xs12>
                                <!-- Permissions -->
                                <v-autocomplete multiple chips color="primary lighten-2" required
                                    @input="$v.form.permissions.$touch()" @blur="$v.form.permissions.$touch()"
                                    :items="permissions"
                                    :error-messages="permissionsErrors"
                                    item-text="name"
                                    item-value="id"
                                    label="Permissions*"
                                    v-model="form.permissions"
                                >
                                    <template slot="selection" slot-scope="data">
                                        <v-chip close class="chip--select-multi"
                                            @click:close="removeSelectedPermissions(data.item)"
                                            :input-value="data.selected"
                                        >
                                            {{ data.item.name }}
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
                                                <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                            </v-list-item-content>
                                        </template>
                                    </template>
                                </v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="primary" outlined
                        @click="closeDialog"
                        :disabled="isLoading">Cancel
                    </v-btn>

                    <v-btn color="primary" :disabled="isLoading"
                        @click="submit"
                    >
            <span v-if="isLoading">
                <v-icon>fa fa-spinner fa-spin</v-icon> Saving
            </span>
                        <span v-else>Save</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
const {required} = require('vuelidate/lib/validators');
import store from '~/store/index';

export default {
    name: 'RoleDialog',

    data: () => ({
        /////////////////
        // Remote data //
        /////////////////
        permissions: [],
        roleId     : null,

        ////////////////
        // State data //
        ////////////////
        form: {},

        roleDialog              : false,
        showPassword            : false,
        showPasswordConfirmation: false,
        isLoading               : false

    }),

    validations () {
        return {
            form: {
                name       : {required},
                permissions: {required}
            }
        };
    },

    computed: {
        nameErrors () {
            if (!this.$v.form.name.$dirty) return [];
            const errors = [];
            !this.$v.form.name.required && errors.push('Name is required!');
            return errors;
        },
        permissionsErrors () {
            if (!this.$v.form.permissions.$dirty) return [];
            const errors = [];
            !this.$v.form.permissions.required && errors.push('At least one permissions is required!');
            return errors;
        }
    },

    methods: {
        initialize () {
            this.form = {
                id         : '',
                name       : '',
                permissions: []
            };

            this.permissions = [];

            this.$v.$reset();
        },

        /**
         * Remove permission on chip 'close' click
         * */
        removeSelectedPermissions (item) {
            const index = this.form.permissions.findIndex(permission => permission.id === item.id);
            if (index >= 0) this.form.permissions.splice(index, 1);
        },

        /**
         * Fetch all permissions available in the app
         * */
        getPermissions () {
            this.isLoading = true;

            return API.permissions.all()
                .then(response => {
                    this.permissions = response.data.permissions;
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => this.isLoading = false);
        },

        /**
         * Open current dialog
         * */
        new () {
            this.roleId     = null;
            this.roleDialog = true;
        },

        /**
         * Open current dialog and fetch role based on 'id' provided from props
         * */
        edit (id) {
            this.roleId     = id;
            this.roleDialog = true;
            this.isLoading  = true;

            return API.roles.get(id)
                .then(response => {
                    response = response.data.role;

                    this.form = {
                        id         : response.id,
                        name       : response.name,
                        permissions: response.permissions
                    };
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => this.isLoading = false);
        },

        submit () {
            this.isLoading = true;
            return this.roleId
                ? this.updateRole()
                : this.createRole();
        },

        createRole () {
            this.$v.form.$touch();

            this.$v.passwordForm.$touch();

            if (this.$v.form.$invalid || this.$v.passwordForm.$invalid) {
                this.isLoading = false;
                return;
            }

            return API.roles.create(Object.assign({}, this.form, this.passwordForm, this.credentials))
                .then(this.successCallback)
                .catch(e => {
                    Utils.standardErrorResponse(e);
                    this.isLoading = false;
                });
        },

        updateRole () {
            this.$v.form.$touch();

            if (this.$v.form.$invalid) {
                this.isLoading = false;
                return;
            }
            console.log(this.form);
            return API.roles.update(this.form)
                .then(() => {
                    store.dispatch('auth/fetchUser');
                    this.successCallback();
                })
                .catch(e => {
                    Utils.standardErrorResponse(e);
                })
                .finally(() => this.isLoading = false);
        },

        /**
         * Emit success and close dialog
         */
        successCallback () {
            Snotify.success('Role saved!', 'Success!');
            this.$emit('role-save');
            this.closeDialog();
        },

        closeDialog () {
            this.roleDialog = false;
            this.isLoading  = false;
            this.initialize();
        }
    },

    watch: {
        roleDialog: {
            handler (value) {
                if (value) {
                    this.initialize();
                    this.getPermissions();
                }
            },
            deep: true
        }
    }
};
</script>
