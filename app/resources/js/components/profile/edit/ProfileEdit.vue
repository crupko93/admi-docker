<template>
    <div>
        <h2 class="mb-4 primary--text headline">Edit Profile</h2>
        <div class="text-center">
            <v-avatar class="mb-4" color="primary" size="126">
                <v-icon dark size="126">mdi-account-circle</v-icon>
            </v-avatar>
        </div>
        <div>
            <v-card>
                <v-card-text>
                    <v-text-field
                        v-model="form.name"
                        @input="$v.form.name.$touch()"
                        @blur="$v.form.name.$touch()"
                        :label="labels.name"
                        :disabled="isLoading"
                        :error-messages="nameErrors" required
                    ></v-text-field>

                    <v-text-field
                        v-model="form.email"
                        type="email"
                        @input="$v.form.email.$touch()"
                        @blur="$v.form.email.$touch()"
                        :label="labels.email"
                        :disabled="isLoading"
                        :error-messages="emailErrors" required
                    ></v-text-field>
                </v-card-text>
            </v-card>

            <h3 class="headline mb-4 mt-12">Password</h3>

            <v-card>
                <v-card-text>
                    <v-text-field
                        v-model="form.password"
                        hint="At least 6 characters"
                        autocomplete="new-password"
                        @input="$v.form.password.$touch()"
                        @blur="$v.form.password.$touch()"
                        @click:append="() => (passwordHidden = !passwordHidden)"
                        :label="labels.password"
                        :append-icon="passwordHidden ? 'visibility_off' : 'visibility'"
                        :type="passwordHidden ? 'password' : 'text'"
                        :disabled="isLoading"
                        :error-messages="passwordErrors" required
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                        @input="$v.form.password_confirmation.$touch()"
                        @blur="$v.form.password_confirmation.$touch()"
                        :label="labels.password_confirmation"
                        :type="passwordHidden ? 'password' : 'text'"
                        :disabled="isLoading"
                        :error-messages="passwordConfirmationErrors" required
                    ></v-text-field>
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
                    Cancel
                </v-btn>

                <v-btn
                    color="primary"
                    class="ml-4"
                    @click="submit"
                    :loading="isLoading"
                    :disabled="isLoading"
                >
                    Save
                </v-btn>
            </v-layout>
        </div>
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
            name                 : 'Name',
            email                : 'Email',
            password             : 'New Password',
            password_confirmation: 'Confirm New Password'
        },

        form: {
            name                 : null,
            email                : null,
            password             : null,
            password_confirmation: null
        }
    }),

    validations () {
        return {
            form: {
                name                 : {required},
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
        nameErrors () {
            if (!this.$v.form.name.$dirty) return [];
            const errors = [];
            !this.$v.form.name.required && errors.push('Name is required!');
            return errors;
        },
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.email && errors.push('Email is not valid!');
            !this.$v.form.email.required && errors.push('Email is required!');
            return errors;
        },
        passwordErrors () {
            if (!this.$v.form.password.$dirty) return [];
            const errors = [];
            !this.$v.form.password.required && errors.push('Password is required!');
            return errors;
        },
        passwordConfirmationErrors () {
            if (!this.$v.form.password_confirmation.$dirty) return [];
            const errors = [];
            !this.$v.form.password_confirmation.required && errors.push('Password confirmation is required!');
            !this.$v.form.password_confirmation.sameAsPassword && errors.push('Passwords must be identical!');
            return errors;
        }
    },

    methods: {
        submit () {
            this.$v.$touch();
            if (this.$v.$invalid) return;
            this.isLoading = true;
            return API.users.updateProfile(this.form)
                .then(response => {
                    Snotify.success('Your profile successfully updated.');
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
