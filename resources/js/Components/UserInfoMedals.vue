<template>
    <div class="d-inline-block ms-1">
        <hash-icon v-if="userHasRole('super-admin',user)"
           class="text-danger"
                   v-b-tooltip.bottom.hover title="Sistem Yöneticisi"/>

        <shield-icon v-if="userHasRole('admin',user)"
             class="text-primary"
                     v-b-tooltip.bottom.hover title="Yetkili"/>

        <checkbox-icon v-if="user.email_verified_at"
                       v-b-tooltip.bottom.hover title="E-Posta Onaylı"
                       class="text-primary"/>
        <user-check-icon v-if="user.tc_verified_at"
             v-b-tooltip.bottom.hover title="T.C. Onaylı"
             class="text-primary"/>
            <user-exclamation-icon v-else
                                   v-b-tooltip.bottom.hover title="T.C. Onaysız!"
               class="text-warning"/>

        <hand-stop-icon v-if="user.deleted_at"
                        v-b-tooltip.bottom.hover title="Hesap Engelli"
            class="text-red"
        />
    </div>
</template>

<script>
export default {
    name: "UserInfoMedals",
    props:{
        user:{
            type : Object,
            required : true
        }
    }
}
</script>

<style scoped>

</style>
