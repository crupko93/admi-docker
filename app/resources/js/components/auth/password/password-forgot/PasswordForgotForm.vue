<template>
    <div>
        <v-text-field
            v-model="form.email"
            type="email"
            @input="$v.form.email.$touch()"
            @blur="$v.form.email.$touch()"
            :label="labels.email"
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
                Back to login
            </v-btn>

            <v-btn
                color="primary"
                class="ml-4"
                @click="submit"
                :loading="isLoading"
                :disabled="isLoading || $v.$invalid"
            >
                Get password
            </v-btn>
        </v-layout>
    </div>
</template>

<script>
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

    computed: {
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.email && errors.push('Email is not valid!');
            !this.$v.form.email.required && errors.push('Email is required!');
            return errors;
        }
    },

    methods: {
        submit () {
            if (this.$refs.form.validate()) {
                this.isLoading = true;
                return API.auth.resetPassword(this.form)
                    .then(() => {
                        Snotify.success(
                            'An email with password reset instructions has been sent to your email address.');
                        this.$emit('success');
                    })
                    .catch(Utils.standardErrorResponse)
                    .finally(() => {
                        this.isLoading = false;
                    });
            }
        }
    },

    created () {
        this.form.email = this.$route.query.email || null;
    }
};
</script>
