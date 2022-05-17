<template>
    <div class="card">
        <div class="card-body">
            <div class="divide-y-2">
                <div v-for="log in activity">
                    <div class="row">
                        <div class="col-auto border-end" style="min-width: calc(13rem + 10px)">
                            <UserInfo avatar v-if="log.causer" :user="log.causer"></UserInfo>
                            <span v-else class="text-muted" style="width: 50px;height: 50px">
                                <brand-tabler-icon class=" mt-1" style="width: 35px;height: 35px;">
                                </brand-tabler-icon> <span class="d-inline-block pb-2">System</span>
                            </span>
                        </div>
                        <div class="col-auto">
                            <div class="text-truncate d-inline-block">
                                {{log.description}}
                            </div>
                            <div class="text-muted text-truncate d-inline-block">
                                —
                                <span class="font-weight-light" v-b-tooltip.bottom :title="log.created_at|momentH">{{ log.created_at|momentFromNow }}</span>
                            </div>
                            <div>
                                <Link :href="route('ticket.show',log.subject_id)"
                                      v-if="log.subject_type === 'App\\Models\\Ticket'">
                                    <mail-icon></mail-icon>
                                    {{log.subject.subject}}
                                </Link>
                                <span v-else-if="log.subject_type === 'App\\Models\\User'">
                                <user-icon></user-icon>
                                <UserInfo class="d-inline-flex" :user="log.subject" no-email :online="false"></UserInfo>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Avatar from "~/Components/Avatar";
import UserInfo from "~/Components/UserInfo"
import {Link} from "@inertiajs/inertia-vue";
export default {
    name: "TabUserActivityLogs",
    props:{
        activity:{
            type: Array,
            required: true
        }
    },
    components:{Avatar, UserInfo, Link},

    data(){
        return {
        }
    },
    mounted() {

    }
}
</script>

<style scoped>

</style>
