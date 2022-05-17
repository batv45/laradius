<template>
  <AppLayout title="Abone Oluştur">
    <PageHeader :back-url="route('account.edit',page_account)">
        <div class="col">
            <div class="page-title"><edit-icon class="text-secondary"/> Abone Düzenle</div>
            <div class="page-subtitle">#{{page_account.id}} — {{page_account.full_name}} - {{page_account.email}} - {{page_account.phone}}</div>
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
                      <h3 class="card-title">Bağlantı Bilgileri</h3>
                  </div>
                  <div class="card-body">
                      <TextInput label="PPPoE Kullanıcı Adı" v-model="form_account.username" :error="form_account.errors.username" class="mb-3"></TextInput>
                      <TextInput label="PPPoE Şifre" v-model="form_account.password" :error="form_account.errors.password" class="mb-3"></TextInput>
                      <TextInput v-model="form_account.lan_ip" :error="form_account.errors.lan_ip" class="mb-3" label="LAN IP Adres" />
                      <TextInput v-model="form_account.wan_ip" :error="form_account.errors.wan_ip" class="mb-3" label="WAN IP Adres" />
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
    components: {TextInput, AppLayout,PageHeader},
    props:{
        page_account: Object
    },
    data(){
        return {
            form_account: this.$inertia.form({
                username: this.page_account.username,
                password: this.page_account.password,
                first_name: this.page_account.first_name,
                last_name: this.page_account.last_name,
                email: this.page_account.email,
                phone: this.page_account.phone,
                addres: this.page_account.address,
                lan_ip: this.page_account.lan_ip,
                wan_ip: this.page_account.wan_ip,
            })
        }
    },
    methods:{
        submitForm(){
            this.form_account.put(route('account.update',this.page_account))
        }
    }
}
</script>
