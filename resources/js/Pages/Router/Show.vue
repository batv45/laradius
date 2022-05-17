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
                            <dd class="col-7">{{page_router.ip}} : {{page_router.port}}</dd>

                            <dt class="col-5">User:Pass</dt>
                            <dd class="col-7">{{page_router.username}} : {{page_router.password}}</dd>

                            <dt class="col-5">Kayıtlı Kullanıcı</dt>
                            <dd class="col-7">{{page_router.account_count}}</dd>

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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">IP Listesi</h3>
                        <div class="card-actions">
                            <Link :href="route('router.ip.create',page_router)" class="btn-action" v-b-tooltip.bottom.hover title="Yeni IP Ekle"><plus-icon/></Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th>IP</th>
                                <th>Tür</th>
                                <th class="w-1">Abone Sayısı</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="ip in page_router.ips">
                                <td>
                                    {{ip.ip}} / {{ip.subnet_mask}}
                                </td>
                                <td>
                                    {{ip.type_text}}
                                </td>
                                <td>
                                    {{ip.account_limit}}
                                </td>
                                <td>
                                    <Link :href="route('router.ip.show',[page_router,ip])" class="">Görüntüle</Link>
                                </td>
                            </tr>
                            <tr v-if="!page_router.ips.length">
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
export default {
    components: {AppLayout, PageHeader, Link},
    props:{
        page_router: Object
    },
    data(){
        return {
            process_name: null
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
        }
    }
}
</script>
