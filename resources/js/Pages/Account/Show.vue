<template>
    <AppLayout title="Abone Görüntüle">
        <PageHeader :back-url="route('account.index')">
            <div class="col">
                <div class="page-title"><search-icon class="text-secondary"/> Abone Görüntüle</div>
                <div class="page-subtitle">#{{page_account.id}} — {{page_account.full_name}} - {{page_account.email}} - {{page_account.phone}}</div>
            </div>

            <template #actions>
                <b-dropdown text="Seçenekler" variant="primary">
                    <Link :href="route('account.edit',page_account)" class="dropdown-item">
                        <edit-icon  class="dropdown-item-icon"/> Düzenle
                    </Link>
                    <b-dropdown-item class="text-danger" @click.prevent="deleteAccount">
                        <trash-icon  class="dropdown-item-icon text-danger"/> Aboneliği SİL
                    </b-dropdown-item>
                </b-dropdown>
            </template>
        </PageHeader>
        <div class="row">
            <div class="col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Bağlantı Bilgileri
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-5">Durum:</dt>
                            <dd class="col-7 strong">
                                <span v-if="process_name == 'meta_load'">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </span>
                                <span class="status status-green" v-else-if="page_meta.connection && page_meta.connection.name == page_account.username">
                                  <span class="status-dot status-dot-animated"></span>
                                  Online
                                </span>
                                <span class="status status-red" v-else>
                                    <span class="status-dot"></span>
                                  Offline
                                </span>
                            </dd>
                            <dt class="col-5">LAN IP:</dt>
                            <dd class="col-7">{{page_account.lan_ip}}</dd>
                            <dt class="col-5">WAN IP:</dt>
                            <dd class="col-7">{{page_account.wan_ip}}</dd>
                            <dt class="col-5">PPPoE Kullanıcı Adı:</dt>
                            <dd class="col-7">{{page_account.username}}</dd>
                            <dt class="col-5">PPPoE Şifre:</dt>
                            <dd class="col-7">{{page_account.password}}</dd>
                        </dl>
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
    props: {
        page_account: Object,
        page_meta: Object
    },
    data(){
        return {
            process_name: 'meta_load',
        }
    },
    mounted() {
        this.getMeta(false);
        setInterval(this.getMeta, 3000);
    },
    methods:{
        deleteAccount(){
            this.swalConfirm('Bu aboneliği silmek istediğinize emin misiniz?')
                .then(sor => {
                    if(sor.isConfirmed){
                        this.$inertia.delete(route('account.destroy',this.page_account))
                    }
                })
        },
        getMeta(show){
            if( show )
                this.process_name = 'meta_load'
            this.$inertia.reload({
                only: ['page_meta'],
                onFinish: () => {
                    this.process_name = null;
                }
            })
        }
    }
}
</script>

