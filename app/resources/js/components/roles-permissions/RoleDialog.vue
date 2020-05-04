<template>
    <v-layout row justify-center>
        <v-dialog v-model="roleDialog" transition="dialog-bottom-transition" max-width="600">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        <span class="headline" v-if="roleId">{{$t('edit_role')}}</span>
                        <span class="headline" v-else>{{$t('add_role')}}</span>
                    </v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <!-- Name -->
                                <v-text-field required name="name" :label="$t('name')+'*'"
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
                                <v-autocomplete multiple chips required color="primary lighten-2"
                                    @input="$v.form.permissions.$touch()" @blur="$v.form.permissions.$touch()"
                                    :items="permissions"
                                    :error-messages="permissionsErrors"
                                    item-text="name"
                                    item-value="name"
                                    :label="$t('permissions')+'*'"
                                    v-model="form.permissions"
                                    return-object
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

                        <v-layout row wrap>
                            <v-flex xs12>
                                <!-- Permissions -->
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
                            </v-flex>
                        </v-layout>

                        </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="primary" outlined
                        @click="closeDialog"
                        :disabled="isLoading">
                        {{$t('cancel')}}
                    </v-btn>

                    <v-btn color="primary" :disabled="isLoading"
                        @click="submit"
                    >
                    <span v-if="isLoading">
                        <v-icon>fa fa-spinner fa-spin</v-icon> Saving
                    </span>
                        <span v-else>{{$t('save')}}</span>
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
        users: [],
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
                permissions: {required},
                users: {required}
            }
        };
    },

    computed: {
        nameErrors () {
            if (!this.$v.form.name.$dirty) return [];
            const errors = [];
            !this.$v.form.name.required && errors.push(this.$t('name')+' '+this.$t('is_required'));
            return errors;
        },
        permissionsErrors () {
            if (!this.$v.form.permissions.$dirty) return [];
            const errors = [];
            !this.$v.form.permissions.required && errors.push(this.$t('one_permission_required'));
            return errors;
        },
        usersErrors (){
            if (!this.$v.form.users.$dirty) return [];
            const errors = [];
            !this.$v.form.users.required && errors.push(this.$t('user')+' '+this.$t('is_required'));
            return errors;
        }
    },

    methods: {
        initialize () {
            this.form = {
                id         : '',
                name       : '',
                permissions: [],
                users: []
            };

            this.permissions = [];
            this.users = [];

            this.$v.$reset();
        },

        /**
         * Remove permission on chip 'close' click
         * */
        removeSelectedPermissions (item) {
            const index = this.form.permissions.findIndex(permission => permission.name === item.name);
            if (index >= 0) this.form.permissions.splice(index, 1);
        },

        /**
         * Remove user on chip 'close' click
         * */
        removeSelectedUsers (item) {
            const index = this.form.users.findIndex(user => user.email === item.email);
            if (index >= 0) this.form.users.splice(index, 1);
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
         * Fetch all users available in the app
         * */
        getUsers () {
            this.isLoading = true;

            return API.users.all()
                      .then(response => {
                          this.users = response.data.users;
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

            if (this.$v.form.$invalid) {
                this.isLoading = false;
                return;
            }

            return API.roles.create(this.form)
                .then(this.successCallback)
                .catch(e => {
                    Utils.standardErrorResponse(e);
                })
                .finally(() => this.isLoading = false);
        },

        updateRole () {
            this.$v.form.$touch();

            if (this.$v.form.$invalid) {
                this.isLoading = false;
                return;
            }

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
            Snotify.success(this.$t('role_saved') + '!', this.$t('success') + '!');
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
                    this.getUsers();
                }
            },
            deep: true
        }
    }
};
</script>
