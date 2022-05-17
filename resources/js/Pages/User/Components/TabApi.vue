<template>
    <div>
        <div class="row mb-2">
            <div class="col-md-8 col-lg-7">
                <div class="">
                    <label class="form-label">Yeni API Anahtar adı</label>
                    <form @submit.prevent="createApi" class="row">
                        <div class="col">
                            <input type="text"
                                   :class="{'is-invalid' : token_errors.token_name}"
                                   class="form-control"
                                   v-model="token_name" placeholder="Token ismi">
                            <div v-for="err in token_errors.token_name" class="invalid-feedback">{{err}}</div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary"
                                    :class="{'disabled':process_name != null, 'btn-loading' : process_name == 'token_create'}"
                                    type="submit"><check-icon/> Oluştur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <div class="m-1 animate__animated animate__fadeIn" v-if="token_response"><circle-check-icon class="text-success"/> Token oluşturuldu: <span
                    class="user-select-all strong">{{token_response}}
                            <copy-icon
                                class="cursor-pointer"
                                @click="doCopy"
                                v-b-tooltip.bottom.hover title="Kopyala"/>
                            <span v-if="tokenCopied" class="text-success animate__animated animate__fadeOutRight animate__slower">
                                <clipboard-check-icon/> Kopyalandı!
                            </span>
                        </span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">API Anahtarları
                    <span class="card-subtitle">Güvenlik için kullanmadığınız tokenleri silin.</span></h3>
            </div>
            <div class="table-responsive">
                <table
                    class="table table-vcenter table-nowrap">
                    <thead>
                    <tr>
                        <th class="w-1">Oluşturma</th>
                        <th>Token Adı</th>
                        <th class="w-1">Son Kullanım</th>
                        <th class="w-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="tok in tokens">
                        <td>{{ tok.created_at|momentH }}
                            <span class="d-none d-md-inline"> - {{tok.created_at|momentFromNow}}</span>
                        </td>
                        <td>{{tok.name}}</td>
                        <td>
                            <span v-if="tok.last_used_at != null" :title="tok.last_used_at|momentH">{{ tok.last_used_at|momentFromNow }}</span>
                        </td>
                        <td>
                            <a href="#" @click.prevent="deleteToken(tok)"
                               :class="{'disabled' : process_name != null}"
                               class="btn btn-sm">
                                <trash-icon class="text-danger"/> Token Sil</a>
                            <div class="progress progress-sm mt-1" v-if="process_name == 'token_delete_'+tok.id">
                                <div class="progress-bar progress-bar-indeterminate"></div>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="tokens.length < 1">
                        <td colspan="4" class="text-center">
                            <span class="text-muted ">API Listesi boş</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "TabApi",
    props:{
        tokens:Array,
    },
    data(){
        return {
            process_name:null,
            tokenCopied:false,
            token_name:'',
            token_errors: {},
            token_response:null
        }
    },

    methods:{
        doCopy() {
            this.tokenCopied = false
            if(this.token_response != null){
                this.$copyText(this.token_response).then(() => {
                    this.tokenCopied = true
                    this.$notyf.success('Token kodu kopyalandı.')
                })
            }
        },
        createApi(){
            if( this.token_name == '' )
                return false;
            this.process_name = 'token_create'
            this.tokenCopied = false
            this.token_errors = {}
            this.token_response = null
            axios.post(route('api.token.create'),{token_name:this.token_name})
                .then((response => {
                    if(response.data.hasOwnProperty('token')){
                        this.token_response = response.data.token
                        this.reloadTokens();
                        this.token_name = '';
                    }
                }))
                .catch((err) => {
                    if( err.response.data.hasOwnProperty('errors')){
                        this.token_errors = err.response.data.errors
                    }
                })
                .then(() => {
                    this.process_name = null
                })
        },
        reloadTokens(){
            this.$inertia.reload({
                only:['page_tokens']
            })
        },
        deleteToken(token){
            this.swalConfirm(token.name+' isimli kaydını silmek istediğinize emin misiniz?')
                .then(res => {
                    if(res.isConfirmed){

                        this.process_name = 'token_delete_'+token.id
                        axios.delete(route('api.token.destroy',token.id))
                        .then(response => {
                            this.$notyf.success('Token bilgisi silindi.')
                            this.reloadTokens()
                        })
                        .then(() => {
                            this.process_name = null
                        })
                    }
                })
        }
    }

}
</script>

<style scoped>

</style>
