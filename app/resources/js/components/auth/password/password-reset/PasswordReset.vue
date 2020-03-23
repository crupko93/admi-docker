<template>
    <v-card>
        <v-toolbar dark color="primary" flat>
            <v-flex xs10>
                <v-toolbar-title>Password reset</v-toolbar-title>
            </v-flex>
            <v-flex xs2 class="text-right">
                <locale-dropdown></locale-dropdown>
            </v-flex>
        </v-toolbar>
        <v-card-text>
            <p>Choose a new password below</p>

            <password-reset-form @success="success"></password-reset-form>
        </v-card-text>
    </v-card>
</template>

<script>
import PasswordResetForm from './PasswordResetForm';
import LocaleDropdown    from '../../../common/LocaleDropdown';

export default {
    components: {
        PasswordResetForm,
        LocaleDropdown
    },

    methods: {
        success (form) {
            this.$router.push({name: 'login', query: {email: form.email}});
        }
    }
};
</script>
