<template>
  <AppLayout title="Abone Oluştur">
    <PageHeader :back-url="route('account.index')">
        <div class="col">
            <div class="page-title"><plus-icon class="text-secondary"/> Yeni Abone</div>
            <div class="page-subtitle">Bu sayfadan yeni abonelik oluşturabilirsiniz.</div>
        </div>
        <template #actions>
            <button type="button" @click="submitForm" class="btn btn-primary"><check-icon/> Kaydet</button>
        </template>
    </PageHeader>
      <form @submit.prevent="submitForm" class="row justify-content-center">
          <!-- Account Details -->
          <div class="col-lg-4 col-md-6">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">Kişisel Bilgiler</h3>
                  </div>
                  <div class="card-body">
                      <TextInput label="Ad" v-model="form_account.first_name" :error="form_account.errors.first_name" class="mb-3"></TextInput>
                      <TextInput label="Soyad" v-model="form_account.last_name" :error="form_account.errors.last_name" class="mb-3"></TextInput>
                      <TextInput label="E-Posta" v-model="form_account.email" :error="form_account.errors.email" class="mb-3"></TextInput>
                      <TextInput label="Telefon" v-model="form_account.phone" :error="form_account.errors.phone" class="mb-3"></TextInput>
                      <div class="mb-3">
                          <label class="form-label">Adres</label>
                          <textarea-autosize :class="{'is-invalid':form_account.errors.address}" v-model="form_account.address" class="form-control"></textarea-autosize>
                      </div>
                      <div class="invalid-feedback">{{ form_account.errors.address }}</div>
                  </div>
              </div>
          </div>
          <!-- Mikrotik Details -->
          <div class="col-lg-4 col-md-6">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">Mikrotik Bilgileri</h3>
                  </div>
                  <div class="card-body">
                      <div class="mb-3">
                          <label class="form-label required">Router</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.router_id}"
                                  :disabled="process_name == 'router_ips'"
                                  v-model="form_account.router">
                              <option value="" hidden disabled selected>Router seçiniz.</option>
                              <option :value="router" v-for="router in page_routers">{{router.identity}} - {{router.ip_address}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.router_id}}</div>
                      </div>
                      <div class="hr-text">LAN IP</div>
                      <div class="mb-3">
                          <label class="form-label required">LAN IP</label>
                          <select class="form-select"
                                  :disabled="!form_account.router"
                                  :class="{'is-invalid': form_account.errors.router_lanip_id}"
                                  v-model="form_account.router_lanip_id">
                              <option value="" hidden disabled selected>Boş IP seçiniz.</option>
                              <option :value="ipp.id" v-for="ipp in getLanIPS()">{{ipp.ip_address}}</option>
                              <option value="" disabled v-if="!getLanIPS().length">LAN IP yok.</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.router_lanip_id}}</div>
                      </div>
                      <div class="hr-text">WAN IP</div>
                      <div class="mb-3">
                          <label class="form-label required">WAN IP</label>
                          <select class="form-select"
                                  :disabled="!form_account.router"
                                  v-model="form_account.router_wanip_address">
                              <option value="" hidden disabled selected>Boş IP seçiniz.</option>
                              <option :value="ipp.ip_address" v-for="ipp in getWanIPS()">{{ipp.ip_address}} {{getWanPorts(ipp.ip_address).length}} / {{
                                      form_account.router.wanips
                                        .filter(f=>f.ip_address == ipp.ip_address).length }}</option>
                              <option value="" disabled v-if="!getWanIPS().length">WAN IP yok.</option>
                          </select>
                      </div>
                      <div class="mb-3">
                          <label class="form-label required">WAN PORT <small>Boş port: {{getWanPorts().length}}</small></label>
                          <select class="form-select"
                                  :disabled="!form_account.router_wanip_address"
                                  :class="{'is-invalid': form_account.errors.router_wanip_id}"
                                  v-model="form_account.router_wanip_id">
                              <option value="" hidden disabled selected>Boş IP seçiniz.</option>
                              <option :value="ipp.id" v-for="ipp in getWanPorts()">{{ipp.port_min}} - {{ipp.port_max}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.router_wanip_id}}</div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Mikrotik Details -->
          <div class="col-lg-4 col-md-6">
              <div class="card">
                  <div class="card-header">
                      <h3 class="card-title">Bağlantı Bilgileri</h3>
                  </div>
                  <div class="card-body">
                      <TextInput label="PPPoE Kullanıcı Adı" v-model="form_account.username" :error="form_account.errors.username" class="mb-3"></TextInput>
                      <TextInput label="PPPoE Şifre" v-model="form_account.password" :error="form_account.errors.password" class="mb-3"></TextInput>
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
import {forEach, reject} from "lodash";

export default {
    components: {TextInput, AppLayout,PageHeader},
    props:{
        page_routers: Array,
        page_used_ips: Object
    },
    data(){
        return {
            process_name: null,
            form_account: this.$inertia.form({
                username: null,
                password: null,
                first_name: null,
                last_name: null,
                email: null,
                phone: null,
                address: null,
                router: '',
                router_lanip_id: '',
                router_wanip_address: '',
                router_wanip_id: '',
            }),
        }
    },
    methods:{
        submitForm(){
            this.form_account.transform((data) => ({
                ...data,
                router_id : data.router ? data.router.id : null
            })).post(route('account.store'))
        },
        getLanIPS(){
          if( this.form_account.router && this.form_account.router.lanips ){
              return this.form_account.router.lanips.filter(f => !this.page_used_ips.lan.includes(f.id))
          }else{
              return []
          }
        },
        getWanIPS(){
            if( this.form_account.router ){
                return _.uniqBy(this.form_account.router.wanips,'ip_address')
            }else{
                return []
            }
        },
        getWanPorts(ip_address = null){
            if(ip_address){
                return this.form_account.router.wanips
                    .filter(f=>f.ip_address==ip_address && this.page_used_ips.wan.includes(f.id))
            } else if( this.form_account.router ){
                return this.form_account.router.wanips
                    .filter(f=>f.ip_address==this.form_account.router_wanip_address && !this.page_used_ips.wan.includes(f.id))
            }else{
                return []
            }

        }
    }
}
</script>
