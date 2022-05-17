<template>
    <div>
        <form @submit.prevent="submitSettingsUpdate" class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Anlık bildirim <span class="strong text-primary">
                                <arrows-vertical-icon/>
                            </span> dikey konumu</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="yposition" v-model="form_settings.notyf_yposition" value="top" class="form-selectgroup-input" >
                            <span class="form-selectgroup-label">
                                        <strong> top</strong> <arrow-bar-to-up-icon/> Ekranın üst kısmında.</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="yposition" v-model="form_settings.notyf_yposition" value="bottom" class="form-selectgroup-input" >
                            <span class="form-selectgroup-label">
                                        <strong> bottom</strong> <arrow-bar-to-down-icon/> Ekranın alt kısmında.</span>
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Anlık bildirim <span class="strong text-primary">
                                <arrows-horizontal-icon/>
                            </span> yatay konumu</label>
                    <div class="form-selectgroup flex-column">
                        <label class="form-selectgroup-item me-auto">
                            <input type="radio" name="xposition" v-model="form_settings.notyf_xposition" value="left" class="form-selectgroup-input">
                            <span class="form-selectgroup-label">
                                        <strong> left</strong> <arrow-bar-to-left-icon/> Ekranın sol tarafında.</span>
                        </label>
                        <label class="form-selectgroup-item ms-auto me-auto">
                            <input type="radio" name="xposition" v-model="form_settings.notyf_xposition" value="center" class="form-selectgroup-input">
                            <span class="form-selectgroup-label">
                                        <strong> center</strong> <dots-icon/> Ekranın ortasında.</span>
                        </label>
                        <label class="form-selectgroup-item ms-5">
                            <input type="radio" name="xposition" v-model="form_settings.notyf_xposition" value="right" class="form-selectgroup-input">
                            <span class="form-selectgroup-label">
                                        <strong> right</strong> <arrow-bar-to-right-icon/> Ekranın sağ tarafında.</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" :class="{'btn-loading':process_name === 'settings_update','disabled':process_name != null}">
                    <check-icon/>
                    Güncelle</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "TabSettings",
    props:{
        user: {
            type: Object,
            required: true
        }
    },
    data(){
        return {
            process_name : null,
            form_settings:this.$inertia.form({
                notyf_yposition:this.user.settings.notyf_yposition??'bottom',
                notyf_xposition:this.user.settings.notyf_xposition??'right',
            }),
        }
    },
    methods:{
        submitSettingsUpdate(){

            this.$inertia.put(route('user-settings.update'),this.form_settings,{
                errorBag:'updateUserSettings',
                onStart:()=>{
                    this.process_name = "settings_update";
                    this.$notyf.dismissAll()
                },
                onFinish:() => this.process_name=null,
                onSuccess: (respon) => {
                    // localStorage.setItem('notyfXpos',this.form_settings.notyf_xposition)
                    // localStorage.setItem('notyfYpos',this.form_settings.notyf_yposition)
                },
            })
        },
    }
}
</script>

<style scoped>

</style>
