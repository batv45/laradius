<template>
    <AppLayout>
        <PageHeader>
            <div class="col">
                <div class="page-title">Kullanıcılar</div>
                <div class="page-subtitle">Sistemde kayıtlı kullanıcılar listesi</div>
            </div>
            <div class="col-auto ms-auto">
                <Link :href="route('admin.user.create')" class="btn btn-primary"><plus-icon/> Yeni Kullanıcı</Link>
            </div>
        </PageHeader>
        <div class="row mb-2">
            <div class="col-xl-4 col-lg-6">
                <div class="row g-2">
                    <div class="col">
                        <div class="input-group input-group-flat">
                            <input type="text" class="form-control"
                                   @keypress.enter="search"
                                   v-model="params.search"
                                   autocomplete="off"
                                   placeholder="Kullanıcılarda ara…">
                                <span class="input-group-text" v-if="params.search">
                                  <a href="#" @click.prevent="params.search=null;search()" class="input-group-link bg-white text-danger">Temizle</a>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto  mt-2">
                <div class="nav-item dropdown d-inline-block">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu" aria-expanded="false">
                        <button type="button" v-if="route().params.hasOwnProperty('tcverified') && route().params.tcverified > 0"
                                class="btn dropdown-toggle btn-link text-decoration-none p-1 text-info dropdown-toggle-no-caret"
                        >T.C. Onaylılar</button>
                        <button type="button" v-else-if="route().params.hasOwnProperty('tcverified') && route().params.tcverified == 0"
                                class="btn dropdown-toggle btn-link text-decoration-none p-1 text-danger dropdown-toggle-no-caret"
                        >T.C. Onaysızlar</button>
                        <button type="button" v-else
                                class="btn dropdown-toggle btn-link text-decoration-none p-1  dropdown-toggle-no-caret"
                        >Filtrele</button>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <Link :href="route('admin.user.index',{tcverified: 1})"
                              class="dropdown-item text-primary"><user-check-icon class="dropdown-item-icon text-primary" /> T.C. Onaylılar</Link>
                        <Link :href="route('admin.user.index',{tcverified: 0})"
                              class="dropdown-item text-danger"><user-x-icon class="dropdown-item-icon text-danger"/> T.C. Onaysızlar</Link>
                    </div>
                </div>
                <Link :href="route(route().current())"
                      v-b-tooltip="'Filtreyi temizle'"
                      v-if="route().params.hasOwnProperty('tcverified')"
                      class="text-danger p-1 rounded-end"><x-icon class="align-middle"/></Link>
            </div>
        </div>
        <div class="row" v-if="page_users.data.filter(f=>!f.tc_verified_at).length > 0 ">
            <div class="col">
                <Alert type="danger" icon class="alert-important">T.C. Kimlik numarası onaylanmamış kullanıcı(lar)
                    mevcut.
                </Alert>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="table-responsive mb-0">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th class="text-muted font-italic w-1">#ID</th>
                                <th>Kullanıcı</th>
                                <th>E-Posta</th>
                                <th>Yetki</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in page_users.data">
                                <td class="text-muted font-italic"
                                ><span>#{{ user.id }}</span></td>
                                <td class="p-1">
                                    <UserInfo avatar avatar-square medals :user="user"></UserInfo>
<!--                                    <small v-if="!user.status" class=" badge bg-danger mt-auto ms-2 mb-auto">Hesap engelli</small>-->
                                </td>
                                <td class="text-muted" >{{user.email}}</td>
                                <td class="text-muted" >
                                    <BadgeUserRole :user="user" />
                                </td>
                                <td>
                                    <Link :href="route('admin.user.edit',user)"
                                          class="btn btn-light"><ChevronRightIcon/> Düzenle</Link>
                                </td>
                            </tr>
                            <tr v-if="!page_users.data.length">
                                <td colspan="5" class="text-center text-muted">Sonuç bulunamadı.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer border-top-0" v-if="page_users.links.length > 3">
                        <Pagination :links="page_users.links"></Pagination>
                    </div>
                </div>

                <div class="row">
                    <div class="col pt-2">
                <span class="text-muted ps-2" v-show="page_users.total>0"><span >{{ page_users.from }} - {{ page_users.to }} arası kullanıcı gösteriliyor.</span>
                    Toplam {{page_users.total}} kullanıcı mevcut.</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "../../../Layouts/AppLayout";
import PageHeader from "../../../Components/PageHeader";
import UserInfo from "../../../Components/UserInfo";
import Avatar from "../../../Components/Avatar";
import {Link} from "@inertiajs/inertia-vue";
import Pagination from "../../../Components/Pagination";
import Alert from "../../../Components/Alert";
import BadgeUserRole from "../../../Components/BadgeUserRole";
import {map, mapValues, pickBy, throttle, trim} from "lodash";
export default {
    name:'UserIndex',
    metaInfo:{
        title : 'Kullanıcılar'
    },
    components: {BadgeUserRole, Pagination, Avatar, UserInfo, PageHeader, AppLayout, Link , Alert},
    props:{
        page_users:Object
    },
    data(){
        return {
            searchText:route().params['q'],
            params: {
                search: this.route().params.search,
            },
        }
    },
    watch: {
        params: {
            handler: throttle(function () {
                    let params = pickBy(mapValues(this.params,trim));
                    this.$inertia.get(this.route(route().current()), params, { replace: true, preserveState: true });
                }, 150),
            deep: true,
        },
    },
    methods:{
        search(clear=false){
            let page = undefined
            if( this.route().params.page > 0 ){
                let page = this.route().params.page
            }

            if( this.searchText != null && this.searchText.length > 0){
                this.$inertia.reload({
                    data:{
                        page,
                        q:this.searchText
                    }
                })
            }else{
                this.$inertia.reload({
                    data:{
                        page,
                        q:undefined
                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
