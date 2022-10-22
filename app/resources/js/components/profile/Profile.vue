<template>
    <div>
        <v-card>
            <v-card-title><h3>Profile</h3></v-card-title>
            <v-card-text class="profile">
                <v-toolbar class="shadow-0 py-0" color="white" v-if="!displayEditProfile">
                    <v-btn icon @click="goBack">
                        <v-icon>chevron_left</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-menu bottom left>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" icon>
                                <v-icon>more_vert</v-icon>
                            </v-btn>
                        </template>

                        <v-list>

                        </v-list>
                    </v-menu>
                </v-toolbar>
                <v-layout flex wrap justify-center align-center mb-8>
                    <v-flex xs12 sm3 class="text-center">
                        <div class="image-placeholder">
                            <span role="img" class="profile-photo-preview" :style="previewStyle">
                                <v-icon v-if="!userData.image" class="company-icon-placeholder">fas fa-user</v-icon>
                            </span>
                        </div>
                        <div class="profile-uploader" v-if="displayEditProfile">
                            <!-- :client-id="company.id" -->
                            <router-link :to="{ name: 'profile-edit' }">Edit profile</router-link>
                        </div>
                    </v-flex>

                    <v-flex xs12 sm9>
                        <h3>Profile Information</h3>

                        <v-layout wrap>
                            <v-flex xs12 sm6>
                                <div class="profile-info">
                                    <span>Username</span>
                                    {{ userData.username || '-' }}
                                </div>

                                <div class="profile-info">
                                    <span>First Name</span>
                                    {{ userData.first_name || '-' }}
                                </div>

                                <div class="profile-info">
                                    <span>Phone</span>
                                    {{ userData.phone || '-' }}
                                </div>
                            </v-flex>

                            <v-flex xs12 sm6>
                                <div class="profile-info">
                                    <span>Email</span>
                                    {{ userData.email || '-' }}
                                </div>

                                <div class="profile-info">
                                    <span>Last Name</span>
                                    {{ userData.last_name || '-' }}
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import lodash         from 'lodash';

export default {
    props: {
        // Current user instance
        user: {type: Object, default: null}
    },

    data: () => ({
        userData: {
            username             : null,
            first_name           : null,
            last_name            : null,
            email                : null,
            phone                : null,
            password             : null,
            password_confirmation: null,
            image                : null
        },

        displayEditProfile: false
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
        goBack () {
            this.$emit('back');
        }
    },

    mounted () {
        if (!this.user && !this.userData.username) {
            this.userData           = Object.assign(this.userData, this.auth);
            this.displayEditProfile = true;
        } else {
            this.userData = this.user;
        }
    },

    watch: {
        user: {
            handler (values) {
                this.userData = values;
            },
            deep: true
        }
    }
};
</script>
