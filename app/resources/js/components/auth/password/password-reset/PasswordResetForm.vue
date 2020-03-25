<template>
    <div>
        <v-text-field
            v-model="form.password"
            hint="At least 6 characters"
            @input="$v.form.password.$touch()"
            @blur="$v.form.password.$touch()"
            @click:append="() => (passwordHidden = !passwordHidden)"
            :label="$t('password')"
            :append-icon="passwordHidden ? 'visibility_off' : 'visibility'"
            :type="passwordHidden ? 'password' : 'text'"
            :disabled="isLoading"
            :error-messages="passwordErrors" required
        ></v-text-field>

        <v-text-field
            v-model="form.password_confirmation"
            @input="$v.form.password_confirmation.$touch()"
            @blur="$v.form.password_confirmation.$touch()"
            :label="$t('confirm_password')"
            :type="passwordHidden ? 'password' : 'text'"
            :disabled="isLoading"
            :error-messages="passwordConfirmationErrors" required
        ></v-text-field>

        <v-layout class="mt-4 mx-0">
            <v-spacer></v-spacer>

            <v-btn
                @click="submit"
                :loading="isLoading"
                :disabled="isLoading || $v.$invalid"
                color="primary"
            >
                {{$t('set_new_password')}}
            </v-btn>
        </v-layout>
    </div>
</template>

<script>
import { required, sameAs } from 'vuelidate/lib/validators';

export default {
    data: () => ({
        passwordHidden: true,
        isLoading     : false,

        labels: {
            password             : 'New Password',
            password_confirmation: 'Confirm New Password'
        },

        form: {
            token                : null,
            email                : null,
            password             : null,
            password_confirmation: null
        }
    }),

    validations () {
        return {
            form: {
                password             : {required},
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password')
                }
            }
        };
    },

    computed: {
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
            API.auth.resetPassword(this.form)
                .then(() => {
                    Snotify.success(this.$t('success_reset_password'));
                    this.$emit('success', this.form);
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {this.isLoading = false;});
        }
    },

    created () {
        this.form.email = this.$route.query.email;
        this.form.token = this.$route.params.token;
    }
};
</script>
