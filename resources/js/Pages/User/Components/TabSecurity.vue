<template>
    <div>
        <form @submit.prevent="submitPasswordUpdate" class="card mb-3">
            <div class="card-header">
                <div class="card-title">
                    <lock-icon/> Şifre Değiştir
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Mevcut şifre</label>
                    <input type="password" class="form-control"
                           :class="form_password.errors.current_password?'is-invalid':''"
                           v-model="form_password.current_password">
                    <div v-if="form_password.errors.current_password" class="invalid-feedback">
                        {{form_password.errors.current_password}}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yeni şifre</label>
                    <input type="password" class="form-control"
                           :class="form_password.errors.password?'is-invalid':''"
                           v-model="form_password.password">
                    <div v-if="form_password.errors.password" class="invalid-feedback">
                        {{form_password.errors.password}}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yeni şifre tekrar</label>
                    <input type="password" class="form-control"
                           :class="form_password.errors.password_confirmation?'is-invalid':''"
                           v-model="form_password.password_confirmation">
                    <div v-if="form_password.errors.password_confirmation" class="invalid-feedback">
                        {{form_password.errors.password_confirmation}}</div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"
                        :disabled="!form_password.isDirty"
                        :class="{'btn-loading':process_name === 'password_update','disabled':process_name != null}">
                    <check-icon/>
                    Güncelle</button>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Tarayıcı Oturumları</div>
            </div>
            <div class="card-body">
                <div v-for="sessi in sessions" class="mb-2">
                    <div class="mb-1">
                        <h3 class="m-0"> {{sessi.agent.platform}} - {{sessi.agent.browser}}</h3>
                    </div>
                    <div class="">
                        <span v-if="sessi.agent.is_desktop"><device-desktop-icon/></span>
                        <span v-else><device-mobile-icon/></span>
                        <span>{{sessi.ip_address}}</span> –
                        <span class="text-success" v-if="sessi.is_current_device">Bu cihaz</span>
                        <span class="text-muted" v-else>{{ sessi.last_active }}</span>
                    </div>
                </div>


                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-session"
                        :class="{'btn-loading':process_name==='session_delete','disabled':process_name!=null}"
                        class="btn btn-dark mt-3"> Diğer cihazlardan çıkış yap</button>
            </div>
        </div>

        <div class="modal fade" ref="modalSession" id="modal-session" tabindex="-1"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-title">Kimlik doğrulama</div>
                        <div class="text-center">
                            <label>
                                <input type="password" v-model="form_confirm_password" name="password"
                                       placeholder="Şifreniz"
                                       class="form-control" />
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" ref="modalsessioncancel" class="btn btn-link text-danger me-auto"
                                data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" @click="otherSessionDelete"
                                :class="{'btn-loading':process_name==='session_delete','disabled':process_name!=null}"
                                class="btn btn-primary">Doğrula</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TabSecurity",
    props:{
        sessions:{
            type:Array,
            required:true
        }
    },
    data() {
        return {
            process_name:null,
            form_confirm_password: null,
            form_password: this.$inertia.form({
                current_password:null,
                password:null,
                password_confirmation:null,
            }),
        }
    },

    mounted() {
        this.$refs.modalSession.addEventListener('hidden.bs.modal',(event)=>{
            this.form_confirm_password = null;
        })
    },
    methods:{
        submitPasswordUpdate(){
            this.process_name = "password_update";
            this.form_password.put(route('user-password.update'),{
                errorBag:'updatePassword',
                onFinish:() => this.process_name=null,
                onSuccess: (respon) => {
                    this.form_password.reset()
                    this.$notyf.success('Hesap şifresi güncellendi')
                },
            })
        },
        otherSessionDelete(){
            this.process_name = "session_delete";
            this.$inertia.delete(route('profile.logout-other-browser-sessions'),{
                data:{password:this.form_confirm_password},
                preserveScroll: true,
                onSuccess:(respon)=>{
                    let flas = respon.props.flash[respon.props.flash.length - 1].type;
                    if( flas === 'success'){
                        this.$refs.modalsessioncancel.click()
                    }
                },
                onFinish:()=>this.process_name = null
            })
        },
    }
}
</script>

<style scoped>

</style>
