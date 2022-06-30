<template>
    <AuthLayout>
        <h2 class="text-center">{{page_hotspot.name}}</h2>
        <h4 class="text-center mb-4">{{page_hotspot.description}}</h4>
        <form @submit.prevent="formSubmit" class="card">
            <div class="card-body">
                <div class="mb-3">
                    <FormInput label="Adınız" v-model="form_user.first_name" :error="form_user.errors.first_name"></FormInput>
                </div>
                <div class="mb-3">
                    <FormInput label="Soyadınız" v-model="form_user.last_name" :error="form_user.errors.last_name"></FormInput>
                </div>
                <div class="mb-3">
                    <FormInput label="Doğum Yılınız" v-model="form_user.birth_year" :error="form_user.errors.birth_year"></FormInput>
                </div>
                <div class="mb-3">
                    <FormInput label="TC Kimlik Numaranız" v-model="form_user.identity_number" :error="form_user.errors.identity_number"></FormInput>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" :class="{'btn-loading':form_user.processing}">Giriş</button>
            </div>
        </form>
    </AuthLayout>
</template>

<script>
import AuthLayout from "~/Layouts/AuthLayout";
import FormInput from "~/Components/FormInput";
export default {
    props:{
      page_hotspot: Object
    },
    components: {FormInput, AuthLayout},
    data(){
      return {
          form_user : this.$inertia.form({
              first_name: null,
              last_name: null,
              birth_year: null,
              identity_number: null,

          })
      }
    },
    methods:{
        formSubmit(){
            this.form_user.post(route('hotspot.login',this.page_hotspot))
        }
    }
}
</script>
