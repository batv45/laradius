<template>
    <div @click="scrollTop" v-show="visible" class="bottom-right animate__fadeIn animate__animated animate__faster" >
        <slot></slot>
    </div>
</template>

<script>
export default {
    data () {
        return {
            visible: false
        }
    },
    methods: {
        scrollTop: function () {
            window.scrollTo(0,0);
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        },
        scrollListener: function (e) {
            this.visible = window.scrollY > 150
        }
    },
    mounted: function () {
        window.addEventListener('scroll', this.scrollListener)
    },
    beforeDestroy: function () {
        window.removeEventListener('scroll', this.scrollListener)
    }
}
</script>

<style scoped>
.bottom-right {
    position: fixed;
    bottom: 20px;
    right: 20px;
    cursor: pointer;
}
</style>
