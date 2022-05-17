<template>
    <AppLayout>
        <PageHeader :back-url="route('admin.user.index')">
            <div class="col">
                <h3 class="page-title"><UserIcon/> – Kullanıcı Düzenle</h3>
                <div class="page-subtitle"><span class="text-muted">#{{ page_user.id }}</span> - <strong>{{ page_user.full_name }}</strong> - {{page_user.email}}</div>
            </div>
        </PageHeader>
        <div class="row justify-content-between">
            <div class="mb-2 col-lg-4 col-md-4">
                <form @submit.prevent="submitUserUpdate" class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kullanıcı Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label required">Ad</label>
                            <input type="text" v-model="form_user.first_name" :class="this.$page.props.errors.first_name?'is-invalid':''" class="form-control"/>
                            <div class="invalid-feedback">{{this.$page.props.errors.first_name}}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Soyad</label>
                            <input type="text" v-model="form_user.last_name" :class="this.$page.props.errors.last_name?'is-invalid':''" class=" form-control"/>
                            <div class="invalid-feedback">{{this.$page.props.errors.last_name}}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-Posta</label>
                            <input type="text" :value="page_user.email" readonly class="form-control"/>
                        </div>
                        <div class="mb-3">
                            <span class="form-help" data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true" data-bs-content="<p>ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.</p>
<p class='mb-0'><a href=''>USP ZIP codes lookup tools</a></p>
">?</span>

                            <label class="form-label">Autosize example</label>
                            <textarea class="form-control" data-bs-toggle="autosize" placeholder="Type something…"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary"><check-icon/> Güncelle</button>
                    </div>
                </form>
            </div>
            <div class="mb-2 col-lg-3 col-md-4 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kullanıcı Rolü</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                <label class="form-selectgroup-item flex-fill" v-for="rol in page_roles">
                                    <input type="radio" name="user_role" v-model="form_role" :value="rol.name" class="form-selectgroup-input">
                                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                                        <div class="me-3">
                                            <span class="form-selectgroup-check"></span>
                                        </div>
                                        <div>
                                            <span class=" text-primary" v-if="rol.name === 'admin'">{{ rol.description }}</span>
                                            <span class=" text-red" v-else-if="rol.name === 'super-admin'">{{ rol.description }}</span>
                                            <span class=" " v-else-if="rol.name === 'user'">{{ rol.description }}</span>
                                            <span class=" " v-else>{{ rol.description }}</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" @click.prevent="submitRoleUpdate()"><check-icon/> Güncelle</button>
                    </div>
                </div>
            </div>
            <div class="mb-2 col-lg-3 col-md-4">
                <Alert type="warning" icon   v-if="userHasRole('super-admin',page_user)" class="alert-important">Sistem yöneticisi tam yetkilidir.<br>
                <small>Ekstra bir yetki vermenize gerek yoktur.</small></Alert>
                <div class="card">
                    <div>
                        <h4 class="border-bottom p-2 ">Kullanıcı Yönetimi</h4>
                        <div class="px-2">
                            <label class="form-check" :class="{'cursor-not-allowed':userHasPermission(perm.name,page_user) && !page_user.permissions.some(p=>p.name === perm.name)}"
                                   v-for="perm in page_permissions.filter(f=> f.name.split('.')[0] === 'user')">
                            <input class="form-check-input" :disabled="userHasPermission(perm.name,page_user) && !page_user.permissions.some(p=>p.name === perm.name)"
                                   v-model="form_permissions" :value="perm.name"
                                   type="checkbox">
                            <span class="form-check-label" :title="perm.name">
                                <plus-icon v-if="perm.name.includes('.create')"></plus-icon>
                                <search-icon v-if="perm.name.includes('.show')"></search-icon>
                                <edit-icon v-if="perm.name.includes('.edit')"></edit-icon>
                                <trash-icon v-if="perm.name.includes('.delete')"></trash-icon>
                                <span :class="userHasPermission(perm.name,page_user)?'strong':''">{{
                                    perm.description
                                }}</span>
                            </span>
                        </label></div>
                    </div>
                    <div v-if="page_permissions.filter(f=> f.name.split('.')[0] !== 'user').length">
                        <h4 class="border-bottom border-top p-2 bg-dark-lt">Diğer Yetkiler</h4>
                        <div class="px-2">
                            <label class="form-check" :class="{'cursor-not-allowed':userHasPermission(perm.name,page_user) && !page_user.permissions.some(p=>p.name === perm.name)}"
                                   v-for="perm in page_permissions.filter(f=>
                        f.name.split('.')[0] !== 'user'
                            )">
                                <input class="form-check-input" :disabled="userHasPermission(perm.name,page_user) && !page_user.permissions.some(p=>p.name === perm.name)"
                                       v-model="form_permissions" :value="perm.name"
                                       type="checkbox">
                                <span class="form-check-label" :title="perm.name">
                                <plus-icon v-if="perm.name.includes('.create')"></plus-icon>
                                <search-icon v-if="perm.name.includes('.show')"></search-icon>
                                <edit-icon v-if="perm.name.includes('.edit')"></edit-icon>
                                <trash-icon v-if="perm.name.includes('.delete')"></trash-icon>
                                <span :class="userHasPermission(perm.name,page_user)?'strong':''">{{
                                        perm.description
                                    }}</span>
                            </span>
                            </label></div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" @click.prevent="submitPermissionUpdate()"><check-icon/> Güncelle</button>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "../../../Layouts/AppLayout";
import PageHeader from "../../../Components/PageHeader";
import TablerSwitch from "../../../Components/TablerSwitch";
import Alert from "../../../Components/Alert";
export default {
    metaInfo:{
        title : 'Kullanıcı Düzenle'
    },
    name: "AdminUserEdit",
    components: {Alert, TablerSwitch, PageHeader, AppLayout},
    props:{
        page_user:Object,
        page_permissions:Array,
        page_roles:Array,
    },
    data(){
        return {
            form_user:{
                first_name:this.page_user.first_name,
                last_name:this.page_user.last_name
            },
            form_role:this.page_user.roles[0].name,
            form_permissions:this.page_user.permissions_all.map(per=>per.name),

        }
    },
    methods:{
        submitUserUpdate(){

            this.$inertia.put(route('admin.user.update',this.page_user),this.form_user,{
                onSuccess:(page)=>{
                    this.form_user.first_name = page.props.page_user.first_name
                    this.form_user.last_name = page.props.page_user.last_name
                }
            })
        },
        submitPermissionUpdate(){
            this.$inertia.put(route('admin.user.update_permission',this.page_user),{permissions:this.form_permissions},{
                onSuccess:(page)=>{
                }
            })
        },
        submitRoleUpdate(){
            this.$inertia.put(route('admin.user.update_role',this.page_user),{role:this.form_role},{
                onSuccess:(page)=>{
                    this.form_permissions = page.props.page_user.permissions_all.map(per=>per.name)
                }
            })
        }

    }
}
</script>

<style scoped>

</style>
