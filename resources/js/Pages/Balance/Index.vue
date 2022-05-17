<template>
    <AppLayout>
        <PageHeader>
            <div class="col">
                <div class="page-title">Hesap Bakiyesi</div>
                <div class="page-subtitle">{{ user.full_name }} - {{user.tc_kn}}</div>
            </div>
        </PageHeader>
        <div class="row mb-4 justify-content-center">
            <div class="col-md-6 col-lg-3">
                <CardBalance :amount="user.balance_tl" />
                <Link :href="route('balance.create')" class="card card-link mt-3">
                    <div class="card-body font-weight-bold text-center"><credit-card-icon class="me-2"/> Bakiye yükle</div>
                </Link>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bakiye hareketleri <small class="text-muted">(Son 10 kayıt)</small></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th class="w-1">Tarih</th>
                                <th>Açıklama</th>
                                <th>Referans</th>
                                <th class="w-1">Tutar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="history in page_balance_history">
                                <td>
                                    <span>{{history.created_at|momentH}}</span>
                                    <span class="d-none d-lg-inline"> – {{ history.created_at|momentFromNow }}</span>
                                </td>
                                <td><strong>{{ history.description }}</strong></td>
                                <td>
                                    <template v-if="history.referenceable_type == page_classes.Plan && history.referenceable">
                                        <span class="text-muted">Paket adı: </span>{{history.referenceable.name}}
                                    </template>
                                    <template v-else-if="history.referenceable_type == page_classes.PaymentHistory && history.referenceable">
                                        <span class="text-muted">Ödeme ID: {{history.referenceable.result_payment_id}}</span>
                                    </template>
                                </td>
                                <td>
                                    <span v-if="history.amount > 0"
                                          class="h4 mb-0 p-1 rounded bg-azure-lt d-block w-full text-center">+<vue-autonumeric tag="span" :value="history.amount"></vue-autonumeric></span>
                                    <span v-else class="h4 mb-0 p-1 rounded bg-red-lt d-block w-full text-center" ><vue-autonumeric tag="span" :value="history.amount"></vue-autonumeric></span>
                                </td>
                            </tr>
                            <tr v-if="!page_balance_history.length">
                                <td colspan="4" class="text-muted text-center">Liste boş.</td>
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
import VueAutonumeric from "~/Components/VueAutonumeric";
import {Link} from "@inertiajs/inertia-vue";
import CardBalance from "~/Components/CardBalance";
export default {
    name: "BalanceIndex",
    components: {CardBalance, PageHeader, AppLayout, VueAutonumeric, Link},
    props:{
        user: Object,
        page_balance_history: Array,
        page_classes:Object
    }
}
</script>

<style scoped>

</style>
