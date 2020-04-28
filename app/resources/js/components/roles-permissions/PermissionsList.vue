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

            </v-data-table>

        </div>
    </div>
</template>

<script>

import lodash         from 'lodash';
import { mapGetters } from 'vuex';

export default {
    name: 'PermissionsList',

    data: vm => ({
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
                text    : vm.$t('label'),
                value   : 'label',
                align   : 'left'
            }, {
                text    : vm.$t('name'),
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

    computed: mapGetters({
        locale: 'lang/locale'
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
                text: this.$t('permission_will_be_deleted').replace('*name*', permission.name),

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

                    API.permissions.delete(permission.id)
                        .then(() => {
                            Snotify.success(this.$t('permission_successfully_deleted'));
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

        },

        locale: {
            handler () {
                this.headers = [
                    {
                        text    : this.$t('label'),
                        value   : 'label',
                        align   : 'left'
                    }, {
                        text    : this.$t('name'),
                        value   : 'name',
                        align   : 'left'
                    }
                ];
            },
            deep: true
        }
    }
};
</script>
