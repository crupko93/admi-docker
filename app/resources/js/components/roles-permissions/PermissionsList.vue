<template>
    <div>
        <div>
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
                :items="permissions"
                :server-items-length="permissionsCount"
                :options.sync="pagination"
                :footer-props="{itemsPerPageOptions: [20, 50, 100]}"
            >
                <v-progress-linear slot="progress" color="blue" height="3" indeterminate></v-progress-linear>

                <template slot="item" slot-scope="props">
                    <tr>
                        <td>{{ props.item.label }}</td>
                        <td>{{ props.item.name }}</td>
                    </tr>
                </template>
            </v-data-table>

        </div>
    </div>
</template>

<script>

import lodash         from 'lodash';

export default {
    name: 'PermissionsList',

    data: () => ({
        /////////////////
        // Remote data //
        /////////////////
        // List of permissions
        permissions    : [],
        permissionsCount: 0,

        ////////////////
        // State data //
        ////////////////
        // Permission data table loading state
        isLoading: false,
        isShowProfile: false,

        // Data table headers
        headers: [
            {
                text    : 'Label',
                value   : 'label',
                align   : 'left'
            }, {
                text    : 'Name',
                value   : 'name',
                align   : 'left'
            }
        ],

        // Permission data table search term
        searchTerm: '',

        // Permission selected from the table
        selectedPermission: null,

        // Permission data table pagination object
        pagination: {}
    }),

    methods: {
        retrievePermissions () {
            this.isLoading = true;

            return API.permissions.all(Object.assign(this.pagination, {
                search: this.searchTerm || null
            }))
                .then(r => {
                    this.permissions     = r.data.permissions;
                    this.permissionsCount = r.data.meta.total;
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => { this.isLoading = false; });
        },

        deletePermission (permission) {
            swal({
                text: `Permission (${permission.name}) and all related data will be permanently \
                    deleted!`,

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

                    API.permissions.delete(permission.id)
                        .then(() => {
                            Snotify.success('Permission successfully deleted!');
                            this.retrievePermissions();
                        })
                        .catch(e => {
                            Utils.standardErrorResponse(e);
                        })
                        .finally(() => {
                            this.isLoading = false;
                            return swal.close;
                        });
                });
        },
    },

    watch: {
        pagination: {
            handler () {
                this.retrievePermissions();
            },
            deep: true
        },

        searchTerm: {
            handler:
                lodash.debounce(function () {
                    this.retrievePermissions();
                }, 500)

        }
    }
};
</script>
