<template>
    <v-flex sm8 md6 lg4>
        <v-card>
            <v-toolbar dark color="primary" flat>
                <v-flex xs10>
                    <v-toolbar-title>Register</v-toolbar-title>
                </v-flex>
                <v-flex xs2 class="text-right">
                    <locale-dropdown></locale-dropdown>
                </v-flex>
            </v-toolbar>
            <v-card-text>
                <register-form @success="success"></register-form>
            </v-card-text>
        </v-card>
    </v-flex>
</template>

<script>
import RegisterForm       from './RegisterForm';
import LocaleDropdown     from '../../common/LocaleDropdown';

export default {
    components: {
        RegisterForm,
        LocaleDropdown
    },

    methods: {
        success (data) {
            this.$store.dispatch('auth/saveToken', data);
            this.$store.dispatch('auth/setUser', data);
            this.$router.push({name: 'index'});
        }
    }
};
</script>
