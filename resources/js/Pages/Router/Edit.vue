<template>
    <AppLayout title="Mikrotik Düzenle">
        <PageHeader :back-url="route('router.show',page_router)">
            <div class="col">
                <div class="page-title">Mikrotik Düzenle</div>
                <div class="page-subtitle">#{{page_router.id}} - {{page_router.ip}}:{{page_router.port}}</div>
            </div>
            <template #actions>
                <button type="button" @click.prevent="submitUpdate" class="btn btn-primary"><check-icon/> Kaydet</button>
            </template>
        </PageHeader>
        <form @submit.prevent="submitUpdate" class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mikrotik Bilgileri</h3>
                    </div>
                    <div class="card-body">
                        <text-input label="Mikrotik IP" v-model="form_router.ip" :error="form_router.errors.ip"></text-input>
                        <text-input label="Port" v-model="form_router.port" :error="form_router.errors.port"></text-input>
                        <text-input label="Username" v-model="form_router.username" :error="form_router.errors.username"></text-input>
                        <text-input label="Password" v-model="form_router.password" :error="form_router.errors.password"></text-input>
                        <div class="">
                            <textarea-autosize class="form-control"
                                               placeholder="Notunuz"
                                               v-model="form_router.description" :class="{'is-invalid':form_router.errors.description}"></textarea-autosize>
                            <div class="invalid-feedback">{{form_router.errors.description}}</div>
                        </div>
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
            form_router: this.$inertia.form({
                ip: this.page_router.ip,
                port: this.page_router.port,
                username: this.page_router.username,
                password: this.page_router.password,
                description: this.page_router.description
            })
        }
    },
    methods:{
        submitUpdate(){
            this.process_name = 'router_update'
            this.form_router.put(route('router.update',this.page_router.id),{
                onFinish: () => {
                    this.process_name = null
                }
            })
        }
    }
}
</script>
