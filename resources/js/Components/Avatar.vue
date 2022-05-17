<template>
    <VueAvatar class="me-2 border"
            background-color="#f0f2f6"
            color="#656d77"
            :size="avatarSm?32:size"
               :rounded="!avatarSquare"
            :class="{'avatar-sm':avatarSm,'rounded-2':avatarSquare}"
            :username="user.full_name" :src="user.profile_photo_url">
    </VueAvatar>
</template>

<script>
import VueAvatar from 'vue-avatar'

export default {
    name: "Avatar",
    props: {
        user:Object,
        avatarSm:{
            type:Boolean,
            default:false
        },
        avatarSquare: {
            type: Boolean,
            default: true
        },
        size: {
            type: Number,
            default: undefined
        }
    },
    components:{
        VueAvatar
    },
    methods:{
    }
}
</script>

<style scoped>

</style>
