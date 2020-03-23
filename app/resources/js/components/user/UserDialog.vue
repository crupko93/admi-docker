<template>
<v-layout row justify-center>
<v-dialog v-model="userDialog" transition="dialog-bottom-transition" max-width="900">
<v-card>
    <v-toolbar dark color="primary">
        <v-btn icon dark @click="closeDialog">
            <v-icon>close</v-icon>
        </v-btn>
        <v-toolbar-title>
            <span class="headline" v-if="userId">Edit User</span>
            <span class="headline" v-else>Add User</span>
        </v-toolbar-title>
    </v-toolbar>

    <v-card-text>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm6 md4>
                    <!-- First Name -->
                    <v-text-field required name="username" label="Username*"
                        @input="$v.form.username.$touch()"
                        @blur="$v.form.username.$touch()"
                        :error-messages="usernameErrors"
                        v-model="form.username"
                    ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                    <!-- First Name -->
                    <v-text-field required name="first_name" label="First Name*"
                        @input="$v.form.first_name.$touch()"
                        @blur="$v.form.first_name.$touch()"
                        :error-messages="firstNameErrors"
                        v-model="form.first_name"
                    ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                    <!-- Last Name -->
                    <v-text-field required name="last_name" label="Last Name*"
                        @input="$v.form.first_name.$touch()"
                        @blur="$v.form.last_name.$touch()"
                        :error-messages="lastNameErrors"
                        v-model="form.last_name"
                    ></v-text-field>
                </v-flex>
            </v-layout>

            <v-layout row wrap>
                <v-flex xs12 sm6 md4>
                    <!-- Email Address -->
                    <v-text-field required name="email" label="E-mail*"
                        @input="$v.form.email.$touch()"
                        @blur="$v.form.email.$touch()"
                        :error-messages="emailErrors"
                        v-model="form.email"
                    ></v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md4>
                    <!-- Phone -->
                    <PhoneInput
                        color="primary" ref="userPhone"
                        @input="$v.form.phone.$touch()"
                        @blur="$v.form.phone.$touch()"
                        name="user_phone" label="Phone*"
                        :rerender="userDialog"
                        v-model="form.phone">
                    </PhoneInput>
                </v-flex>

                <v-flex xs12 sm12 md4>
                    <!-- Role -->
                    <v-select required :items="roles" label="Role*"
                        @change="$v.form.role.$touch()"
                        @blur="$v.form.role.$touch()"
                        :error-messages="roleErrors"
                        v-model="form.role"
                    ></v-select>
                </v-flex>
            </v-layout>

            <v-layout row wrap v-if="!userId" align-center>
                <v-flex xs12 sm6 md4>
                    <!-- Password -->
                    <v-text-field required class="pass" name="password" label="Password*"
                        @click:prepend="generatePassword"
                        @click:append="showPassword = !showPassword"
                        @input="$v.passwordForm.password.$touch()"
                        @blur="$v.passwordForm.password.$touch()"
                        :prepend-icon="'fas fa-sync'"
                        :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                        :type="showPassword ? 'text' : 'password'"
                        :error-messages="passwordErrors"
                        v-model="passwordForm.password"
                    ></v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md4>
                    <!-- Password Confirmation -->
                    <v-text-field required name="password_confirmation" label="Confirm Password*"
                        @click:append="showPasswordConfirmation = !showPasswordConfirmation"
                        @input="$v.passwordForm.password_confirmation.$touch()"
                        @blur="$v.passwordForm.password_confirmation.$touch()"
                        :type="showPasswordConfirmation ? 'text' : 'password'"
                        :append-icon="showPasswordConfirmation ? 'visibility' : 'visibility_off'"
                        :error-messages="passwordConfirmationErrors"
                        v-model="passwordForm.password_confirmation"
                    ></v-text-field>
                </v-flex>

                <v-flex xs12 sm12 md4>
                    <v-checkbox class="pt-3" label="Send credentials by email?"
                        :disabled="form.role !== 'user'"
                        v-model="credentials.send"
                    ></v-checkbox>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card-text>

    <v-card-actions>
        <v-spacer></v-spacer>

        <v-btn color="primary" outlined
            @click="closeDialog"
            :disabled="formBusy">Cancel
        </v-btn>

        <v-btn color="primary" :disabled="formBusy"
            @click="submit"
        >
            <span v-if="formBusy">
                <v-icon>fa fa-spinner fa-spin</v-icon> Saving
            </span>
            <span v-else>Save</span>
        </v-btn>
    </v-card-actions>
</v-card>
</v-dialog>
</v-layout>
</template>

<script>

import PhoneInput from '../../components/common/PhoneInput';

const { required, email, sameAs } = require('vuelidate/lib/validators');

export default {
    name: 'UserDialog',

    components: {
        PhoneInput
    },

    data: () => ({
        userId                  : null,
        userDialog              : false,
        showPassword            : false,
        showPasswordConfirmation: false,
        formBusy                : false,

        form        : {},
        passwordForm: {},
        credentials : {
            send: false
        },

        roles: []
    }),

    validations () {
        return {
            form: {
                username  : { required },
                first_name: { required },
                last_name : { required },
                email     : { required, email },
                phone     : { phone: this.$utils.validators.phone('userPhone') },
                role      : { required }
            },

            passwordForm: {
                password             : { required },
                password_confirmation: { required, sameAsPassword: sameAs('password') }
            }
        };
    },

    computed: {
        usernameErrors () {
            if (!this.$v.form.username.$dirty) return [];
            const errors = [];
            !this.$v.form.username.required && errors.push('Username is required!');
            return errors;
        },
        firstNameErrors () {
            if (!this.$v.form.first_name.$dirty) return [];
            const errors = [];
            !this.$v.form.first_name.required && errors.push('First name is required!');
            return errors;
        },
        lastNameErrors () {
            if (!this.$v.form.last_name.$dirty) return [];
            const errors = [];
            !this.$v.form.last_name.required && errors.push('Last name is required!');
            return errors;
        },
        emailErrors () {
            if (!this.$v.form.email.$dirty) return [];
            const errors = [];
            !this.$v.form.email.email && errors.push('Email is not valid!');
            !this.$v.form.email.required && errors.push('Email is required!');
            return errors;
        },
        roleErrors () {
            if (!this.$v.form.role.$dirty) return [];
            const errors = [];
            !this.$v.form.role.required && errors.push('Role is required!');
            return errors;
        },
        passwordErrors () {
            if (!this.$v.passwordForm.password.$dirty) return [];
            const errors = [];
            !this.$v.passwordForm.password.required && errors.push('Password is required!');
            return errors;
        },
        passwordConfirmationErrors () {
            if (!this.$v.passwordForm.password_confirmation.$dirty) return [];
            const errors = [];
            !this.$v.passwordForm.password_confirmation.required && errors.push('Password confirmation is required!');
            !this.$v.passwordForm.password_confirmation.sameAsPassword && errors.push('Passwords must be identical!');
            return errors;
        }
    },

    methods: {
        initialize () {
            this.form = {
                id                    : '',
                username              : '',
                first_name            : '',
                last_name             : '',
                email                 : '',
                phone                 : '',
                role                  : null,
                image                 : ''
            };

            this.passwordForm = {
                password             : '',
                password_confirmation: ''
            };

            this.roles = [];

            this.$v.$reset();
        },

        submit () {
            this.formBusy = true;
            return this.userId
                ? this.updateUser()
                : this.createUser();
        },

        createUser () {
            this.$v.form.$touch();

            this.$v.passwordForm.$touch();

            if (this.$v.form.$invalid || this.$v.passwordForm.$invalid) {
                this.formBusy = false;
                return;
            }

            return API.users.create(Object.assign({}, this.form, this.passwordForm, this.credentials))
                .then(this.successCallback)
                .catch(e => {
                    Utils.standardErrorResponse(e);
                    this.formBusy = false;
                });
        },

        updateUser () {
            this.$v.form.$touch();

            if (this.$v.form.$invalid) {
                this.formBusy = false;
                return;
            }

            return API.users.update(this.form)
                .then(this.successCallback)
                .catch(e => {
                    Utils.standardErrorResponse(e);
                    this.formBusy = false;
                });
        },

        successCallback () {
            Snotify.success('User saved!', 'Success!');
            this.$emit('user-save');
            this.closeDialog();
        },

        generatePassword () {
            const characterSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789![]{}()%&*$#^<>~@';
            let password = '';

            for (let i = 0; i < 12; i++) {
                password += characterSet.charAt(
                    Math.floor(Math.random() * characterSet.length)
                );
            }

            this.passwordForm.password              = password;
            this.passwordForm.password_confirmation = password;
            this.showPassword                       = true;
            this.showPasswordConfirmation           = true;
        },

        new () {
            this.userId     = null;
            this.userDialog = true;
        },

        edit (id) {
            this.userId     = id;
            this.userDialog = true;

            return API.users.get(id).then(response => {
                response = response.data.user;
                this.form.billing_state = null;

                this.form = {
                    id                    : response.id,
                    username              : response.username,
                    first_name            : response.first_name,
                    last_name             : response.last_name,
                    email                 : response.email,
                    phone                 : response.phone,
                    role                  : response.role[0]
                };
            }).catch(Utils.standardErrorResponse);
        },

        getRoles () {
            return API.roles.all().then(response => {
                this.roles = response.data.roles;
                this.roles = response.data.roles.map(role => role.name);

            }).catch(Utils.standardErrorResponse);
        },

        closeDialog () {
            this.userDialog = false;
            this.formBusy   = false;
            this.initialize();
        }
    },

    watch: {
        userDialog: {
            handler (value) {
                if (value) {
                    this.initialize();
                    this.getRoles();
                }
            },
            deep: true
        }
    }
};
</script>
