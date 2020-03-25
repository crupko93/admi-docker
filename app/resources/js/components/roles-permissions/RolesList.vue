<template>
    <div>
        <div>
            <v-layout wrap>
                <v-flex xs12 class="text-right pr-4">
                    <v-btn color="primary" fab outlined small @click="$refs.roleDialog.new()">
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
                :items="roles"
                :server-items-length="roleCount"
                :options.sync="pagination"
                :footer-props="{itemsPerPageOptions: [20, 50, 100]}"
            >
                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <template slot="item" slot-scope="props">
                    <tr>
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.permissions.length > 0 ? lodash.map(props.item.permissions, 'label') : '-' }}</td>
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
                                    <!-- [ALL] Edit -->
                                    <v-list-item @click="$refs.roleDialog.edit(props.item.id)">
                                        <v-list-item-title>{{$t('edit')}}</v-list-item-title>
                                    </v-list-item>

                                    <!-- [ALL] Delete -->
                                    <v-list-item @click="deleteRole(props.item)">
                                        <v-list-item-title>{{$t('delete')}}</v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </td>
                    </tr>
                </template>
            </v-data-table>

        </div>

        <!-- Role Dialog -->
        <RoleDialog ref="roleDialog" @role-save="retrieveRoles"></RoleDialog>
    </div>
</template>

<script>

import RoleDialog     from './RoleDialog';
import lodash         from 'lodash';

export default {
    name: 'RoleList',

    components: {
        RoleDialog
    },

    props: {
        // Role to use for role retrieval
        role: {type: String}
    },

    data: () => ({
        lodash,
        /////////////////
        // Remote data //
        /////////////////
        // List of roles and total count (to be retrieved on pagination change)
        roles    : [],
        roleCount: 0,

        ////////////////
        // State data //
        ////////////////
        // Role data table loading state
        isLoading: false,
        isShowProfile: false,

        // Data table headers
        headers: [
            {
                text    : 'Name',
                align   : 'left',
                sortable: true
            }, {
                text    : 'Permissions',
                align   : 'left',
                sortable: true
            }, {
                text    : 'Actions',
                value   : '',
                align   : 'center',
                sortable: false
            }
        ],

        // Role data table search term
        searchTerm: '',

        // Role selected from the table
        selectedRole: null,

        // Role data table pagination object
        pagination: {}
    }),

    methods: {
        retrieveRoles () {
            this.isLoading = true;

            return API.roles.all(Object.assign(this.pagination, {
                role  : this.role,
                search: this.searchTerm || null
            }))
                .then(r => {
                    this.roles     = r.data.roles;
                    this.roleCount = r.data.meta.total;
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => { this.isLoading = false; });
        },

        updateRole (role) {
            return API.roles.updateRole({id: role.id, role})
                .then(() => {
                    this.retrieveRoles();
                    Snotify.success('Role updated!');
                })
                .catch(Utils.standardErrorResponse);
        },

        deleteRole (role) {
            swal({
                text: `Role (${role.name}) and all related data will be permanently deleted!`,

                title    : 'Are you sure?',
                icon     : 'info',
                className: 'swal-info',
                buttons  : {
                    cancel : {
                        text      : 'Cancel',
                        visible   : true,
                        closeModal: true
                    },
                    confirm: {
                        text      : 'Ok',
                        closeModal: false
                    }
                }
            })
                .then(confirm => {
                    if (!confirm) return;

                    this.isLoading = true;

                    API.roles.delete(role.id)
                        .then(() => {
                            Snotify.success('Role successfully deleted!');
                            this.retrieveRoles();
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
                this.retrieveRoles();
            },
            deep: true
        },

        searchTerm: {
            handler:
                lodash.debounce(function () {
                    this.retrieveRoles();
                }, 500)

        }
    }
};
</script>
