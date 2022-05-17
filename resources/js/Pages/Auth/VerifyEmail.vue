<template>
    <AuthLayout>
        <div class="text-center mb-4">
            <Link :href="route('index')"><img :src="asset('static/logo.svg')" height="36" alt=""></Link>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h3 class="card-title">Doğrulama gerekli
                    <span class="float-end small"><Link :href="route('logout')"
                                                  class="btn btn-sm btn-danger"
                                                  method="post">Oturumu Kapat</Link></span></h3>
                <div class="markdown">
                    Hesabınızı doğrulamanız için e-posta adresinize <strong>({{ $page.props.user.email }})</strong> bir link gönderildi. — <Link
                    :href="route('verification.send')" method="post">Tekrar Gönder</Link>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import AuthLayout from "../../Layouts/AuthLayout";
export default {
    metaInfo:{
      title:'Hesabını Doğrula'
    },
    name: "VerifyEmail",
    components: {AuthLayout, Link}
}
</script>

<style scoped>

</style>
