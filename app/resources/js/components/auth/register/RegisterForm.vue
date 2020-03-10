<template>
    <div>
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

        <v-text-field
            v-model="form.password"
            hint="At least 6 characters"
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
            @input="$v.form.password_confirmation.$touch()"
            @blur="$v.form.password_confirmation.$touch()"
            :label="labels.password_confirmation"
            :type="passwordHidden ? 'password' : 'text'"
            :disabled="isLoading"
            :error-messages="passwordConfirmationErrors" required
        ></v-text-field>

        <v-layout row class="mt-4 mx-0">
            <v-spacer></v-spacer>

            <v-btn
                color="grey darken-2"
                :disabled="isLoading"
                :to="{ name: 'login' }"
                text exact
            >
                Back to login
            </v-btn>

            <v-btn
                type="submit"
                color="primary"
                class="ml-4"
                @click="submit"
                :loading="isLoading"
                :disabled="isLoading || $v.$invalid"
            >
                Register
            </v-btn>
        </v-layout>
    </div>
</template>

<script>
import { email, required, sameAs } from 'vuelidate/lib/validators';

export default {
    data: () => ({
        passwordHidden: true,
        isLoading       : false,

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
                password             : {required},
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password')
                }
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
            return API.auth.register(this.form)
                .then(() => {
                    Snotify.success('You have been successfully registered!');
                    this.$emit('success');
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {
                    this.isLoading = false;
                });
        }
        
    }
};
</script>
