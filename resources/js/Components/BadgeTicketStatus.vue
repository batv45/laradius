<template>
    <div class="d-inline">
        <span class="badge me-1" :class="'bg-'+ticket.status_color">{{ ticket.status_text }}</span>
    </div>
</template>

<script>
export default {
    props:{
        badgeClass:String,
        ticket:Object
    },
    name: "BadgeTicketStatus"
}
</script>

<style scoped>

</style>
