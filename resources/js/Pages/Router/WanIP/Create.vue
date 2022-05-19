<template>
    <AppLayout title="Wan IP ekle">
        <PageHeader :back-url="route('router.show',page_router)">
            <div class="col">
                <div class="page-title">{{page_router.ip_address}} - Yeni WAN IP ekle</div>
            </div>
            <template #actions>
                <button type="button"
                        :class="{'btn-loading':process_name == 'wanip_create'}"
                        @click.prevent="submitForm()" class="btn btn-primary"><check-icon/> Kaydet</button>
            </template>
        </PageHeader>
        <form @submit.prevent="submitForm" class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ page_router.ip_address}} - Yeni LAN IP Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        <text-input label="IP Adres" required v-model="form_wanip.ip_address"
                                    placeholder="10.10.10.100"
                                    :error="form_wanip.errors.ip_address"></text-input>
                        <text-input label="Abone Sayısı" required type="number" v-model="form_wanip.account_limit"
                                    :error="form_wanip.errors.account_limit"></text-input>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>

<script>
import AppLayout from "~/Layouts/AppLayout";
import PageHeader from "~/Components/PageHeader";
import TextInput from "~/Components/TextInput";
export default {
    components: {TextInput, PageHeader, AppLayout},
    props:{
        page_router: Object
    },
    data(){
        return {
            process_name: null,
            form_wanip: this.$inertia.form({
                ip_address: null,
                account_limit: null
            })
        }
    },
    methods:{
        submitForm(){
            this.form_wanip.post(route('router.wanip.store',this.page_router),{
                onBefore: () => this.process_name = 'wanip_create',
                onFinish: () => this.process_name = null
            })
        }
    }
}
</script>
