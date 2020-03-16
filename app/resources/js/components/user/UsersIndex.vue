<template>
    <v-card>
        <v-card-title>
            <h3 class="title">User Management</h3>
        </v-card-title>

        <v-tabs>
            <v-tab :key="0">System Users</v-tab>
            <v-tab class="pb-0" :key="1">Users</v-tab>

            <v-tab-item :key="0">
                <user-list role="system" ref="userListSystem"></user-list>
            </v-tab-item>

            <v-tab-item :key="1">
                <user-list role="user" ref="userList"></user-list>
            </v-tab-item>
        </v-tabs>
    </v-card>
</template>

<script>
import UserDialog from '../../components/user/UserDialog';
import UserList   from '../../components/user/UserList';
import Profile    from '../profile/Profile';

export default {
    name: 'UsersIndex',

    components: {
        UserDialog,
        UserList
    },

    methods: {
        retrieveAllUsers () {
            return Promise.all([
                this.$refs.userListSystem.retrieveUsers(),
                this.$refs.userList.retrieveUsers()
            ]);
        }
    }
};
</script>

<style scoped>

</style>
