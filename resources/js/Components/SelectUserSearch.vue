<template>
    <TomSelect v-model="val"
               :settings="select_settings"
               @input="handleInput">
        <slot/>
    </TomSelect>
</template>

<script>
import axios from "axios";
import TomSelect from "~/Components/TomSelect";

export default {
    name: "SelectUserSearch",
    components: {
        TomSelect
    },
    props:{
        value: {
            default: ''
        },
        settings: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            select_settings:{
                ...this.settings,
                openOnFocus: false,
                valueField: 'id',
                labelField: 'full_name',
                searchField: ['full_name','tc_kn'],
                load: function(query, callback) {
                    axios.post(route('api.user.search'),{query})
                        .then(response => {
                            if(response.data.users)
                                callback(response.data.users);
                        }).catch( () => {
                        callback()
                    })

                },
                render: {
                    option: function(data, escape) {
                        return '<div class="px-2">' +
                            '<div class="username">'+ escape(data.id)+' - ' + escape(data.full_name) + '</div>' +
                            '<div class="tcid text-muted">' + escape(data.tc_kn) + '</div>' +
                            '</div>';
                    },
                    item: function(data, escape) {
                        return '<div title="' + escape(data.tc_kn) + '">'
                            + '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1 icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\n' +
                            '   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>\n' +
                            '   <circle cx="12" cy="7" r="4"></circle>\n' +
                            '   <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>\n' +
                            '</svg>'
                            + escape(data.full_name) + ' <span class="text-muted ms-2">'+escape(data.tc_kn)+'</span> </div>';
                    }
                }
            },
            val:this.value
        }
    },
    methods:{
        handleInput (e) {
            this.$emit('input', this.val)
        }
    },
}
</script>
