<template>
    <v-flex sm8 md6 lg4>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </v-flex>
</template>

<script>
export default {};
</script>
