<template>
    <AuthLayout>
        <div class="text-center mb-4">
            <Link :href="route('index')"><img :src="asset('static/logo.svg')" height="36" alt=""></Link>
        </div>
        <form class="card card-md animate__animated animate__fadeInRight animate__faster" @submit.prevent="submit" autocomplete="off">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Şifre Sıfırlama</h2>
                <div class="mb-3">
                    <label class="form-label">Yeni Şifre</label>
                    <input type="text" class="form-control" v-model="form.password"
                           :class="$page.props.errors.password?'is-invalid':''"
                           placeholder="Yeni şifrenizi belirtin">
                    <div class="invalid-feedback">{{$page.props.errors.password}}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tekrar Şifre</label>
                    <input type="text" class="form-control" v-model="form.password_confirmation"
                           placeholder="Şifrenizi tekrar girin">
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100"><check-icon/> Gönder</button>
                </div>
            </div>

        </form>
        <div class="text-center text-muted mt-3">
            Giriş sayfasına <Link :href="route('login')"  tabindex="-1" class=""><arrow-back-icon/> Dön</Link>
        </div>
    </AuthLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AuthLayout from "../../Layouts/AuthLayout";

export default {
    metaInfo:{
        title:'Şifre Sıfırla'
    },
    name: "ResetPassword",
    components: {AuthLayout, Link},
    data(){
        return {
            form:{
                password:null,
                email:this.$page.props.request.email,
                token:this.$page.props.request.token,
                password_confirmation:null
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(route('password.update'),this.form,{
                onSuccess:()=>{
                    this.$swal('Başarılı','Şifreniz güncellendi giriş yapabilirsiniz','success')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
