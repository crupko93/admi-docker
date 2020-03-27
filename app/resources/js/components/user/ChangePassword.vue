<template>
    <v-dialog v-model="passwordDialog" persistent max-width="400">
        <v-card>
            <v-card-title>
                <h3 class="title">{{$t('change_password')}}</h3> <br>
            </v-card-title>

            <v-card-text>
                <small class="body-1 alternative--text pl-1">
                    {{$t('changing_password_for')}} <strong>{{ user.first_name }} {{ user.last_name }}</strong>
                </small>

                <v-text-field required class="pass mt-4" name="password" :label="$t('password')+'*'"
                    @click:prepend="generatePassword"
                    @click:append="showPassword = !showPassword"
                    @input="$v.form.password.$touch()"
                    @blur="$v.form.password.$touch()"
                    :prepend-icon="'fas fa-sync'"
                    :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                    :type="showPassword ? 'text' : 'password'"
                    :error-messages="passwordErrors"
                    v-model="form.password"
                ></v-text-field>

                <v-text-field required name="password_confirmation" :label="$t('confirm_password')+'*'"
                    @click:append="showPasswordConfirmation = !showPasswordConfirmation"
                    @input="$v.form.password_confirmation.$touch()"
                    @blur="$v.form.password_confirmation.$touch()"
                    :append-icon="showPasswordConfirmation ? 'visibility' : 'visibility_off'"
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    :error-messages="passwordConfirmationErrors"
                    :prepend-icon="'z'"
                    v-model="form.password_confirmation"
                ></v-text-field>

                <v-checkbox hide-details class="pl-2" :label="$t('send_credentials_by_email?')" v-model="form.send_password">
                </v-checkbox>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" outlined @click="closePasswordDialog" :disabled="isUpdating">{{$t('cancel')}}</v-btn>
                <v-btn color="primary" @click.prevent="updatePassword" :disabled="isUpdating">
                    <span v-if="!isUpdating">{{$t('update')}}</span>
                    <span v-if="isUpdating"><v-icon>fa fa-spinner fa-spin</v-icon> {{$t('updating')}}</span>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

import { required, sameAs } from 'vuelidate/lib/validators';

export default {
    name: 'ChangePassword',

    data: () => ({
        passwordDialog          : false,
        isUpdating              : false,
        showPassword            : false,
        showPasswordConfirmation: false,

        user: {},

        form: {
            password             : '',
            password_confirmation: '',
            send_password        : false
        }
    }),

    validations: {
        form: {
            password             : { required },
            password_confirmation: { required, sameAsPassword: sameAs('password') },
        }
    },

    computed: {
        passwordErrors () {
            if (!this.$v.form.password.$dirty) return [];
            const errors = [];
            !this.$v.form.password.required && errors.push(this.$t('password')+' '+this.$t('is_required'));
            return errors;
        },
        passwordConfirmationErrors () {
            if (!this.$v.form.password_confirmation.$dirty) return [];
            const errors = [];
            !this.$v.form.password_confirmation.required && errors.push(this.$t('confirm_password')+' '+this.$t('is_required'));
            !this.$v.form.password_confirmation.sameAsPassword && errors.push(this.$t('password')+' '+this.$t('must_be_identical'));
            return errors;
        }
    },

    methods: {
        open (user) {
            this.passwordDialog = true;
            this.user           = user;
        },

        closePasswordDialog () {
            this.passwordDialog = false;

            this.form.password              = '';
            this.form.password_confirmation = '';
            this.form.send_password         = false;

            this.$v.$reset();
        },

        generatePassword () {
            const characterSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789![]{}()%&*$#^<>~@';

            let password = '';

            for (let i = 0; i < 12; i++) {
                password += characterSet.charAt(
                    Math.floor(Math.random() * characterSet.length)
                );
            }

            this.form.password              = password;
            this.form.password_confirmation = password;

            this.showPassword             = true;
            this.showPasswordConfirmation = true;
        },

        updatePassword () {
            this.$v.$touch();

            if (this.$v.$invalid) return;

            this.isUpdating = true;

            return API.users.updatePassword({
                id                   : this.user.id,
                password             : this.form.password,
                password_confirmation: this.form.password_confirmation,
                send_password        : this.form.send_password
            }).then(() => {
                Snotify.success(this.$t('password_updated') + '!');

                this.isUpdating     = false;
                this.passwordDialog = false;

                this.form.password              = '';
                this.form.password_confirmation = '';
                this.form.send_password         = false;

                this.showPassword             = false;
                this.showPasswordConfirmation = false;

                this.$v.$reset();
            }).catch(e => {
                Utils.standardErrorResponse(e);
                this.isUpdating = false;
            });
        }
    }
};
</script>
