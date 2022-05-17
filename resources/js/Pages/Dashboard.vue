<template>
    <AppLayout>
        <PageHeader>
            <div class="col">
                <h3 class="page-title">Quattro Core System</h3>
            </div>
        </PageHeader>
        <div class="row mx-1" v-if="tc_verified_users > 0 && userHasPermission('user.show')">
            <Alert type="danger" icon class="alert-important"><strong>{{tc_verified_users}}</strong> adet T.C. Kimlik numarası onaylanmamış kullanıcı mevcut.
                — <Link :href="route('admin.user.index',{'tcverified':0})" class="alert-link">Görüntüle</Link>!
            </Alert>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 col-lg-3">
                <CardBalance :amount="$page.props.user.balance_tl" />
            </div>
        </div>
        <div class="row" v-if="userHasRole('super-admin')">
            <div class="col-lg-6" >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Son kayıt olan 10 kullanıcı</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                            <tr>
                                <th>Kullanıcı</th>
                                <th>E-Posta</th>
                                <th>Telefon</th>
                                <th class="w-1">Tarih</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in page_last_users">
                                <td>
                                    <UserInfo
                                        avatar
                                        medals
                                        :user="user" />
                                </td>
                                <td>{{ user.email }}</td>
                                <td>
                                    {{user.phone}}
                                </td>
                                <td>
                                    <span class="text-nowrap" v-b-tooltip.right.hover :title="user.created_at|momentH">{{ user.created_at|momentFromNow }}</span>
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
import AppLayout from "../Layouts/AppLayout";
import Avatar from "vue-avatar";
import PageHeader from "../Components/PageHeader";
import UserInfo from "~/Components/UserInfo";
import BadgeTicketStatus from "~/Components/BadgeTicketStatus";
import {Link} from "@inertiajs/inertia-vue";
import Alert from "~/Components/Alert"
import CardBalance from "~/Components/CardBalance";

export default {
    metaInfo:{title:'Ana Sayfa'},
    name: "Dashboard",
    components: {CardBalance, BadgeTicketStatus, UserInfo, PageHeader, Avatar, AppLayout, Link, Alert},
    props:{
        page_remaining_expenses:Array,
        tc_verified_users:Number,
        page_last_users:Array,
        page_last_apartments:Array
    },
    data(){
        return {
        }
    },
    mounted(){
    }
}
</script>

<style scoped>

</style>
