<template>
    <v-card>
        <v-card-title>
            <h3 class="title">Roles and Permissions Management</h3>
        </v-card-title>

        <v-tabs>
            <v-tab :key="0">Roles</v-tab>
            <v-tab class="pb-0" :key="1">Permissions</v-tab>

            <v-tab-item :key="0">
                <role-list></role-list>
            </v-tab-item>

            <v-tab-item :key="1">
                <permissions-list></permissions-list>
            </v-tab-item>
        </v-tabs>
    </v-card>
</template>

<script>
import RoleList        from './RolesList';
import PermissionsList from './PermissionsList';

export default {
    name: 'RolesIndex',
    components: {PermissionsList, RoleList}
};
</script>
