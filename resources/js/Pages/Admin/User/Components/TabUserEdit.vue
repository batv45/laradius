<template>
    <form @submit.prevent="submitUserUpdate" class="card">
        <div class="card-header"
             :class="user.deleted_at?'bg-red-lt':''">
            <h3 class="card-title">Kullanıcı Profili <small class="badge bg-danger-lt" v-if="user.deleted_at">Hesap engelli</small>
            </h3>
            <div class="card-options" v-if="userHasPermission('user.delete')">
                <div class="dropdown">
                    <button type="button" class="btn btn-ghost-danger btn-sm dropdown-toggle"
                            :class="{'disabled':this.process_name}"
                            data-bs-toggle="dropdown">
                        <div class="spinner-border spinner-border-sm me-1" role="status" v-if="this.process_name"></div>
                        <span v-else>İşlemler</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item"
                           @click.prevent="submitUserDelete"
                           href="#">
                            <template v-if="user.deleted_at">
                                <recycle-icon class="dropdown-item-icon"/>
                                Engeli kaldır
                            </template>
                            <template v-else>
                                <hand-stop-icon class="dropdown-item-icon"/>
                                Hesabı engelle
                            </template>
                        </a>
                        <a class="dropdown-item"
                           @click.prevent="submitUserDeleteForce"
                           href="#">
                            <trash-icon class="dropdown-item-icon"/>
                            Tüm kullanıcı bilgilerini sil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3 text-center">
                <a href="#" v-if="user.profile_photo_url != null" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal-photo">
                    <avatar :size="150" class="d-inline-block"
                            background-color="#f0f2f6"
                            color="#656d77"
                            :rounded="false"
                            :username="user.full_name" :src="user.profile_photo_url"></avatar>
                </a>
                <avatar v-else :size="150" class="d-inline-block"
                        background-color="#f0f2f6"
                        color="#656d77"
                        :rounded="false"
                        :username="user.full_name" :src="user.profile_photo_url"></avatar>

                <Link href="#"
                      :class="{'btn-loading':this.process_name === 'photo_delete','disabled':this.process_name}"
                      @click.prevent="deleteProfilePhoto"
                      v-show="user.profile_photo_path !==  null"
                      class="btn btn-sm btn-danger">Kaldır</Link>
            </div>
            <div class="mb-3">
                <label class="form-label required">Ad</label>
                <input type="text" v-model="form_profile.first_name"
                       minlength="1"
                       :class="form_profile.errors.first_name?'is-invalid':''" class="form-control"/>
                <div class="invalid-feedback">{{form_profile.errors.first_name}}</div>
            </div>
            <div class="mb-3">
                <label class="form-label required">Soyad</label>
                <input type="text" v-model="form_profile.last_name"
                       minlength="2"
                       :class="form_profile.errors.last_name?'is-invalid':''" class=" form-control"/>
                <div class="invalid-feedback">{{form_profile.errors.last_name}}</div>
            </div>
            <div class="mb-3">
                <label class="form-label required">TC Kimlik </label>
                <input type="number" v-model="form_profile.tc_kn"
                       :disabled="!!user.tc_verified_at"
                       :class="form_profile.errors.tc_kn?'is-invalid':user.tc_verified_at?'is-valid':'is-invalid'"
                       class=" form-control form-number"/>
                <div class="invalid-feedback">{{form_profile.errors.tc_kn}}</div>
            </div>
            <div class="mb-3">
                <label class="form-label required">Doğum Yılı</label>
                <input type="number" v-model="form_profile.birth_year"
                       :disabled="!!user.tc_verified_at"
                       :class="form_profile.errors.birth_year?'is-invalid':user.tc_verified_at?'is-valid':'is-invalid'"
                       class=" form-control form-number"/>
                <div class="invalid-feedback">{{form_profile.errors.birth_year}}</div>
            </div>
            <div class="mb-3">
                <label class="form-label">E-Posta</label>
                <input type="text" v-model="form_profile.email"
                       :class="form_profile.errors.email?'is-invalid':''"
                       class="form-control"/>
                <div class="invalid-feedback">{{form_profile.errors.email}}</div>
            </div>
            <div class="hr-text">Şifre Değiştir</div>
            <div class="mb-3">
                <label class="form-label">Yeni Şifre:</label>
                <input type="text" v-model="form_profile.new_password" :class="form_profile.errors.new_password?'is-invalid':''"
                       placeholder="Şifre" class="form-control"/>
                <div class="invalid-feedback">{{form_profile.errors.new_password}}</div>
                <Alert type="info" icon class="alert-important mt-2 p-2">Boş bırakılırsa güncellenmez.</Alert>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit"
                    :disabled="!form_profile.isDirty"
                    :class="{'btn-loading':this.process_name === 'user_update','disabled':this.process_name}"
                    class="btn btn-primary"><check-icon/> Güncelle</button>
        </div>
    </form>
</template>

<script>
import Avatar from "vue-avatar";
import Alert from "~/Components/Alert";
import UserInfo from "~/Components/UserInfo";
import UserInfoMadals from "~/Components/UserInfoMedals";

export default {
    name: "TabUserEdit",
    components: {UserInfoMadals, UserInfo, Avatar,Alert},
    props:{
        user:{
            type: Object,
            required: true
        }
    },
    data(){
        return {
            process_name: null,
            form_profile:this.$inertia.form({
                first_name : this.user.first_name,
                last_name : this.user.last_name,
                email :this.user.email,
                tc_kn :this.user.tc_kn,
                birth_year : this.user.birth_year,
                new_password : "",
            }),
        }
    },
    mounted() {
        this.$inertia.on('finish',()=>this.process_name = null)
    },
    methods:{
        submitUserUpdate(){
            this.form_profile.put(route('admin.user.update',this.user),{
                preserveScroll : true,
                onStart:() => this.process_name = 'user_update',
                onSuccess:(page)=>{
                    this.form_profile.first_name = this.user.first_name
                    this.form_profile.last_name = this.user.last_name
                    this.form_profile.email = this.user.email
                    this.form_profile.tc_kn = this.user.tc_kn
                    this.form_profile.birth_year = this.user.birth_year
                    this.form_profile.new_password = null
                }
            })
        },

        submitUserDelete(){
            if( this.user.deleted_at ){
                this.swalConfirmHtml(this.user.full_name+' kullanıcısının engelini kaldırmak istediğinize emin misiniz ?').then((sor)=>{
                    if(sor.isConfirmed){
                        this.process_name = "user_delete"
                        this.$inertia.delete(route('admin.user.destroy',this.user))
                    }
                })
            }else{
                this.swalConfirmHtml(this.user.full_name+' kullanıcısını engellemek istediğinize emin misiniz ?').then((sor)=>{
                    if(sor.isConfirmed){
                        this.process_name = "user_delete"
                        this.$inertia.delete(route('admin.user.destroy',this.user))
                    }
                })
            }
        },
        submitUserDeleteForce(){
            this.swalConfirm(this.user.full_name+' kullanıcısını, bildirim mesajlarını ve sistemde kayıtlı tüm verilerini silmek istediğinize emin misiniz?').then((sor)=>{
                if(sor.isConfirmed){
                    this.process_name = "user_delete"
                    this.$inertia.delete(route('admin.user.destroy_force',this.user))
                }
            })
        },
        deleteProfilePhoto(){
            this.swalConfirm(this.user.full_name+' kullanıcısının profil fotoğrafını silmek istediğine emin misin?').then((result) => {
                if (result.isConfirmed) {
                    this.process_name = "photo_delete"
                    this.$inertia.delete(route('admin.user.profilePhotoDelete',this.user.id))
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
