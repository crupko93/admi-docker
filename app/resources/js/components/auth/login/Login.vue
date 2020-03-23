<template>
    <v-flex sm8 md6 lg4>
        <div class="login-logo">
            <img src="/img/logo-sign.png" alt="">
        </div>
        <v-card>
            <v-toolbar dark color="primary" flat>
                <v-toolbar-title>Login</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <login-form @success="success"></login-form>
                <div class="text-center mt-4">Don't have an account?
                    <router-link :to="{ name: 'register' }">Register</router-link>
                </div>
            </v-card-text>
        </v-card>
    </v-flex>
</template>

<script>
import LoginForm from './LoginForm';

export default {
    components: {
        LoginForm
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
