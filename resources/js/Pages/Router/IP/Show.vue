<template>
    <AppLayout title="IP Görüntüle">
        <PageHeader :back-url="route('router.show',page_router)">
            <div class="col">
                <div class="page-title"><search-icon class="text-secondary"/> IP Detayları</div>
                <div class="page-subtitle">{{page_router.identity}} {{page_router.ip}} : {{page_router.port}}</div>
            </div>
        </PageHeader>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">IP Port Aralığı <small>Toplam: {{page_port_range.length}}</small></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <tbody>
                            <tr v-for="port in page_port_range">
                                <td>
                                    {{port.min}} - {{port.max}}
                                </td>
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
export default {
    components: {AppLayout,PageHeader},
    props:{
        page_router: Object,
        page_port_range: Object
    }
}
</script>
