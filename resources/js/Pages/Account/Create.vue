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
                                  v-model="form_account.router_id">
                              <option value="" hidden disabled selected>Router seçiniz.</option>
                              <option :value="router.id" v-for="router in page_routers">{{router.identity}} - {{router.ip}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.router_id}}</div>
                      </div>

                      <div class="hr-text">LAN IP</div>
                      <div class="mb-3">
                          <label class="form-label required">LAN IP Bloğu</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.lan_subnet}"
                                  :disabled="!form_account.router_id"
                                  v-model="form_account.lan_subnet">
                              <option value="" hidden disabled selected>IP bloğu seçiniz.</option>
                              <option
                                  v-if="getSubnets('lan').length"
                                  :value="ip.id"
                                  v-for="ip in getSubnets('lan')">
                                  {{ ip.ip }}/{{ ip.subnet_mask }}
                              </option>
                              <option value="" v-if="!getSubnets('lan').length"
                                      disabled selected>LAN IP bloğu bulunamadı.</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.lan_subnet}}</div>
                      </div>
                      <div class="mb-3">
                          <label class="form-label required">LAN IP</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.lan_ip}"
                                  v-model="form_account.lan_ip">
                              <option value="" hidden disabled selected>Boş IP seçiniz.</option>
                              <option v-if="getUsableIPs('lan',form_account.lan_subnet)"
                                      :value="ipp" v-for="ipp in getUsableIPs('lan',form_account.lan_subnet)
                                      .filter(ff => !page_used_lan_ips.includes(ff))">{{ipp}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.lan_ip}}</div>
                      </div>

                      <div class="hr-text">WAN IP</div>
                      <div class="mb-3">
                          <label class="form-label required">WAN IP Bloğu</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.wan_subnet}"
                                  :disabled="!form_account.router_id"
                                  v-model="form_account.wan_subnet">
                              <option value="" hidden disabled selected>IP bloğu seçiniz.</option>
                              <option
                                  v-if="getSubnets('wan').length"
                                  :value="ip.id"
                                  v-for="ip in getSubnets('wan')">
                                  {{ ip.ip }}/{{ ip.subnet_mask }} - {{ ip.account_limit }}
                              </option>
                              <option value="" v-if="!getSubnets('wan').length"
                                      disabled selected>WAN IP bloğu bulunamadı.</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.wan_subnet}}</div>
                      </div>

                      <div class="mb-3">
                          <label class="form-label required">WAN IP</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.wan_ip}"
                                  v-model="form_account.wan_ip">
                              <option value="" hidden disabled selected>Boş IP seçiniz.</option>
                              <option v-if="getUsableIPs('wan',form_account.wan_subnet)"
                                      :value="ipp" v-for="ipp in getUsableIPs('wan',form_account.wan_subnet)">{{ipp}} -
                                  {{page_used_wan_ips.filter(f=>f == ipp).length}} /
                              {{getSubnets('wan').find(ff => ff.id == form_account.wan_subnet) ?
                                      getSubnets('wan').find(ff => ff.id == form_account.wan_subnet).account_limit : ''}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.wan_ip}}</div>
                      </div>
                      {{form_account.wan_port}}
                      <div class="mb-3">
                          <label class="form-label required">WAN Port</label>
                          <select class="form-select"
                                  :class="{'is-invalid': form_account.errors.wan_port}"
                                  :disabled="!form_account.wan_subnet"
                                  v-model="form_account.wan_port">
                              <option value="" hidden disabled selected>WAN Port aralığı seçiniz.</option>
                              <option v-if="getPortRanges()"
                                      :value="port" v-for="(port,key) in getPortRanges()">{{port.min}} - {{port.max}}</option>
                          </select>
                          <div class="invalid-feedback">{{form_account.errors.wan_port}}</div>
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
        page_used_lan_ips: Array,
        page_used_wan_ips: Array,
        page_used_ports: Array,
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
                lan_subnet: '',
                wan_subnet: '',
                lan_ip: null,
                wan_ip: null,
                wan_port:'',
                router_id:''
            }),
        }
    },
    methods:{
        submitForm(){
            this.form_account.post(route('account.store'))
        },
        getSubnets(filter = null){
            let router = this.page_routers.find(r => r.id == this.form_account.router_id)

            if( router && router.hasOwnProperty('ips') ){
                if(filter != null)
                    return router.ips.filter( f => f.type == filter)
                else
                    return router.ips
            }else
                return [];
        },
        getUsableIPs(filter = null , id){
            let subnet = this.getSubnets(filter)
            if( subnet.length > 0 && id > 0 ){
                let ips = subnet.find(f => f.id == id)
                return (ips && ips.hasOwnProperty('usable_ips') ) > 0 ? ips.usable_ips : []
            }else{
                return []
            }
        },
        getPortRanges(){
            let wansub = this.getSubnets('wan').find(f => f.id == this.form_account.wan_subnet)
            if( wansub && wansub.hasOwnProperty('port_range') ){
                let ports = wansub.port_range;
               forEach(this.page_used_ports,(p) => ports = reject(ports,(r) => r.max <= p.max && r.min >= p.min))
                return ports
            }else{
                return []
            }
        }
    }
}
</script>
