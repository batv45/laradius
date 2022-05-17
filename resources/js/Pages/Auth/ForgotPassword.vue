<template>
    <AuthLayout>
        <div class="text-center mb-4">
            <Link :href="route('index')"><img :src="asset('static/logo.svg')" height="36" alt=""></Link>
        </div>
        <form class="card card-md animate__animated animate__fadeInRight animate__faster" @submit.prevent="submit" autocomplete="off">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Şifre hatırlatma</h2>
                <div class="mb-3">
                    <label class="form-label">E-Posta</label>
                    <input type="email" class="form-control" v-model="form.email"
                           :class="$page.props.errors.email?'is-invalid':''"
                           placeholder="E-Posta adresiniz">
                    <div class="invalid-feedback">{{$page.props.errors.email?$page.props.errors.email.replace('email','E-Posta'):''}}</div>
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
        title:'Şifre Hatırlat'
    },
    name: "ForgotPassword",
    components: {AuthLayout,Link},
    data(){
        return {
            form:{
                email:null,
            }
        }
    },
    methods:{
        submit(){
            this.$inertia.post(route('password.email'),this.form,{
                onSuccess: (page) => {
                    this.$swal('Başarılı','Şifrenizi sıfırlamanız için E-Posta adresinize bir link gonderildi.','success')
                },
                onError: (err) => {
                    console.log(err);
                    this.$notyf.error('Bir hata oluştu!')

                }
            })
        }
    }
}
</script>

<style scoped>

</style>
