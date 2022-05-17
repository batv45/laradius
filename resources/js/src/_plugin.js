import {Inertia} from '@inertiajs/inertia'
import Vue from 'vue'

export default {
    install: (app, options) => {
        Inertia.on('error', (errors) => {
            console.log(errors)
            Vue.prototype.$notyf.error('Bir hata oluştu!')
        })
        app.mixin({
            methods: {
                moment: (date) => app.moment(date),
                route: (name, params, absolute) => route(name, params, absolute),
                asset(url){

                    return this.$page.props.app.asset_url+ url;
                },
                userHasRole(role,user=null){
                    user = user === null ? this.$page.props.user : user;
                    return user.roles.some((r)=>r.name === role);
                },
                userHasPermission(perm,user=null){
                    user = user === null ? this.$page.props.user : user;
                    if( user.roles.some((r)=>r.name === 'super-admin') )
                        return true;
                    return user.permissions_all.some((p)=>p.name === perm);
                },
                swalConfirm(text){
                    return this.$swal({
                        title: 'Emin misin?',
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Hayır',
                        confirmButtonText: 'Evet'
                    })
                },
                swalConfirmHtml(html){
                    return this.$swal({
                        title: 'Emin misin?',
                        html: html,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Hayır',
                        confirmButtonText: 'Evet'
                    })
                }
            },
            filters: {
                momentD: function (date) {
                    return app.moment(date).format('DD/MM/YYYY');
                },
                momentH: function (date) {
                    return app.moment(date).format('DD/MM/YYYY HH:mm');
                },
                momentS: function (date) {
                    return app.moment(date).format('DD/MM/YYYY HH:mm:ss');
                },
                momentFromNow: function (date) {

                    return app.moment(date).fromNow();
                }
            }
        })
    }
}
