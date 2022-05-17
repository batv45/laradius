<template>
    <div class="wrapper">
        <TopNavbar :container-type="containerType"></TopNavbar>
<!--        <Navbar></Navbar>-->
        <div class="page-wrapper">
            <div class=" pt-3" :class="'container-'+containerType" style="min-height: 350px">
                <div class="row justify-content-center" v-if="!$page.props.user.email_verified_at && $page.props.user.email">
                    <div class="col">
                        <Alert type="warning" icon>E-Posta adresinizin onaylanması gerekli. Gelen kutunuzu kontrol ediniz. —
                            <a href="#"
                               @click.prevent="sendVerificationMail"
                               class="alert-link">Yeniden gönder</a>.</Alert>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col">
                        <Alert v-if="!this.$page.props.user.tc_verified_at" type="warning" icon="">TC Kimlik
                            bilgilerinizi doğrulamanız gerekmektedir! —
                            <Link
                                :href="route('profile.edit')" class="alert-link">doğrula!
                            </Link>
                        </Alert>
                    </div>
                </div>
                <slot/>
            </div>
            <Footer></Footer>
        </div>
        <FlashMessage :notifications="this.$page.props.flash"/>
        <ScrollTop class="">
            <div class="bg-white"><span class="p-2 bg-primary rounded-2">
                <chevrons-up-icon class="me-0 text-white"> </chevrons-up-icon>
            </span></div>
        </ScrollTop>
    </div>
</template>

<script>

import FlashMessage from "../Components/FlashMessage";
import Footer from "./_Footer";
import Navbar from "./_Navbar";
import TopNavbar from "./_TopNavbar";
import Alert from "~/Components/Alert"
import {Link} from "@inertiajs/inertia-vue";
import ScrollTop from "~/Components/ScrollTop";

export default {
    name: "AppLayout",
    metaInfo(){
        return {
            title: this.title
        }
    },
    components: {TopNavbar, Navbar, Footer, FlashMessage, Alert, Link, ScrollTop},
    props:{
        title: String,
        containerType: {
            type: String,
            default: 'xl'
        }
    },
    data(){
        return {
            toTopShow:false,
        }
    },
    mounted() {

        window.addEventListener('focus',function(){
            // setTimeout(function(){ window.location.reload(); }, 700);
        })
    },
    methods:{
        sendVerificationMail(){
            this.$inertia.post(route('verification.send'))
        }
    },
}
</script>

<style scoped>
</style>
