<template>
    <AppLayout>
        <PageHeader>
            <div class="col">
                <div class="page-title">Abonelikler</div>
            </div>
            <template #actions>
                <Link :href="route('account.create')" class="btn btn-primary"><plus-icon/> Yeni Abone</Link>
            </template>
        </PageHeader>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter table-nowrap">
                            <thead>
                            <tr>
                                <th class="fst-italic w-1">#ID</th>
                                <th class="w-1">Ad Soyad</th>
                                <th class="w-1">E-Posta</th>
                                <th class="w-1">Telefon</th>
                                <th>Adres</th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="account in page_accounts">
                                <td>#{{account.id}}</td>
                                <td>{{account.full_name}}</td>
                                <td>{{account.email}}</td>
                                <td>{{account.phone}}</td>
                                <td>{{account.address}}</td>
                                <td>
                                    <div class="btn-group">
                                        <Link :href="route('account.show',account)" class="btn btn-light">
                                            <chevron-right-icon/>
                                            Görüntüle
                                        </Link>
                                        <Link :href="route('account.edit',account)" class="btn btn-icon btn-light"
                                              v-b-tooltip.hover.bottom
                                              title="Düzenle">
                                            <edit-icon/>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!page_accounts.length">
                                <td colspan="6" class="text-muted text-center">Liste boş.</td>
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
import {Link} from "@inertiajs/inertia-vue";
export default {
    metaInfo:{title:'Aboneler'},
    components: {AppLayout,PageHeader, Link},
    props: {
        page_accounts: Array
    }
}
</script>

