<template>
    <div>
            <v-card>
                <v-card-title><h3>{{$t('edit_profile')}}</h3></v-card-title>
                <v-card-text class="profile">
                    <v-layout flex wrap justify-center align-center>

                    <v-flex xs12 md3 class="text-center">
                        <div class="image-placeholder">
                                <span role="img" class="profile-photo-preview" :style="previewStyle">
                                    <v-icon v-if="!form.image" class="company-icon-placeholder">fas fa-user</v-icon>
                                </span>
                        </div>
                        <div class="profile-uploader">
                            <!-- :client-id="company.id" -->
                        </div>
                    </v-flex>
                    <v-flex xs12 md9>
                        <h3>{{$t('profile_info')}}</h3>

                        <v-layout row wrap>
                            <v-flex xs12 sm6>
                                <div class="profile-info">
                                    <v-text-field
                                        v-model="form.username"
                                        @input="$v.form.username.$touch()"
                                        @blur="$v.form.username.$touch()"
                                        :label="$t('username')"
                                        :disabled="isLoading"
                                        :error-messages="usernameErrors" required
                                    ></v-text-field>
                                </div>

                                <div class="profile-info">
                                    <v-text-field
                                        v-model="form.first_name"
                                        @input="$v.form.first_name.$touch()"
                                        @blur="$v.form.first_name.$touch()"
                                        :label="$t('first_name')"
                                        :disabled="isLoading"
                                        :error-messages="firstNameErrors" required
                                    ></v-text-field>
                                </div>

                                <div class="profile-info">
                                    <v-text-field
                                        v-model="form.phone"
                                        type="email"
                                        :label="$t('phone')"
                                        :disabled="isLoading"
                                    ></v-text-field>
                                </div>
                            </v-flex>

                            <v-flex xs12 sm6>
                                <div class="profile-info">
                                    <v-text-field
                                        v-model="form.email"
                                        type="email"
                                        @input="$v.form.email.$touch()"
                                        @blur="$v.form.email.$touch()"
                                        :label="$t('email')"
                                        :disabled="isLoading"
                                        :error-messages="emailErrors" required
                                    ></v-text-field>
                                </div>

                                <div class="profile-info">
                                    <v-text-field
                                        v-model="form.last_name"
                                        @input="$v.form.last_name.$touch()"
                                        @blur="$v.form.last_name.$touch()"
                                        :label="$t('last_name')"
                                        :disabled="isLoading"
                                        :error-messages="lastNameErrors" required
                                    ></v-text-field>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12>
                            <h3>{{$t('security')}}</h3>

                            <v-layout row wrap>
                                <v-flex xs12 sm6>
                                    <div class="profile-info">
                                        <v-text-field
                                            v-model="form.password"
                                            hint="At least 6 characters"
                                            autocomplete="new-password"
                                            @input="$v.form.password.$touch()"
                                            @blur="$v.form.password.$touch()"
                                            @click:append="() => (passwordHidden = !passwordHidden)"
                                            :label="$t('password')"
                                            :append-icon="passwordHidden ? 'visibility_off' : 'visibility'"
                                            :type="passwordHidden ? 'password' : 'text'"
                                            :disabled="isLoading"
                                            :error-messages="passwordErrors" required
                                        ></v-text-field>
                                    </div>
                                </v-flex>

                                <v-flex xs12 sm6>
                                    <div class="profile-info">
                                        <v-text-field
                                            v-model="form.password_confirmation"
                                            autocomplete="new-password"
                                            @input="$v.form.password_confirmation.$touch()"
                                            @blur="$v.form.password_confirmation.$touch()"
                                            :label="$t('confirm_password')"
                                            :type="passwordHidden ? 'password' : 'text'"
                                            :disabled="isLoading"
                                            :error-messages="passwordConfirmationErrors"
                                        ></v-text-field>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
            <v-layout mt-12 mx-0>
                <v-spacer></v-spacer>

                <v-btn
                    color="grey darken-2"
                    :disabled="isLoading"
                    :to="{ name: 'profile' }"
                    text exact
                >
                    {{$t('cancel')}}
                </v-btn>

                <v-btn
                    color="primary"
                    class="ml-4"
                    @click="submit"
                    :loading="isLoading"
                    :disabled="isLoading"
                >
                    {{$t('save')}}
                </v-btn>
            </v-layout>
        </div>
</template>

<script>
import { mapGetters }                          from 'vuex';
import { email, required, requiredIf, sameAs } from 'vuelidate/lib/validators';

export default {
    data: () => ({
        passwordHidden: true,
        isLoading     : false,

        labels: {
            username             : 'Username',
            first_name           : 'First Name',
            last_name            : 'Last Name',
            email                : 'Email',
            phone                : 'Phone',
            password             : 'New Password',
            password_confirmation: 'Confirm New Password'
        },

        form: {
            username             : null,
            first_name           : null,
            last_name            : null,
            email                : null,
            phone                : null,
            password             : null,
            password_confirmation: null
        }
    }),

    validations () {
        return {
            form: {
                username             : {required},
                first_name           : {required},
                last_name            : {required},
                email                : {required, email},
                password             : {
                    required: requiredIf(function () {
                        return this.form.password_confirmation && this.form.password_confirmation.length > 0;
                    })
                },
                password_confirmation: {
                    sameAsPassword: sameAs('password')
                }
            }
        };
    },

    computed: {
        ...mapGetters({
            auth: 'auth/user'
        }),
        usernameErrors () {
            if (!this.$v.form.username.$dirty) return [];
            const errors = [];
            !this.$v.form.username.required && errors.push(this.$t('username')+' '+this.$t('is_required'));
            return errors;
        },
        firstNameErrors () {
            if (!this.$v.form.first_name.$dirty) return [];
            const errors = [];
            !this.$v.form.first_name.required && errors.push(this.$t('firts_name')+' '+this.$t('is_required'));
            return errors;
        },
        lastNameErrors () {
            if (!this.$v.form.last_name.$dirty) return [];
            const errors = [];
            !this.$v.form.last_name.required && errors.push(this.$t('last_name')+' '+this.$t('is_required'));
            return errors;
        },
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.email && errors.push(this.$t('email')+' '+this.$t('is_not_valid'));
            !this.$v.form.email.required && errors.push(this.$t('email')+' '+this.$t('is_required'));
            return errors;
        },
        passwordErrors () {
            if (!this.$v.form.password.$dirty) return [];
            const errors = [];
            !this.$v.form.password.required && errors.push(this.$t('password')+' '+this.$t('is_required'));
            return errors;
        },
        passwordConfirmationErrors () {
            if (!this.$v.form.password_confirmation.$dirty) return [];
            const errors = [];
            !this.$v.form.password_confirmation.sameAsPassword && errors.push(this.$t('password')+' '+this.$t('must_be_identical'));
            return errors;
        },
        // Evaluate style attribute for photo preview
        previewStyle () {
            return _.has(this.user, 'image.path')
                ? `background-image: url(${this.user.image.path})`
                : '';
        }
    },

    methods: {
        submit () {
            this.$v.$touch();
            if (this.$v.$invalid) return;
            this.isLoading = true;
            return API.users.updateProfile(this.form)
                .then(response => {
                    Snotify.success(this.$t('profile_successfully_updated'));
                    this.$store.dispatch('auth/setUser', response.data);
                    this.$emit('userUpdated');
                    this.$router.push({name: 'profile'});
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {
                    this.isLoading = false;
                });

        }
    },

    mounted () {
        this.form = Object.assign(this.form, this.auth);
    }
};
</script>
