<template>
    <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light
        :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" :value="mini"
        :width="300">
        <v-list class="py-0">
            <v-list-item>
                <v-list-item-icon v-show="$vuetify.breakpoint.mdAndUp && mini">
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>chevron_right</v-icon>
                    </v-btn>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-flex xs12 class="text-center">
                        <div class="image-placeholder">
                            <span role="img" class="profile-photo-preview" :style="previewStyle">
                                <v-icon v-if="user && !user.image" class="company-icon-placeholder">fas fa-user</v-icon>
                            </span>
                        </div>
                    </v-flex>
                    <v-list-item-title class="text-center">{{ user.first_name + ' ' + user.last_name }}</v-list-item-title>
                </v-list-item-content>

                <v-list-item-icon>
                    <v-btn small icon @click.native.stop="navToggle" class="mx-0">
                        <v-icon>chevron_left</v-icon>
                    </v-btn>
                </v-list-item-icon>
            </v-list-item>
        </v-list>

        <v-list class="py-0" dense v-for="(group, index) in items" :key="index">
            <v-divider class="mb-2" :class="{ 'mt-0': !index, 'mt-2': index }" v-if="group.length"></v-divider>

            <template v-for="item in group">
                <v-list-group
                    v-if="!!item.items"
                    :prepend-icon="item.icon"
                    no-action
                    :key="item.title"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </template>

                    <v-list-item
                        v-for="subItem in item.items"
                        :key="subItem.title"
                        @click="subItem.action ? subItem.action() : false"
                        :to="subItem.to"
                        ripple
                        :exact="subItem.exact !== undefined ? subItem.exact : true"
                    >
                        <v-list-item-content class="pl-2">
                            <v-list-item-title>{{ subItem.title }}</v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-icon>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                </v-list-group>

                <v-list-item
                    v-else
                    @click.native="item.action ? item.action() : false"
                    href="javascript:void(0)"
                    :to="item.to"
                    ripple
                    :exact="item.exact !== undefined ? item.exact : true"
                    :key="item.title"
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    props: ['mini'],

    data: () => ({
        items: [],
        user : {
            username             : null,
            first_name           : null,
            last_name            : null,
            image                : null
        }
    }),

    computed: {
        ...mapGetters({
            auth: 'auth/user'
        }),
        // Evaluate style attribute for photo preview
        previewStyle () {
            return _.has(this.user, 'image.path')
                ? `background-image: url(${this.user.image.path})`
                : '';
        }
    },

    methods: {
        initialize () {
            this.items = [];
            this.user = {
                username  : null,
                first_name: null,
                last_name : null,
                image     : null
            };
        },

        navToggle () {
            this.$emit('nav-toggle');
        },

        async logout () {
            await this.$store.dispatch('auth/logout');

            Snotify.info('You are logged out.');
            this.$router.push({name: 'login'});
        },

        navigation () {
            this.items = [];
            let temporaryItems = [
                [
                    {title: 'Dashboard', icon: 'far fa-chart-line', to: {name: 'dashboard'}, exact: false}
                ],
                [
                    {title: 'Profile', icon: 'person', to: {name: 'profile'}, exact: false}
                ],
                [
                    {title: 'Users', icon: 'fas fa-users', to: {name: 'users'}, exact: false, permission: 'read_administration_section'}
                ],
                [
                    {title: 'Roles and Permissions', icon: 'fas fa-user-tag', to: {name: 'roles'}, exact: false, permission: 'read_administration_section'}
                ],
                [
                    {title: 'Logout', icon: 'power_settings_new', action: this.logout}
                ]
            ];


            // Filter navbar items based on authenticated user's permissions
            temporaryItems.forEach((navItem) => {
                if (!navItem[0].permission || (navItem[0].permission && Utils.hasPermissionTo(navItem[0].permission))) {
                    this.items.push(navItem);
                }
            });
        }
    },

    mounted () {
        this.user = this.auth;
        this.navigation();
    },

    watch: {
        auth: {
            handler () {
                if (this.auth) {
                    this.user = this.auth;
                    this.navigation();
                } else {
                    this.initialize();
                }
            },
            deep: true
        }
    }
};
</script>
