<template>
    <v-flex sm8 md6 lg4>
        <div class="login-logo">
            <img src="/img/logo-sign.png" alt="">
        </div>
        <v-card>
            <content-loader :loading="isLoading"></content-loader>
            <v-toolbar dark color="primary" flat>
                   <v-flex xs10>
                       <v-toolbar-title>test</v-toolbar-title>
                   </v-flex>
                   <v-flex xs2 class="text-right">
                       <locale-dropdown></locale-dropdown>
                   </v-flex>
            </v-toolbar>
            <v-card-text>
                <login-form @success="success" @isLoading="setLoading"></login-form>
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

    data: () => ({
        isLoading: false
    }),

    methods: {
        setLoading (loading) {
            this.isLoading = loading;
        },

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
