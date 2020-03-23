<template>
    <v-app-bar dark :clipped-left="$vuetify.breakpoint.mdAndUp" fixed app hide-on-scroll color="primary" class="align-center" style="width: 100%">
        <v-app-bar-nav-icon @click.stop="navToggle"></v-app-bar-nav-icon>

        <v-toolbar-title class="white--text d-flex align-center">
            <img src="/img/logo-sign.png" alt="Admin logo" style="max-height: 56px" class="mr-2">
            <div class="flex-row">{{ $t('settings') }}</div>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <locale-dropdown></locale-dropdown>
    </v-app-bar>
</template>

<script>
import { settings }   from '~/config';
import LocaleDropdown from './LocaleDropdown';

export default {
    components: {
        LocaleDropdown
    },

    data: () => ({
        siteName: settings.siteName
    }),

    methods: {
        navToggle () {
            this.$emit('nav-toggle');
        }
    }
};
</script>
