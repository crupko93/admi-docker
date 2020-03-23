<template>
    <v-menu offset-y>
        <template v-slot:activator="{ on }">
            <v-btn v-on="on" text color="gray" style="margin: 0">
                <country-flag :country='locales[locale] !== "EN" ? locales[locale] : "US"' size='small'></country-flag>
                <div style="margin-left: 3px;">{{ locales[locale] }}</div>
            </v-btn>
        </template>
        <v-list>
            <v-list-item v-for="(option, value) in locales"
                @click.prevent="setLocale(value)"
                :key="value"
                class="align-center justify-center"
            >
                <v-list-item-icon class="mr-0 text-center justify-center" style="padding: 0">
                    <country-flag :country='option !== "EN" ? option : "US"' size='small'></country-flag>
                </v-list-item-icon>
                    <v-list-item-title v-text="option" small style="flex: none;"></v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
import { mapGetters }   from 'vuex';
import { loadMessages } from '~/plugins/i18n';
import CountryFlag      from 'vue-country-flag';

export default {
    components: {
        CountryFlag
    },

    computed: mapGetters({
        locale : 'lang/locale',
        locales: 'lang/locales'
    }),

    methods: {
        setLocale (locale) {
            if (this.$i18n.locale !== locale) {
                loadMessages(locale);

                this.$store.dispatch('lang/setLocale', {locale});
            }
        }
    }
};
</script>
