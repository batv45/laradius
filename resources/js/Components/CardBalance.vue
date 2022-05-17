<template>
    <div class="card bg-dark">
        <div class="card-stamp">
            <div class="card-stamp-icon bg-white text-primary">
                <currency-lira-icon/>
            </div>
        </div>
        <div class="card-body">
            <h3 class="card-title mb-0">Bakiyeniz</h3>
            <h1 class="display-6">{{ amount }}</h1>
        </div>
    </div>
</template>

<script>
export default {
    name: "CardBalance",
    props:['amount']
}
</script>

<style scoped>

</style>
