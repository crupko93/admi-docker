<template>
    <div class="text-center">
        <v-form v-if="!isLoading">
            <v-text-field
                v-model="form.email"
                :label="$t('username/email')"
                prepend-icon="person"
                type="email"
                @input="$v.form.email.$touch()"
                @blur="$v.form.email.$touch()"
                :disabled="isLoading"
                :error-messages="emailErrors" required
            ></v-text-field>

            <v-text-field
                v-model="form.password"
                :label="$t('password')"
                prepend-icon="lock"
                @input="$v.form.password.$touch()"
                @blur="$v.form.password.$touch()"
                @click:append="() => (passwordHidden = !passwordHidden)"
                :append-icon="passwordHidden ? 'visibility_off' : 'visibility'"
                :type="passwordHidden ? 'password' : 'text'"
                :disabled="isLoading"
                :error-messages="passwordErrors" required
            ></v-text-field>

            <v-layout class="mt-4 mx-0">
                <v-spacer></v-spacer>

                <v-btn
                    color="grey darken-2"
                    :disabled="isLoading"
                    :to="{ name: 'forgot', query: {email: form.email} }"
                    text
                >
                    {{$t('forgot_password')}}
                </v-btn>

                <v-btn
                    color="primary"
                    class="ml-4"
                    type="submit"
                    :loading="isLoading"
                    :disabled="isLoading || $v.$invalid"
                    @click.prevent="submit"
                >
                    {{$t('login')}}
                </v-btn>
            </v-layout>
        </v-form>
    </div>
</template>

<script>
import { required, email } from 'vuelidate/lib/validators';

export default {
    data: () => ({
        passwordHidden: true,

        form: {
            email   : null,
            password: null
        },

        isLoading: false
    }),

    validations: {
        form: {
            email   : {required},
            password: {required}
        }
    },

    computed: {
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.required && errors.push(this.$t('username/email')+' '+this.$t('is_required'));
            return errors;
        },
        passwordErrors () {
            if (!this.$v.form.password.$dirty) return [];
            const errors = [];
            !this.$v.form.password.required && errors.push(this.$t('password')+' '+this.$t('is_required'));
            return errors;
        }
    },

    methods: {
        submit () {
            this.$v.$touch();
            if (this.$v.$invalid) return;

            this.$emit('isLoading', true);

            return API.auth.login(this.form)
                .then(response => {
                    Snotify.success(this.$t('welcome_back'));
                    this.$emit('success', response.data);
                })
                .catch(error => {
                    Utils.standardErrorResponse(error);
                    this.$emit('isLoading', false);
                });
        }
    },

    created () {
        this.form.email = this.$route.query.email || null;
    }
};
</script>
