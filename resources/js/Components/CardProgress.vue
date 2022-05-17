<template>
    <div class="progress card-progress" v-show="process">
        <div class="progress-bar progress-bar-indeterminate"></div>
    </div>

</template>

<script>
export default {
    props:{
        process: {
            type:Boolean,
            default:false,
            required:true
        }
    }
}
</script>

<style scoped>

</style>
