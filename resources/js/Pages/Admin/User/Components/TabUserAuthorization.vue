<template>
    <div class="row row-cards">
        <div class="col-sm-6 mb-3" v-if="userHasRole('super-admin')">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Kullanıcı Rolü</h3>
                    <div class="">
                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                            <label class="form-selectgroup-item flex-fill" v-for="rol in roles">
                                <input type="radio" name="user_role" v-model="form_role.name" :value="rol.name"
                                       class="form-selectgroup-input">
                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                    <div class="me-3">
                                        <span class="form-selectgroup-check"></span>
                                    </div>
                                    <div>
                                        <span class=" text-primary" v-if="rol.name === 'admin'">{{
                                                rol.description
                                            }}</span>
                                        <span class=" text-red" v-else-if="rol.name === 'super-admin'">{{
                                                rol.description
                                            }}</span>
                                        <span class=" " v-else-if="rol.name === 'user'">{{ rol.description }}</span>
                                        <span class=" " v-else>{{ rol.description }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button"
                            :class="{'btn-loading':this.process_name === 'role_update','disabled':this.process_name}"
                            class="btn btn-primary" @click.prevent="submitRoleUpdate()">
                        <check-icon/>
                        Güncelle
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-6"  v-if="userHasRole('super-admin')">
            <Alert type="warning" icon  v-if="userHasRole('super-admin',user)" class="alert-important">Sistem yöneticisi tam yetkilidir.<br>
                <small>Ekstra bir yetki vermenize gerek yoktur.</small></Alert>
            <div class="card">
                <div>
                    <h4 class="border-bottom p-2 ">Kullanıcı Yönetimi</h4>
                    <div class="px-2">
                        <label class="form-check"
                               :class="{'cursor-not-allowed':userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)}"
                               v-for="perm in permissions.filter(f=> f.name.split('.')[0] === 'user')">
                            <input class="form-check-input"
                                   :disabled="userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)"
                                   v-model="form_permissions.all" :value="perm.name"
                                   type="checkbox">
                            <span class="form-check-label" :title="perm.name">
                                <plus-icon v-if="perm.name.includes('.create')"></plus-icon>
                                <search-icon v-if="perm.name.includes('.show')"></search-icon>
                                <edit-icon v-if="perm.name.includes('.edit')"></edit-icon>
                                <trash-icon v-if="perm.name.includes('.delete')"></trash-icon>
                                <span :class="userHasPermission(perm.name,user)?'strong text-decoration-underline':''">{{
                                        perm.description
                                    }}</span>
                            </span>
                        </label></div>
                </div>
                <div>
                    <h4 class="border-bottom border-top p-2 ">Bildirim Yönetimi</h4>
                    <div class="px-2">
                        <label class="form-check" :class="{'cursor-not-allowed':userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)}"
                               v-for="perm in permissions.filter(f=> f.name.split('.')[0] === 'ticket')">
                            <input class="form-check-input" :disabled="userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)"
                                   v-model="form_permissions.all" :value="perm.name"
                                   type="checkbox">
                            <span class="form-check-label" :title="perm.name">
                                <plus-icon v-if="perm.name.includes('.create')"></plus-icon>
                                <search-icon v-if="perm.name.includes('.show')"></search-icon>
                                <edit-icon v-if="perm.name.includes('.edit')"></edit-icon>
                                <trash-icon v-if="perm.name.includes('delete') && !perm.name.includes('message.delete')"></trash-icon>
                                <message-off-icon v-if="perm.name.includes('message.delete')"></message-off-icon>
                                <span :class="userHasPermission(perm.name,user)?'strong text-decoration-underline':''">{{
                                        perm.description
                                    }}</span>
                            </span>
                        </label></div>
                </div>
                <div v-if="permissions.filter(f=> f.name.split('.')[0] !== 'user' &&
                 f.name.split('.')[0] !== 'ticket').length">
                    <h4 class="border-bottom border-top p-2 bg-dark-lt">Diğer Yetkiler</h4>
                    <div class="px-2">
                        <label class="form-check" :class="{'cursor-not-allowed':userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)}"
                               v-for="perm in permissions.filter(f=>
                        f.name.split('.')[0] !== 'user' &&
                        f.name.split('.')[0] !== 'ticket'
                            )">
                            <input class="form-check-input" :disabled="userHasPermission(perm.name,user) && !user.permissions.some(p=>p.name === perm.name)"
                                   v-model="form_permissions.all" :value="perm.name"
                                   type="checkbox">
                            <span class="form-check-label" :title="perm.name">
                                <plus-icon v-if="perm.name.includes('.create')"></plus-icon>
                                <search-icon v-if="perm.name.includes('.show')"></search-icon>
                                <edit-icon v-if="perm.name.includes('.edit')"></edit-icon>
                                <trash-icon v-if="perm.name.includes('.delete')"></trash-icon>
                                <span :class="userHasPermission(perm.name,user)?'strong text-decoration-underline':''">{{
                                        perm.description
                                    }}</span>
                            </span>
                        </label></div>
                </div>
                <div class="card-footer text-end">
                    <button type="button"
                            :class="{'btn-loading':this.process_name === 'permission_update','disabled':this.process_name}"
                            class="btn btn-primary" @click.prevent="submitPermissionUpdate()"><check-icon/> Güncelle</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import Alert from "~/Components/Alert"
export default {
    name: "TabUserAuthorization",
    components:{Alert},
    props:{
        user:{
            type: Object,
            required: true
        },
        roles:{
            type: Array,
            required: true
        },
        permissions:{
            type: Array,
            required: true
        }
    },
    data(){
        return {
            process_name: null,
            form_role:this.$inertia.form({
                name: this.user.roles[0].name
            }),
            form_permissions:this.$inertia.form({
                all: this.user.permissions_all.map(per=>per.name)
            }),
        }
    },
    mounted() {
        this.$inertia.on('finish',()=>this.process_name = null)
    },
    methods:{
        submitRoleUpdate(){
            this.process_name = "role_update"

            this.form_role.put(route('admin.user.update_role',this.user),{
                preserveScroll : true,
                onError:(error)=>{
                  this.$notyf.error(error.name)
                },
                onFinish:()=>{
                    this.form_permissions.all = this.user.permissions_all.map(per=>per.name)
                }
            })
        },
        submitPermissionUpdate(){
            this.process_name = "permission_update"
            this.form_permissions.put(route('admin.user.update_permission',this.user),{
                preserveScroll : true,
                onFinish:()=>{
                    this.form_permissions.all = this.user.permissions_all.map(per=>per.name)
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
