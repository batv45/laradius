<template>
    <AppLayout title="Mikrotik">
        <PageHeader>
            <div class="col">
                <div class="page-title">Mikrotik Cihazlar Listesi</div>
            </div>
        </PageHeader>
        <div class="row justify-content-center mb-4">
            <div class="col-md-12">
                <form @submit.prevent="submitCreate" class="card">
                    <div class="card-header">
                        <h3 class="card-title">Yeni Mikrotik Oluştur</h3>
                        <div class="card-actions">
                            <button type="submit" class="btn-action"
                                    :disabled="process_name == 'router_create'"
                                    v-b-tooltip.bottom.hover title="Kaydet">
                                <span v-if="process_name == 'router_create'"
                                      class="spinner-border spinner-border-sm" role="status"></span>
                                <check-icon v-else />
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <text-input label="Mikrotik IP" v-model="form_router.ip_address" :error="form_router.errors.ip_address"></text-input>
                            </div>
                            <div class="col-md-2">
                                <text-input label="Port" v-model="form_router.port" :error="form_router.errors.port"></text-input>
                            </div>
                            <div class="col-md-3">
                                <text-input label="Username" v-model="form_router.username" :error="form_router.errors.username"></text-input>
                            </div>
                            <div class="col-md-3">
                                <text-input label="Password" v-model="form_router.password" :error="form_router.errors.password"></text-input>
                            </div>
                        </div>
                    </div>
                    <CardProgress :process="process_name == 'router_create'"></CardProgress>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                            <tr>
                                <th class="w-1 text-muted fst-italic">#ID</th>
                                <th>IP:Port</th>
                                <th>User:Pass</th>
                                <th>Kayıtlı Abone</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="placeholder-glow" v-if="process_name == 'router_check'">
                                <td>
                                    <div class="placeholder placeholder-xs col-12"></div>
                                </td>
                                <td>
                                    <div class="placeholder placeholder-xs col-7"></div>
                                </td>
                                <td>
                                    <div class="placeholder placeholder-xs col-7"></div>
                                </td>
                                <td>
                                    <div class="placeholder placeholder-xs col-7"></div>
                                </td>
                                <td>
                                    <div class="placeholder placeholder-xs col-12"></div>
                                </td>
                            </tr>
                            <template v-else>
                                <tr v-for="router in page_routers">
                                    <td class="fst-italic text-muted">#{{router.id}}</td>
                                    <td>{{router.ip_address}} : {{router.port}}
                                        <span class="badge bg-warning" v-if="router.deleted_at">Arşiv</span>
                                    </td>
                                    <td>{{router.username}} : {{router.password}}
                                        <span v-if="process_name == 'router_check'" class="spinner ms-1 spinner-border spinner-border-sm"></span>
                                        <span v-else-if="router.client_status" class="badge bg-success ms-1"></span>
                                        <span v-else class="badge bg-red ms-1"></span>
                                    </td>
                                    <td>
                                        <span v-if="router.account_count">{{ router.account_count }} abone</span>
                                        <span v-else class="text-muted">Abone yok</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <Link :href="route('router.show',router.id)" class="btn btn-light">
                                                <chevron-right-icon/>
                                                Görüntüle
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!page_routers || !page_routers.length">
                                    <td colspan="5" class="text-muted text-center">Liste Boş.</td>
                                </tr>
                            </template>
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
import TextInput from "~/Components/TextInput";
import {Link} from "@inertiajs/inertia-vue";
import FormErrors from "~/Components/FormErrors";
import CardProgress from "~/Components/CardProgress";
export default {
    components: {CardProgress, FormErrors, TextInput, PageHeader, AppLayout, Link},
    props:{
        page_routers: Array
    },
    data(){
        return {
            process_name: null,
            form_router: this.$inertia.form({
                ip_address: null,
                port: 8728,
                username: 'admin',
                password: null,
            })
        }
    },
    mounted() {
        setTimeout(this.routerCheck,10)
    },
    methods:{
        submitCreate(){
            this.process_name = 'router_create'
            this.form_router.post(route('router.store'),{
                onSuccess: () => {
                  this.form_router.reset()
                    this.routerCheck()
                },
                onFinish: () => {
                    this.process_name = null
                }
            })
        },
        routerCheck(){
            this.$inertia.reload({
                only: ['page_routers'],
                onBefore: (visit) => {this.process_name = 'router_check';},
                onFinish: visit => {this.process_name = null;},
            })
        }
    }
}
</script>
