<template>
    <AuthLayout>
        <div class="text-center mb-4">
            <Link :href="route('index')"><img :src="asset('static/logo.svg')" height="36" alt=""></Link>
        </div>
        <div v-show="!is_register" class="animate__animated animate__fadeInLeft animate__faster">
            <form class="card card-md" @submit.prevent="submitLogin" autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Kullanıcı Girişi</h2>
                    <div class="mb-3">
                        <h4>Hazır Kullanıcılar</h4>
                        <p v-for="sus in sample_users">
                            <a href="#" @click.prevent="loginuser(sus.email,'123')"
                               :class="userHasRole('super-admin',sus) ? 'bg-red-lt' : userHasRole('admin',sus) ? 'bg-primary-lt': 'bg-dark-lt'"
                                  class=" mb-2 rounded p-1">#{{sus.id}} {{ sus.email }} - 123 - {{ sus.full_name }} {{ sus.role_text }}</a></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-Posta</label>
                        <input type="text" class="form-control" :class="$page.props.errors.email?'is-invalid':''"
                               v-model="form_login.email" tabindex="1"
                               placeholder="E-Posta Adresiniz">
                        <div class="invalid-feedback">{{ $page.props.errors.email }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Şifre
                            <span class="form-label-description">
                  <Link :href="route('password.request')">Şifremi unuttum</Link>
                </span>
                        </label>
                        <div class="row">
                            <div class="col">
                                <input :type="viewPass?'text':'password'" tabindex="2"
                                       :class="$page.props.errors.password?'is-invalid':''" ref="passInput"
                                       class="form-control" v-model="form_login.password"
                                       placeholder="Şifreniz" autocomplete="off">
                                <div class="invalid-feedback">{{ $page.props.errors.password }}</div>
                            </div>
                            <div class="col-auto pt-2 ps-0">
                                <a href="#" @click.prevent="viewPass = !viewPass" class="link-secondary"
                                   title="Şifre görüntüle" data-bs-toggle="tooltip">
                                    <eye-off-icon v-show="!viewPass" />
                                    <eye-icon class="text-primary" v-show="viewPass" />
                                </a></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" tabindex="3" v-model="form_login.remember" class="form-check-input"/>
                            <span class="form-check-label">Beni hatırla</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit"
                                tabindex="3"
                                :class="{'btn-loading':process_name==='login'}"
                                class="btn btn-primary w-100"><login-icon/> Giriş</button>
                    </div>
                </div>
            </form>
            <div class="text-center text-muted mt-3">
                Hesabınız yok mu? <a href="#" @click.prevent="formChange(!is_register)" tabindex="-1">Kayıt Ol</a>
            </div>
        </div>

        <div v-show="is_register" class="animate__animated animate__fadeInRight animate__faster">
            <form class="card card-md" @submit.prevent="submitRegister"  autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4"><user-plus-icon/> Yeni hesap oluştur</h2>
                    <div class="mb-3">
                        <label class="form-label required">Ad</label>
                        <input type="text" class="form-control" minlength="3" v-model="form_register.first_name"
                               :class="$page.props.errors.first_name?'is-invalid':''" placeholder="Adınız" >
                        <div class="invalid-feedback">{{ $page.props.errors.first_name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Soyad</label>
                        <input type="text" class="form-control" v-model="form_register.last_name"
                               :class="$page.props.errors.last_name?'is-invalid':''" placeholder="Soy adınız" >
                        <div class="invalid-feedback">{{ $page.props.errors.last_name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">TC Kimlik Numarası</label>
                        <input type="number" class="form-control form-number" required v-model="form_register.tc_kn"
                               :class="$page.props.errors.tc_kn?'is-invalid':''" placeholder="TC Kimlik Numarası" >
                        <div class="invalid-feedback">{{ $page.props.errors.tc_kn }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Doğum Yılı</label>
                        <input type="number" class="form-control form-number" v-model="form_register.birth_year"
                               :class="$page.props.errors.birth_year?'is-invalid':''" placeholder="Doğum yılınız" >
                        <div class="invalid-feedback">{{ $page.props.errors.birth_year }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">E-Posta</label>
                        <input type="email" class="form-control" v-model="form_register.email"
                               :class="$page.props.errors.email?'is-invalid':''" placeholder="E-Posta adresiniz" >
                      <div class="invalid-feedback">{{ $page.props.errors.email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Şifre</label>
                        <input type="password" class="form-control" v-model="form_register.password"
                               :class="$page.props.errors.password?'is-invalid':''" placeholder="Password" autocomplete="off" >

                      <div class="invalid-feedback">{{ $page.props.errors.password }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Şifre Tekrar</label>
                        <input type="password" class="form-control" v-model="form_register.password_confirmation" placeholder="Password" autocomplete="off" >
                    </div>
                    <div class="form-footer">
                        <button type="submit"
                                :class="{'btn-loading':process_name==='register'}"
                                class="btn btn-primary w-100"><check-icon/> Hesap oluştur</button>
                    </div>
                </div>
            </form>
            <div class="text-center text-muted mt-3">
                Zaten bir hesabınız var mı? <a href="#" @click.prevent="formChange(!is_register)" tabindex="-1">

                Giriş</a>
            </div>
        </div>
    </AuthLayout>

</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AuthLayout from "../../Layouts/AuthLayout";

import Alert from "../../Components/Alert";
export default {
    metaInfo:{
        title:'Giriş'
    },
    name: "Login",
    components: {Alert, AuthLayout,Link},
    props: {
        register: Boolean,
        sample_users:Array
    },
    data(){
        return {
            process_name:null,
            viewPass:false,
            is_register:this.$props.register,
            form_login: {
                email:null,
                password:null,
                remember:undefined,
            },
            form_register:{
              first_name: '',
              last_name:'',
                tc_kn:null,
              birth_year:null,
              email:'',
              password:'',
              password_confirmation:'',
            }
        }
    },
    created() {
        // Attach onpopstate event handler
        // window.onpopstate = function(event) {
        //     if(event.state.hasOwnProperty('register')){
        //         this.register = event.state.register
        //     }
        // };
    },
    computed:{
      checkPassView(){

      }
    },
  methods:{
    loginuser(mail,pass){
        this.form_login.email = mail.toString();
        this.form_login.password = pass;
        this.submitLogin();
    },
    passView(){
      if(viewPass){
          this.$refs.passInput.type = 'text'
      }else{
          this.$refs.passInput.type = 'password';
      }
    },
    formChange(vall){
        // if( vall === true ){
        //     window.history.pushState({baba:'tuna'}, '', route('register'));
        // }else if(vall === false){
        //     window.history.pushState({baba:'tuna'}, '', route('login'));
        // }
        this.$page.props.errors={};
        this.is_register = vall;
    },
    submitLogin() {
        this.$page.props.errors={};
        this.$inertia.post('login',this.form_login,{
            onStart:()=>this.process_name = 'login',
            onFinish:()=>this.process_name = null
        });
    },
    submitRegister(){
            this.$page.props.errors={};

        this.process_name = 'register'
          this.$inertia.post('register',this.form_register,{
            onSuccess: () => {
                this.form_register = {
                    first_name: '',
                    last_name:'',
                    email:'',
                    password:'',
                    password_confirmation:'',
                }
                this.is_register = false;
                this.$swal('Başarılı','Hesabınız oluşturuldu.','success');
            },
              onError: (errors ) => {
                if(errors.hasOwnProperty('tc_kn')){
                    this.$notyf.error('T.C. Kimlik bilgileriniz doğrulanamadı!')
                }
              },
              onFinish: () => this.process_name = null
          });
    }
  }
}
</script>

<style scoped>

</style>
