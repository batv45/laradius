<template>
    <Login :register="true"></Login>
</template>

<script>
import Login from "./Login";
export default {
    metaInfo:{
        title:'Kayıt Ol'
    },
    name: "Register",
    components: {Login}
}
</script>

<style scoped>

</style>
