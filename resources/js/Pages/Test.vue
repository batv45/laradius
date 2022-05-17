<template>
    <AuthLayout>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <pre>{{form_adres.data()}}</pre>
                <pre>cities : {{page_cities ? page_cities.length : ''}}</pre>
                <pre>{{filterSemt()}}</pre>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">ülke:</label>
                    <b-form-select v-if="page_countries.length"
                       class="form-select"
                        v-model="form_adres.country"
                        :options="page_countries.map(m => ({ value:m.id,text:m.name}))"></b-form-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">İl:</label>
                    <b-form-select v-if="cities.length"
                                   class="form-select"
                        v-model="form_adres.city"
                        :options="cities.map(m => ({value:m.id,text:m.name}))"></b-form-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">İlçe:</label>
                    <b-form-select v-if="filterDistrict().length"
                                   class="form-select"
                        v-model="form_adres.district"
                        :options="filterDistrict()"></b-form-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mahalle:</label>
                    <b-form-select v-if="filterSemt().length"
                           class="form-select"
                        v-model="form_adres.locality"
                        :options="filterSemt()"></b-form-select>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script>
import AuthLayout from "~/Layouts/AuthLayout";
import {uniqBy} from "lodash";
import SelectInput from "~/Components/SelectInput";
export default {
    name: "Test",
    components: {SelectInput, AuthLayout},
    props:{
        page_countries:Array,
        page_cities:Array,
        page_districts:Array
    },
    data(){
        return {
            form_adres : this.$inertia.form({
                country:null,
                city:null,
                district:null,
                locality:null,
                street:null
            }),
            cities : [],
            districts : []
        }
    },
    mounted() {
        // this.form_adres.district = 45
    },
    watch:{
        'form_adres.country'(val){
            this.form_adres.city = null
            this.cities = []
            this.form_adres.district = null
            this.districts = []
            this.form_adres.locality = null

            this.$inertia.reload({
                headers:{'X-QT-COUNTRY':val},
                only:['page_cities'],
                onSuccess: (page) => {
                    this.cities = page.props.page_cities
                }
            })
        },
        'form_adres.city'(val){
            this.form_adres.district = null
            this.districts = []

            this.$inertia.reload({
                headers:{'X-QT-CITY':val},
                only:['page_districts'],
                onSuccess: (page) => {
                    this.districts = page.props.page_districts
                    // this.form_adres.district = 'Soma'
                }
            })
        }
    },
    methods:{
        filterDistrict(){
            return uniqBy(this.districts,'ilce')
            .map(m => ({
                    value:m.ilce,
                    text:m.ilce
                }
            ))
        },
        filterSemt(){
            let filter = uniqBy(this.page_districts,'semt')
                .filter(f => f.ilce == this.form_adres.district)

            if( filter ){
                return  filter.map(m => ({
                        label:m.semt,
                        options:
                            uniqBy(this.page_districts,'mahalle').filter(f => f.semt == m.semt)
                                .map(s => ({value:s.id,text:s.mahalle}))
                    }
                ))
            }else return []

        }
    }
}
</script>

<style scoped>

</style>
