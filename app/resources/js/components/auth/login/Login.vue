<template>
    <v-flex sm8 md6 lg4>
        <div class="login-logo">
            <img src="/img/logo-sign.png" alt="">
        </div>
        <v-card>
            <v-toolbar dark color="primary" flat>
                   <v-flex xs10>
                       <v-toolbar-title>{{ $t('login') }}</v-toolbar-title>
                   </v-flex>
                   <v-flex xs2 class="text-right">
                       <locale-dropdown></locale-dropdown>
                   </v-flex>
            </v-toolbar>
            <v-card-text>
                <login-form @success="success"></login-form>
                <div class="text-center mt-4">{{ $t('no_account') }}
                    <router-link :to="{ name: 'register' }">{{ $t('register') }}</router-link>
                </div>
            </v-card-text>
        </v-card>
    </v-flex>
</template>

<script>
import LoginForm      from './LoginForm';
import LocaleDropdown from '../../common/LocaleDropdown';

export default {
    components: {
        LoginForm,
        LocaleDropdown
    },

    methods: {
        success (data) {
            Promise.all([
                this.$store.dispatch('auth/saveToken', data),
            ])
                .then(() => {
                    this.$router.push({name: 'index'});
                });
           
            
        }
    }
};
</script>
