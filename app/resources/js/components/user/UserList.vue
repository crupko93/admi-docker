<template>
    <div>
        <div v-if="!isShowProfile">
            <v-layout wrap>
                <v-flex xs12 class="text-right pr-4">
                    <v-btn color="primary" fab outlined small @click="$refs.userDialog.new()">
                        <v-icon>far fa-plus</v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>

            <v-card-text>
                <v-text-field flat solo-inverted single-line hide-details clearable
                    class="datatable-search"
                    append-icon="search" :label="$t('search')"
                    v-model="searchTerm"
                ></v-text-field>
            </v-card-text>

            <v-data-table
                :loading="isLoading"
                :headers="headers"
                :items="users"
                :server-items-length="userCount"
                :options.sync="pagination"
                :footer-props="{itemsPerPageOptions: [20, 50, 100]}"
            >
                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <template slot="item" slot-scope="props">
                    <tr>
                        <td>{{ props.item.username }}</td>
                        <td>{{ props.item.first_name }} {{ props.item.last_name }}</td>
                        <td>{{ props.item.email }}</td>
                        <td>{{ props.item.role[0] }}</td>
                        <td class="text-center px-0">
                            <v-menu offset-y>
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                        v-on="on"
                                        color="primary" text icon
                                    >
                                        <v-icon>more_vert</v-icon>
                                    </v-btn>
                                </template>
                                <v-list>
                                    <!-- [ALL] View Profile -->
                                    <!-- <v-list-tile @click="showUserProfile(props.item)"> -->
                                    <v-list-item @click="selectedUser = props.item; isShowProfile = !isShowProfile">
                                        <v-list-item-title>{{$t('view_profile')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [Moderator] Make Admin -->
                                    <v-list-item @click="updateRole(props.item, 'admin')"
                                        v-if="props.item.role === 'moderator'"
                                    >
                                        <v-list-item-title>{{$t('make_admin')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [Admin] Make Moderator -->
                                    <v-list-item @click="updateRole(props.item, 'moderator')"
                                        v-if="props.item.role === 'admin'"
                                    >
                                        <v-list-item-title>{{$t('make_moderator')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [ALL] Change Password -->
                                    <v-list-item @click="$refs.changePassword.open(props.item)">
                                        <v-list-item-title>{{$t('change_password')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [ALL] Edit -->
                                    <!-- <v-list-item @click="$refs.userDialog.edit(props.item.id)" -->
                                    <v-list-item @click="$refs.userDialog.edit(props.item.id)">
                                        <v-list-item-title>{{$t('edit')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [ALL] Delete -->
                                    <v-list-item @click="deleteUser(props.item)">
                                        <v-list-item-title>{{$t('delete')}}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </td>
                    </tr>
                </template>
            </v-data-table>

        </div>

        <Profile v-if="isShowProfile" @back="isShowProfile = false; selectedUser = null" :user="selectedUser"></Profile>
        <!-- Change Password Dialog -->
        <ChangePassword ref="changePassword"></ChangePassword>
        <!-- User Dialog -->
        <UserDialog ref="userDialog" @user-save="retrieveUsers"></UserDialog>
    </div>
</template>

<script>

import ChangePassword from './ChangePassword';
import UserDialog     from './UserDialog';
import { mapGetters } from 'vuex';
import lodash         from 'lodash';
import Profile        from '../profile/Profile';

export default {
    name: 'UserList',

    components: {
        Profile,
        UserDialog,
        ChangePassword
    },

    props: {
        // Role to use for user retrieval
        role: {type: String}
    },

    data: vm => ({
        /////////////////
        // Remote data //
        /////////////////
        // List of users and total count (to be retrieved on pagination change)
        users    : [],
        userCount: 0,

        ////////////////
        // State data //
        ////////////////
        // User data table loading state
        isLoading: false,
        isShowProfile: false,

        // Data table headers
        headers: [
            {
                text    : vm.$t('username'),
                value   : 'username',
                align   : 'left'
            }, {
                text    : vm.$t('name'),
                value   : 'first_name',
                align   : 'left'
            }, {
                text    : vm.$t('email'),
                value   : 'email',
                align   : 'left'
            }, {
                text    : vm.$t('role'),
                value   : 'roles',
                align   : 'left',
                sortable: false
            }, {
                text    : vm.$t('actions'),
                value   : '',
                align   : 'center',
                sortable: false
            }
        ],

        // User data table search term
        searchTerm: '',

        // User selected from the table
        selectedUser: null,

        // User data table pagination object
        pagination: {}
    }),

    computed: {
        ...mapGetters({
            user  : 'auth/user',
            locale: 'lang/locale'
        })
    },

    methods: {
        retrieveUsers () {
            this.isLoading = true;

            return API.users.all(Object.assign(this.pagination, {
                role  : this.role,
                search: this.searchTerm || null
            }))
                .then(r => {
                    this.users     = r.data.users;
                    this.userCount = r.data.meta.total;
                    this.$emit('retrieve-count', this.userCount);
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => { this.isLoading = false; });
        },

        updateRole (user, role) {
            return API.users.updateRole({id: user.id, role})
                .then(() => {
                    this.retrieveUsers();
                    Snotify.success(this.$t('user_role_updated') + '!');
                })
                .catch(Utils.standardErrorResponse);
        },

        deleteUser (user) {
            swal({
                text: this.$t('user_will_be_deleted').replace('*name*', user.first_name+' '+user.last_name),

                title    : this.$t('are_you_sure'),
                icon     : 'info',
                className: 'swal-info',
                buttons  : {
                    cancel : {
                        text      : this.$t('cancel'),
                        visible   : true,
                        closeModal: true
                    },
                    confirm: {
                        text      : this.$t('ok'),
                        closeModal: false
                    }
                }
            })
                .then(confirm => {
                    if (!confirm) return;

                    this.isLoading = true;

                    API.users.delete(user.id)
                        .then(() => {
                            Snotify.success(this.$t('user_successfully_deleted') + '!');
                            this.retrieveUsers();
                        })
                        .catch(e => {
                            Utils.standardErrorResponse(e);
                            this.isLoading = false;
                        })
                        .finally(swal.close);
                });
        }
    },

    watch: {
        pagination: {
            handler () {
                this.retrieveUsers();
            },
            deep: true
        },

        searchTerm: {
            handler:
                lodash.debounce(function () {
                    this.retrieveUsers();
                }, 500)

        },

        locale: {
            handler () {
                this.headers = [
                    {
                        text    : this.$t('username'),
                        value   : 'username',
                        align   : 'left'
                    }, {
                        text    : this.$t('name'),
                        value   : 'first_name',
                        align   : 'left'
                    }, {
                        text    : this.$t('email'),
                        value   : 'email',
                        align   : 'left'
                    }, {
                        text    : this.$t('role'),
                        value   : 'roles',
                        align   : 'left',
                        sortable: false
                    }, {
                        text    : this.$t('actions'),
                        value   : '',
                        align   : 'center',
                        sortable: false
                    }
                ];
            },
            deep: true
        }
    }
};
</script>
