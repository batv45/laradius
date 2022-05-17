<template>
    <div>
        <form @submit.prevent="submitProfileUpdate()"  class="card mb-2">
            <div class="card-header">
                <h3 class="card-title"><user-icon/> Profil Bilgisi</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-lg-2 align-self-center">
                        <div class="mt-2 text-center">
                            <a href="#" class="text-decoration-none" :disabled="process_name != null" data-bs-toggle="modal" data-bs-target="#modal-small">
                                <avatar :size="140" class="avatar-upload cursor-pointer d-inline-flex"
                                        background-color="#f0f2f6"
                                        color="#656d77"
                                        :rounded="false"
                                        :username="$page.props.user.full_name"
                                        :src="$page.props.user.profile_photo_url"></avatar>
                            </a>
                            <Link href="#"
                                  @click.prevent="deleteProfilePhoto"
                                  :class="{'btn-loading':process_name === 'photo_delete','disabled':process_name != null}"
                                  v-show="$page.props.user.profile_photo_path !==  null"
                                  class="btn btn-sm btn-danger">Kaldır</Link>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label required">Ad</label>
                            <input type="text" v-model="form_user.first_name"
                                   :disabled="user.tc_verified_at"
                                   :class="{'is-invalid': form_user.errors.first_name}"
                                   class="form-control">
                            <div class="invalid-feedback">{{form_user.errors.first_name}}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Soyad</label>
                            <input type="text" v-model="form_user.last_name"
                                   :disabled="user.tc_verified_at"
                                   :class="{'is-invalid': form_user.errors.last_name}"
                                   class="form-control">
                            <div class="invalid-feedback">{{form_user.errors.last_name}}</div>
                        </div>
                    </div>
                </div>
                <div class="hr-text mt-3">İletişim Bilgileri</div>
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3">
                            <label class="form-label">E-Posta</label>
                            <div class="input-icon mb-1">
                                <span class="input-icon-addon">
                                  <circle-check-icon v-if="user.email_verified_at != null" class="text-success" />
                                    <mail-icon v-else-if="user.email == null" />
                                    <alert-triangle-icon v-else class="text-warning" />
                                </span>
                                <input type="text" v-model="form_user.email"
                                       :class="{'is-invalid': form_user.errors.email}"
                                       class="form-control">
                            </div>
                            <small class="form-hint text-warning" v-if="user.email_verified_at == null && user.email != null">E-Posta onayı gerekli!</small>
                            <div class="text-danger small" v-if="form_user.errors.email">{{form_user.errors.email}}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3">
                            <label class="form-label">Telefon</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">+90</span>
                                <input type="text" v-model="form_user.phone"
                                       @keydown="phoneKeyDown"
                                       v-mask="'### ### ## ##'"
                                       :class="{'is-invalid':form_user.errors.phone}"
                                       class="form-control" autocomplete="off">
                                <div class="invalid-feedback">{{form_user.errors.phone}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hr-text mt-3">TC Kimlik Bilgileri</div>
                <div class="row">
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3">
                            <label class="form-label required">TC Kimlik</label>
                            <div class="input-icon mb-1">
                                <span class="input-icon-addon">
                                  <circle-check-icon v-if="user.tc_verified_at != null && user.tc_kn == form_user.tc_kn" class="text-success" />
                                    <alert-triangle-icon v-else class="text-warning" />
                                </span>
                                <input type="text" :disabled="user.tc_verified_at" v-model="form_user.tc_kn"
                                       :class="{'is-invalid':form_user.errors.tc_kn}"
                                       class="form-control" autocomplete="off">
                            </div>
                            <small class="form-hint text-success" v-if="user.tc_verified_at != null && user.tc_kn == form_user.tc_kn">TC Kimlik onaylı</small>
                            <small class="form-hint text-warning" v-else>TC Kimlik onayı gerekli!</small>
                            <div class="text-danger small" v-if="form_user.errors.tc_kn">{{form_user.errors.tc_kn}}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="mb-3">
                            <label class="form-label required">Doğum Tarihi</label>
                            <div class="input-icon mb-1">
                                <span class="input-icon-addon">
                                  <circle-check-icon v-if="user.tc_verified_at != null && user.birth_year == form_user.birth_year" class="text-success" />
                                    <alert-triangle-icon v-else class="text-warning" />
                                </span>
                                <input type="text" :disabled="user.tc_verified_at" v-model="form_user.birth_year" class="form-control" autocomplete="off">
                            </div>
                            <small class="form-hint text-success" v-if="user.tc_verified_at != null && user.birth_year == form_user.birth_year">Doğum tarihi onaylı</small>
                            <small class="form-hint text-warning" v-else>Doğum tarihi onayı gerekli!</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><check-icon/> Güncelle</button>
            </div>
        </form>

        <div class="modal fade" ref="modalSmall" id="modal-small" tabindex="-1"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <form @submit.prevent="submitPhotoFile" enctype="multipart/form-data"
                      ref="formUpload" class="modal-content">
                    <div class="modal-body">
                        <div class="modal-title">Fotoğraf yükle</div>
                        <div>
                            <input type="file" ref="upload" name="photoFile" id="upload"
                                   accept="image/x-png,image/gif,image/jpeg"
                                   :class="this.$page.props.errors.updateProfilePhoto && this.$page.props.errors.updateProfilePhoto.photo?'is-invalid':''"
                                   class="form-control" />
                            <div v-if="this.$page.props.errors.updateProfilePhoto" class="invalid-feedback">
                                {{this.$page.props.errors.updateProfilePhoto.photo}}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-danger me-auto"
                                ref="modalCancel"
                                @click="uploadPhotoCancel" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit"
                                :class="{'btn-loading':process_name === 'photo_update','disabled':process_name != null}"
                                class="btn btn-primary">Yükle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Avatar from "vue-avatar";
import Alert from "~/Components/Alert"
import BadgeUserRole from "~/Components/BadgeUserRole";

export default {
    name: "TabProfile",
    components: {BadgeUserRole, Avatar,Alert},
    props:{
        errors:{
            type: Object,
            default: () => ({})
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            process_name:null,
            form_user: this.$inertia.form({
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email,
                tc_kn: this.user.tc_kn,
                birth_year: this.user.birth_year,
                phone: this.user.phone
            })
        }
    },
    mounted() {
        this.$inertia.on('finish', () => this.process_name = null)
        this.$refs.modalSmall.addEventListener('hidden.bs.modal',(event)=>{
            this.uploadPhotoCancel();
        })
    },
    methods:{
        submitProfileUpdate(){
            this.form_user.transform((data) => ({
                ...data,
                phone: data.phone != null ? data.phone.replace(/\s+/g, '') : null,
            })).put(route('user-profile-information.update'),{
                errorBag:'updateProfileInformation',
                onStart:()=>this.process_name='profile_update',
                onFinish:() => this.process_name=null,
            })
        },
        deleteProfilePhoto(){
            this.swalConfirm('Profil fotoğrafını silmek istediğine emin misin?').then((result) => {
                if (result.isConfirmed) {
                    this.process_name = "photo_delete";
                    this.$inertia.delete(route('profile.delete-profile-photo'))
                }
            })
        },
        submitPhotoFile(){
            const formData= new FormData()
            formData.append('photo', this.$refs.upload.files[0])
            formData.append('type', 'photo')
            formData.append('_method', 'PUT');

            this.process_name = "photo_update";
            this.$inertia.post(route('user-profile-photo.update'), formData,{
                onSuccess:()=>{
                    this.$refs.modalCancel.click()
                },
                onError:()=>{

                }
            })
            // this.$inertia.put(route('user-profile-photo.update'), formData)
        },
        uploadPhotoCancel(){
            this.$refs.formUpload.reset()
            this.$refs.upload.value=null;
        },
        phoneKeyDown(key){
            if( key.keyCode === 32 ) key.preventDefault()
            else if(key.keyCode === 96 && ( this.form_user.phone === null || this.form_user.phone === '' ) ){
                key.preventDefault()
            }
            if(this.form_user.phone != null && this.form_user.phone.charAt(0) == '0'){
                this.form_user.phone = this.form_user.phone.substring(1)
            }
        }
    }
};
</script>



<style scoped>
div.fileinputs {
    position: relative;
}

div.fakefile {
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 1;
}

input.file {
    position: relative;
    text-align: right;
    -moz-opacity:0 ;
    filter:alpha(opacity: 0);
    opacity: 0;
    z-index: 2;
}

</style>
