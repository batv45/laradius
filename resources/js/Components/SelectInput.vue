<template>
    <select type="text" class="form-select" ref="selectInput">
        <option value="tr" data-custom-properties="&lt;span class=&quot;flag flag-xs flag-country-tr&quot;&gt;&lt;/span&gt;">Türkiye (+90)</option>
    </select>
</template>

<script>
import TomSelect from 'tom-select'

export default {
    name: "SelectInput",
    props:{
        nullText:{
            type:String,
            default:'Seçim yapın'
        },
        options:{
            type:Object,
            required:true
        }
    },
    data(){
        return {
            tomSelect:null,
        }
    },
    mounted() {
        let selectInput = this.$refs.selectInput
        this.tomSelect = (new TomSelect(selectInput, {
            copyClassesToDropdown: false,
            dropdownClass: 'dropdown-menu',
            optionClass:'dropdown-item',
            controlInput: '<input>',
            render:{
                item: function(data,escape) {
                    if( data.customProperties ){
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function(data,escape){
                    if( data.customProperties ){
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
        }));
    },
}
</script>

<style scoped>

</style>
