<template>
</template>

<script>
export default {
    name: "FlashMessage",
    data() {
        return {

        };
    },
    props:{
        notifications:Array
    },
    created() {
        this.$inertia.on('finish', (event) => this.runMessages())
    },
    mounted() {
        this.flash()
        this.swal()

    },
    methods:{
        runMessages(){
            this.flash()
            this.swal()

        },
        swal(){
            if(this.$page.props.swal.title != null || this.$page.props.swal.text != null || this.$page.props.swal.html != null)
            this.$swal.fire(this.$page.props.swal)
            this.$page.props.swal = {}
        },
        flash() {
            if (this.$page.props.user && this.$page.props.user.settings) {
                this.$notyf.options.position.y = this.$page.props.user.settings.notyf_yposition
                    ? this.$page.props.user.settings.notyf_yposition
                    : 'bottom';
                this.$notyf.options.position.x = this.$page.props.user.settings.notyf_xposition
                    ? this.$page.props.user.settings.notyf_xposition
                    : 'right';
            }
            for (let notyf of this.notifications) {
                if (notyf.type === 'danger')
                    notyf.type = 'error';
                if (notyf.dismissible) {
                    notyf.duration = 0;
                }
                this.$notyf.open(notyf);

            }
            this.notifications.splice(0,this.notifications.length);
        }
    }
}
</script>

<style scoped>

</style>
