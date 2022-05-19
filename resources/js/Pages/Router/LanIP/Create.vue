<template>
    <AppLayout title="Lan IP ekle">
        <PageHeader :back-url="route('router.show',page_router)">
            <div class="col">
                <div class="page-title">{{page_router.ip_address}} - Yeni LAN IP ekle</div>
            </div>
            <template #actions>
                <button type="button"
                        :class="{'btn-loading':process_name == 'lanip_create'}"
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
                        <text-input label="IP Adres" required v-model="form_lanip.ip_address"
                                    placeholder="10.10.10.100"
                                    hint="Örn: 10.10.10.100 veya 10.10.10.100-254"
                                    :error="form_lanip.errors.ip_address"></text-input>
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
            form_lanip: this.$inertia.form({
                ip_address: null
            })
        }
    },
    methods:{
        submitForm(){
            this.form_lanip.post(route('router.lanip.store',this.page_router),{
                onBefore: () => this.process_name = 'lanip_create',
                onFinish: () => this.process_name = null
            })
        }
    }
}
</script>
