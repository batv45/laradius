<template>
    <div class="d-inline-block">
        <span v-if="amount > 0" class="h4 mb-0 p-1 rounded bg-azure-lt d-block w-full text-center">+<vue-autonumeric tag="span" :value="amount"></vue-autonumeric></span>
        <span v-else class="h4 mb-0 p-1 rounded bg-red-lt d-block w-full text-center"><vue-autonumeric tag="span" :value="amount"></vue-autonumeric></span>
    </div>
</template>

<script>
import VueAutonumeric from "~/Components/VueAutonumeric";

export default {
    name: "BadgeCurrency",
    components:{VueAutonumeric},
    props:{
        amount: Number
    }
}
</script>

<style scoped>

</style>
