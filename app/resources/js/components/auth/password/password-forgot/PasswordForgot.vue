<template>
    <v-card>
        <v-toolbar dark color="primary" flat>
            <v-flex xs10>
                <v-toolbar-title>{{$t('reset_password')}}</v-toolbar-title>
            </v-flex>
            <v-flex xs2 class="text-right">
                <locale-dropdown></locale-dropdown>
            </v-flex>
        </v-toolbar>
        <v-card-text>
            <p>{{$t('enter_email_will_receive_password')}}</p>

            <password-forgot-form @success="success"></password-forgot-form>
        </v-card-text>
    </v-card>
</template>

<script>
import PasswordForgotForm from './PasswordForgotForm';
import LocaleDropdown     from '../../../common/LocaleDropdown';

export default {
    components: {
        PasswordForgotForm,
        LocaleDropdown
    },

    methods: {
        success (data) {
            this.$router.push({name: 'login'});
        }
    }
};
</script>
