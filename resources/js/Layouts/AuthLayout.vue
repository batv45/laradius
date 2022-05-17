<template>
    <div class="flex-fill d-flex flex-column justify-content-center py-4">
        <div class="container-tight py-6">
            <slot/>
        </div>
        <FlashMessage :notifications="this.$page.props.flash"/>
    </div>
</template>

<script>
import FlashMessage from "../Components/FlashMessage";
export default {
    name: "AuthLayout",
    components: {FlashMessage}
}
</script>

<style scoped>

</style>
