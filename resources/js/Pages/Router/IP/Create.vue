<template>
  <AppLayout title="Yeni IP Ekle">
      <PageHeader :back-url="route('router.show',page_router)">
          <div class="col">
              <div class="page-title"><square-plus-icon class="text-secondary"/> Yeni IP Ekle</div>
              <div class="page-subtitle">Mikrotik: {{page_router.ip}} : {{page_router.port}}</div>
          </div>
          <template #actions>
              <button type="button" @click.prevent="submitCreate"
                      :disabled="process_name"
                      :class="{'btn-loading': process_name == 'router_ip_create'}"
                      class="btn btn-primary"><check-icon/> Kaydet</button>
          </template>
      </PageHeader>
      <form @submit.prevent="submitCreate" class="row justify-content-center">
          <div class="col-lg-5">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">IP Bilgileri</h3>
                  </div>
                  <div class="card-body">
                      <div class="mb-3">
                          <div class="form-selectgroup">
                              <label class="form-selectgroup-item" v-for="(key,val) in page_ip_types">
                                  <input type="radio" name="icons" v-model="form_ip.type" :value="key" class="form-selectgroup-input">
                                  <span class="form-selectgroup-label">
                                      <device-desktop-icon v-if="key==='lan'" class="me-1"/>
                                      <network-icon v-if="key==='wan'" class="me-1"/>
                                      {{val}}</span>
                              </label>
                          </div>
                          <div class="small mt-1 text-danger" v-if="form_ip.errors.type">{{form_ip.errors.type}}</div>
                      </div>
                      <text-input label="IP Bloğu" placeholder="10.10.10.0" :error="form_ip.errors.ip" v-model="form_ip.ip"></text-input>
                      <text-input label="Subnet" placeholder="24" :error="form_ip.errors.subnet_mask" v-model="form_ip.subnet_mask"></text-input>
                      <text-input type="number" class="col-lg-5" label="Abone Sayısı"
                                  :disabled="form_ip.type == 'lan'"
                                  :error="form_ip.errors.account_limit" v-model="form_ip.account_limit"></text-input>
                  </div>
                  <CardProgress :process="process_name == 'router_ip_create'"></CardProgress>
              </div>
          </div>
      </form>
  </AppLayout>
</template>

<script>
import AppLayout from "~/Layouts/AppLayout";
import PageHeader from "~/Components/PageHeader";
import TextInput from "~/Components/TextInput";
import CardProgress from "~/Components/CardProgress";
export default {
    components: {CardProgress, TextInput, PageHeader, AppLayout},
    props:{
        page_router: Object,
        page_ip_types: Object
    },
    data(){
        return {
            process_name: null,
            form_ip: this.$inertia.form({
                ip: '10.10.10.0',
                subnet_mask: 24,
                type: 'lan',
                account_limit: 0
            })
        }
    },
    watch:{
      'form_ip.type'(val){
            if( val == 'lan' ){
                this.form_ip.account_limit = 0;
                this.form_ip.subnet_mask = '24'
            }else if( val == 'wan' ){
                this.form_ip.subnet_mask = '32'
            }
      }
    },
    methods:{
        submitCreate(){
            this.process_name = 'router_ip_create'
            this.form_ip.post(route('router.ip.store',this.page_router),{
                onFinish: () => this.process_name = null
            })
        }
    }
}
</script>
