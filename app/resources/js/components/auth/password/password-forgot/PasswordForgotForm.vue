<template>
    <div>
        <v-text-field
            v-model="form.email"
            type="email"
            @input="$v.form.email.$touch()"
            @blur="$v.form.email.$touch()"
            :label="$t('email')"
            :disabled="isLoading"
            :error-messages="emailErrors" required
        ></v-text-field>

        <v-layout class="mt-4 mx-0">
            <v-spacer></v-spacer>

            <v-btn
                color="grey darken-2"
                :disabled="isLoading"
                :to="{ name: 'login', query: {email: form.email} }"
                text exact
            >
                {{$t('back_to_login')}}
            </v-btn>

            <v-btn
                color="primary"
                class="ml-4"
                @click="submit"
                :loading="isLoading"
                :disabled="isLoading || $v.$invalid"
            >
                {{$t('get_password')}}
            </v-btn>
        </v-layout>
    </div>
</template>

<script>
import { email, required } from 'vuelidate/lib/validators';

export default {
    data: () => ({
        isLoading: false,

        labels: {
            email: 'Email'
        },

        form: {
            email: null
        }
    }),

    validations () {
        return {
            form: {
                email: {required, email}
            }
        };
    },

    computed: {
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.email && errors.push(this.$t('email_is_not_valid'));
            !this.$v.form.email.required && errors.push(this.$t('email_is_required'));
            return errors;
        }
    },

    methods: {
        submit () {
            this.$v.$touch();
            if (this.$v.$invalid) return;
            this.isLoading = true;
            return API.auth.resetPassword(this.form)
                .then(() => {

                    Snotify.success(this.$t('reset_password_email_sent'));
                    this.$emit('success');
                })
                .catch(Utils.standardErrorResponse)
                .finally(() => {
                    this.isLoading = false;
                });

        }
    },

    created () {
        this.form.email = this.$route.query.email || null;
    }
};
</script>
