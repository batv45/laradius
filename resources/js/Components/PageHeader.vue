<template>
    <div class="page-header">
        <div class="row align-items-center">
            <slot />
            <div class="col-12 col-sm-auto text-end mt-2 mt-sm-0 align-self-baseline">
                <slot name="actions">
                </slot>
                <div v-if="backUrl || backHistory" class="d-inline-block ms-2 "><span class="d-none d-sm-inline">
                  <Link :href="getUrl()" @click="history()" :class="backText===''?'btn-icon':''" class="btn btn-dark">
                      <ArrowBackUpIcon></ArrowBackUpIcon>
                    {{ backText }}
                  </Link>
                </span>
                    <span class="d-sm-none">
                  <Link :href="getUrl()" class="btn btn-dark btn-icon">
                      <ArrowBackUpIcon></ArrowBackUpIcon>
                  </Link>
                </span></div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue";

export default {
    components: {
        Link
    },
    props:{
        backHistory:{
            type:Boolean,
            default:false
        },
        backUrl:{
            type:String,
            default:null
        },
        backUrlNull:{
            type:String,
            default:null
        },
        backText:{
            type:String,
            default:'Geri dön'
        },
        backColor:{
            type:String,
            default:'dark'
        }
    },
    methods:{
        history(){
            if(this.backHistory)
                window.history.back();
            else
                return null
        },
        getUrl(){
            let appback = this.$page.props.app.back_url;

            if( this.backUrl !== null){
                return this.backUrl;
            }else if( this.backUrlNull !== null ){
                return appback === route(route().current(),route().params) ? this.backUrlNull : appback
            }else
                return undefined;
        }
    }
}
</script>

<style scoped>

</style>
