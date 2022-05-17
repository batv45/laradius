<template>
    <AppLayout>
        <PageHeader :back-url-null="route('admin.user.index')">
            <div class="col">
                <h3 class="page-title"><UserIcon/> – Kullanıcı Düzenle</h3>
                <div class="page-subtitle"><span class="text-muted">#{{ page_user.id }}</span> - <strong>{{ page_user.full_name }} —
                    <BadgeUserRole :user="page_user"/>
                </strong> — {{page_user.email}}</div>
            </div>
        </PageHeader>
        <div class="row" v-if="!page_user.tc_verified_at">
            <div class="col">
                <Alert type="danger" class="alert-important" icon>Kullanıcı T.C. Kimlik numarası onaylı <strong>değil</strong> !</Alert>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <Avatar :user="page_user"
                                    :size="100"
                                    class="d-inline-block mb-1"></Avatar>
                            <UserInfo :user="page_user"
                                      medals
                                      :link="false"
                            ></UserInfo>
                        </div>

                        <div class="hr m-3 mx-0"></div>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a aria-current="page" class="nav-link active"
                                   data-bs-toggle="tab"
                                   href="#tabs-profile"
                                >
                                    <user-icon class="me-2"></user-icon>
                                    Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab"
                                   href="#tabs-authorization"
                                >
                                    <shield-icon class="me-2"/>
                                    Yetkilendirme</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab"
                                   href="#tabs-activity-logs"
                                >
                                    <activity-icon class="me-2"/>
                                    İşlem Kayıtları</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tab-content">
                    <div class="tab-pane active show animate__animated animate__fadeIn animate__faster"
                         id="tabs-profile">
                        <TabUserEdit :user="page_user"></TabUserEdit>
                    </div>
                    <div class="tab-pane animate__animated animate__fadeIn animate__faster "
                         id="tabs-authorization">
                        <TabUserAuthorization :user="page_user" :roles="page_roles" :permissions="page_permissions"></TabUserAuthorization>
                    </div>
                    <div class="tab-pane animate__animated animate__fadeIn animate__faster"
                         id="tabs-activity-logs">
                        <TabUserActivityLogs :activity="page_activity"></TabUserActivityLogs>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "../../../Layouts/AppLayout";
import PageHeader from "../../../Components/PageHeader";
import TablerSwitch from "../../../Components/TablerSwitch";
import Alert from "~/Components/Alert";
import TextareaAutosize from "vue-textarea-autosize"
import Avatar from "~/Components/Avatar";
import BadgeUserRole from "../../../Components/BadgeUserRole";
import TabUserEdit from "~/Pages/Admin/User/Components/TabUserEdit";
import TabUserAuthorization from "~/Pages/Admin/User/Components/TabUserAuthorization";
import UserInfo from "~/Components/UserInfo";
import UserInfoMedals from "~/Components/UserInfoMedals";
import TabUserActivityLogs from "~/Pages/Admin/User/Components/TabUserActivityLogs";

export default {
    metaInfo:{
        title : 'Kullanıcı Düzenle'
    },
    name: "AdminUserEdit",
    components: {
        TabUserActivityLogs,
        UserInfoMedals,
        UserInfo,
        TabUserAuthorization,
        TabUserEdit, BadgeUserRole, Alert, TablerSwitch, PageHeader, AppLayout, TextareaAutosize, Avatar},
    props:{
        page_user:Object,
        page_permissions:Array,
        page_roles:Array,
        page_activity:Array
    },
    data(){
        return {
            back_url:null,
            process_name:null,

        }
    },
    mounted() {
        this.back_url = this.$page.props.app.back_url;

    },
    methods:{
        hasTab(event){
          window.location.hash = event.target.hash;
        },
        submitPermissionUpdate(){
            this.process_name = "permission_update"
            this.$inertia.put(route('admin.user.update_permission',this.page_user),{permissions:this.form_permissions},{
                preserveScroll : true,
                onFinish:()=>{
                    this.form_permissions = this.$page.props.page_user.permissions_all.map(per=>per.name)
                }
            })
        },
        submitRoleUpdate(){
            this.process_name = "role_update"
            this.$inertia.put(route('admin.user.update_role',this.page_user),{role:this.form_role},{
                preserveScroll : true,
                onFinish:()=>{
                    this.form_role = this.$page.props.page_user.roles[0].name
                    this.form_permissions = this.$page.props.page_user.permissions_all.map(per=>per.name)
                }
            })
        },


    }
}
</script>

<style scoped>

</style>
