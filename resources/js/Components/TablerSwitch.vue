<template>
    <button class="switch-icon align-text-bottom" data-bs-toggle="switch-icon">
        <slot/>
    </button>
</template>

<script>
export default {
    name: "TablerSwitch",
    created(){
        this.$nextTick(()=>{
            var switchesTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="switch-icon"]'));
            switchesTriggerList.map(function (switchTriggerEl) {
                switchTriggerEl.addEventListener('click', function (e) {
                    e.stopPropagation();
                    switchTriggerEl.classList.toggle('active');
                });
            });
        })
    }
}
</script>

<style scoped>

</style>
