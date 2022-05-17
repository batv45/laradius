<template>
    <AppLayout>
        <div class="row mt-2">
            <div class="col-lg-3 col-3">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a aria-current="page" class="nav-link active" data-bs-toggle="tab" href="#tabs-profile"><user-icon class="me-2"></user-icon> Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabs-security"><shield-icon class="me-2"/> Güvenlik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabs-api">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-api me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 13h5"></path>
                                <path d="M12 16v-8h3a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-3"></path>
                                <path d="M20 8v8"></path>
                                <path d="M9 16v-5.5a2.5 2.5 0 0 0 -5 0v5.5"></path>
                            </svg>
                            API Yapılandırma</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabs-settings"><adjustments-icon class="me-2"/> Tercihler</a>
                    </li>
                </ul>
            </div>

            <div class="col">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-profile">
                        <TabProfile :user="page_user" :process_name="process_name"/>
                    </div>
                    <div class="tab-pane" id="tabs-security">
                        <TabSecurity :sessions="page_sessions"/>
                    </div>
                    <div class="tab-pane" id="tabs-settings">
                        <TabSettings :user="page_user" />
                    </div>
                    <div class="tab-pane" id="tabs-api">
                        <TabApi :tokens="page_tokens" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import FilledCircleCheckIcon from "../../Components/Icons/FilledCircleCheckIcon";
import {Link} from "@inertiajs/inertia-vue";
import Avatar from "vue-avatar";
import TabProfile from "./Components/TabProfile";
import TabSecurity from "./Components/TabSecurity";
import TabSettings from "./Components/TabSettings";
import Alert from '~/Components/Alert'
import TabApi from "~/Pages/User/Components/TabApi";

export default {
name: "Profile",
    metaInfo:{title:'Hesabım'},
    props:{
        page_sessions:Array,
        page_tokens:Array,
        errors:Object,
        page_user:Object
    },
    data(){
    return {
        process_name:null,
    }
    },
    components: {TabSettings, TabSecurity, TabProfile, TabApi, FilledCircleCheckIcon, AppLayout, Link, Avatar,Alert},
    methods:{
    }
}
</script>
