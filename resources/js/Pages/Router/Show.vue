<template>
    <AppLayout title="Mikrotik Görüntüle">
        <PageHeader :back-url="route('router.index')">
            <div class="col">
                <div class="page-title"><search-icon class="text-secondary"/> Mikrotik Görüntüle</div>
            </div>
            <template #actions>
                <b-dropdown text="Seçenekler" variant="primary">
                    <Link :href="route('router.edit',page_router)" class="dropdown-item"><pencil-icon class="dropdown-item-icon"/> Düzenle</Link>
                    <b-dropdown-item @click.prevent="deleteRouter"><trash-icon class="dropdown-item-icon"/> Sil</b-dropdown-item>
                </b-dropdown>
            </template>
        </PageHeader>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mikrotik Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">Identity</dt>
                            <dd class="col-7">{{page_router.identity}}</dd>

                            <dt class="col-5">IP:Port</dt>
                            <dd class="col-7">{{page_router.ip_address}} : {{page_router.port}}</dd>

                            <dt class="col-5">User:Pass</dt>
                            <dd class="col-7">{{page_router.username}} : {{page_router.password}}</dd>

                            <dt class="col-5">Kayıtlı Kullanıcı</dt>
                            <dd class="col-7">{{page_router.lanips.length}}</dd>

                            <dt class="col-5">Aktif Kullanıcı</dt>
                            <dd class="col-7">
                                <span v-if="process_name == 'router_check'" class="spinner spinner-border spinner-border-sm"></span>
                                <span v-else>{{ page_router.account_active }}</span>
                            </dd>
                        </dl>
                        <div v-if="page_router.description">
                            <div class="hr-text my-2">Not</div>
                            <p>{{ page_router.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <div class="input-group input-group-flat">
                        <input type="text" v-model="filter.lanip_search" placeholder="LAN IP Ara" autocomplete="off" class="form-control">
                        <span class="input-group-text">
                            <a href="#" @click.prevent="filter.lanip_search = null" v-if="filter.lanip_search" class="link-secondary" title="" data-bs-toggle="tooltip" data-bs-original-title="Clear search">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </a>
                          </span>
                    </div>
                </div>
                <div class="card" style="max-height: 28rem">
                    <div class="card-header">
                        <h3 class="card-title">LAN IP Listesi - <small>{{page_router.lanips.length}} Adet</small></h3>
                        <div class="card-actions">
                            <Link :href="route('router.lanip.create',page_router)" class="btn-action" v-b-tooltip.bottom.hover title="Yeni LAN IP Ekle"><plus-icon/></Link>
                        </div>
                    </div>
                    <div class="table-responsive  card-body-scrollable card-body-scrollable-shadow">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th class="w-1">Durum</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ip in filterLanIP()">
                                <td>
                                    {{ip.ip_address}}
                                </td>
                                <td>

                                </td>
                                <td>
                                    <a href="#" type="button"
                                       @click.prevent="deleteIP(route('router.lanip.destroy',[page_router,ip]),'lan'+ip.id)"
                                       :class="{'btn-loading':process_name == 'ip_delete_lan'+ip.id}"
                                       v-b-tooltip.leftbottom.hover title="LAN IP yi sil"
                                          class="btn btn-sm btn-icon btn-link"><trash-icon class="text-secondary"/></a>
                                </td>
                            </tr>
                            <tr v-if="!page_router.lanips.length">
                                <td colspan="4" class="text-muted text-center">Liste Boş.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <div class="input-group input-group-flat">
                        <input type="text" v-model="filter.wanip_search" placeholder="WAN IP Ara" autocomplete="off" class="form-control">
                        <span class="input-group-text">
                            <a href="#" @click.prevent="filter.wanip_search = null" v-if="filter.wanip_search" class="link-secondary" title="" data-bs-toggle="tooltip" data-bs-original-title="Clear search">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </a>
                          </span>
                    </div>
                </div>
                <div class="card" style="max-height: 28rem">
                    <div class="card-header">
                        <h3 class="card-title">WAN IP Listesi - <small>{{page_router.wanips.length}} Adet</small></h3>
                        <div class="card-actions">
                            <Link :href="route('router.wanip.create',page_router)" class="btn-action" v-b-tooltip.bottom.hover title="Yeni WAN IP Ekle"><plus-icon/></Link>
                        </div>
                    </div>
                    <div class="table-responsive  card-body-scrollable card-body-scrollable-shadow">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th class="w-1">Port</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ip in filterWanIP()">
                                <td>
                                    {{ip.ip_address}}
                                </td>
                                <td>
                                    {{getWanPorts(ip.ip_address).length}} /
                                    {{page_router.wanips.filter(f=>f.ip_address == ip.ip_address).length}}
                                </td>
                                <td>
                                    <a href="#" type="button" @click.prevent="deleteIP(route('router.wanip.destroy',[page_router,ip]),'wan'+ip.id)"
                                       :class="{'btn-loading':process_name == 'ip_delete_wan'+ip.id}"
                                       v-b-tooltip.leftbottom.hover title="WAN IP yi sil"
                                          class="text-decoration-none"><trash-icon class="text-secondary"/></a>
                                </td>
                            </tr>
                            <tr v-if="!page_router.wanips.length">
                                <td colspan="4" class="text-muted text-center">Liste Boş.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "~/Layouts/AppLayout";
import PageHeader from "~/Components/PageHeader";
import {Link} from "@inertiajs/inertia-vue";
import TextInput from "~/Components/TextInput";

export default {
    components: {TextInput, AppLayout, PageHeader, Link},
    props:{
        page_router: Object,
        page_used_ips: Object
    },
    data(){
        return {
            process_name: null,
            filtered_lanips: [],
            filtered_wanips: [],
            filter: {
                lanip_search: null,
                wanip_search: null,
            }
        }
    },
    mounted() {
         setTimeout(this.routerCheck,100)
    },
    methods:{
        routerCheck(){
            this.$inertia.reload({
                only: ['page_router'],
                onBefore: () => { this.process_name = 'router_check';},
                onFinish: () => { this.process_name = null; }
            })
        },
        deleteRouter(){
            this.swalConfirm('Bu kaydı silmek istediğinize emin misiniz?').then((sor) => {
                if(sor.isConfirmed){
                    this.$inertia.delete(route('router.destroy',this.page_router),{
                        headers: {'X-Laradius-ForceDelete':true}
                    })
                }
            })
        },
        deleteIP(href,id = 0){
            this.swalConfirm('Bu IP kaydını silmek istediğinize emin misiniz?').then((sor) => {
                if(sor.isConfirmed){
                    this.$inertia.delete(href,{
                        onBefore: () => this.process_name = 'ip_delete_'+id,
                        onFinish: () => this.process_name = null
                        // headers: {'X-Laradius-ForceDelete':true}
                    })
                }
            })
        },
        filterLanIP(){
            if( this.filter.lanip_search ){
                return this.page_router.lanips.filter(f => f.ip_address.includes(this.filter.lanip_search))
            }else{
                return this.page_router.lanips
            }
        },
        filterWanIP(){
            if( this.filter.wanip_search ){
                return _.uniqBy(this.page_router.wanips,'ip_address').filter(f => f.ip_address.includes(this.filter.wanip_search))
            }else{
                return _.uniqBy(this.page_router.wanips,'ip_address')
            }
        },
        getWanPorts(ip_address){
            if(ip_address){
                return this.page_router.wanips
                    .filter(f=>f.ip_address==ip_address && this.page_used_ips.wan.includes(f.id))
            }

        }
    }
}
</script>
