<template>
    <div v-if="Object.keys(errors).length > 0">
        <Alert type="danger" icon="danger">
            <div class="strong h3 mb-1">Hatalı alanlar mevcut;</div>
            <div v-for="err in errors">{{err}}</div>
        </Alert>
    </div>
</template>

<script>
import Alert from "~/Components/Alert";
export default {
    name: "FormErrors",
    props:{
        errors:{
            type : Object,
            required : true
        }
    },
    components: {Alert}
}
</script>

<style scoped>

</style>
