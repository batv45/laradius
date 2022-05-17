<template>
    <AppLayout>
        <PageHeader :back-url="route('balance.index')">
            <div class="col">
                <div class="page-title">Bakiye Yükle</div>
            </div>
        </PageHeader>
        <div class="row">
            <div class="col">
                <FormErrors :errors="form_card.errors"></FormErrors>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="input-group mb-2">
                              <span class="input-group-text">
                                Ödeme tutarı
                              </span>
                                <vue-autonumeric class="form-control form-control-lg"
                                                 :class="{'is-invalid':form_card.errors.amount}"
                                                 v-model="form_card.amount"></vue-autonumeric>
                                <div class="invalid-feedback">{{form_card.errors.amount}}</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button :disabled="!form_card.amount" type="button"
                                    :class="{'btn-loading':process_name}"
                                    @click.prevent="submitPay"
                                    class="btn btn-primary w-100">
                                <span v-if="form_card.amount > 0 " class="me-1"><vue-autonumeric tag="span" :value="form_card.amount"></vue-autonumeric> için  </span>
                                Ödeme Yap
                            </button>
                        </div>
                    </div>
                    <div class="progress progress-sm card-progress" v-show="process_name">
                        <div class="progress-bar progress-bar-indeterminate">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bakiye yüklemeleri <small class="text-muted">(Son 10 kayıt)</small></h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th class="w-1">#ID</th>
                                <th class="w-1">Tarih</th>
                                <th>Açıklama</th>
                                <th class="w-1">Ödeme ID</th>
                                <th class="w-1">Tutar</th>
                                <th class="w-1">Durum</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="history in page_payment_history">
                                <td>
                                    <span class="text-muted fst-italic">#{{ history.id }}</span>
                                </td>
                                <td>
                                    <span>{{history.created_at|momentH}}</span>
                                    <span class="d-none d-lg-inline"> – {{ history.created_at|momentFromNow }}</span>
                                </td>
                                <td>
                                    <strong>{{ history.description }}</strong>
                                </td>
                                <td>
                                    {{history.result_payment_id}}
                                </td>
                                <td>
                                    <BadgeCurrency :amount="history.price"></BadgeCurrency>
                                </td>
                                <td class="">
                                    <span class="text-success" v-if="history.paymented_at"><circle-check-icon /> Ödendi</span>
                                    <template v-else>
                                        <a v-if="history.raw_result && history.raw_result.paymentPageUrl"
                                           :href="history.raw_result.paymentPageUrl"
                                                class="btn btn-sm btn-outline-warning"><question-mark-icon />Ödeme bekliyor</a>

                                        <span class="text-warning" v-else><question-mark-icon /> Ödeme yapılamadı</span>
                                    </template>
                                </td>
                            </tr>
                            <tr v-if="!page_payment_history.length">
                                <td colspan="4" class="text-center text-muted">Liste boş.</td>
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
import FormErrors from "~/Components/FormErrors";
import {mapValues, pickBy, throttle, trim} from "lodash";
import BadgeCurrency from "~/Components/BadgeCurrency";
export default {
    name: "BalanceCreate",
    metaInfo:{title:'Bakiye Yükle'},
    components: {BadgeCurrency, FormErrors, PageHeader, AppLayout, VueAutonumeric},
    props:{
        page_payment_history: Array
    },
    data(){
        return {
            process_name:null,
            form_card: this.$inertia.form({
                amount:1,
            })
        }
    },
    methods:{
        submitPay(){
            this.process_name = 'payment_submit'

            this.form_card.clearErrors()
            this.form_card.transform(data => ({
                ...data,
                amount: Math.abs(data.amount)
            })).post(route('balance.store'),{
                onFinish: () => this.process_name = null
            })
        }
    }
}
</script>

<style scoped>

</style>
