<template>
    <div>
        <label v-show="label" class="form-label" :class="{'required':required}">{{ label }}</label>
        <input
            class="form-control"
            type="text"
            :id="id"
            :placeholder="label"
            :value="value"
            :required="required"
            :class="{'is-invalid':name.length?$page.props.errors.hasOwnProperty(name):''}"
            v-on:input="updateValue($event.target.value)">

        <div class="invalid-feedback" v-if="name.length">{{ $page.props.errors[name]}}</div>
    </div>
</template>

<script>
export default {
    name: "FormInput",
    props: {
        id: {
            type: String
        },
        label: {
            type: String
        },
        value: {
            type: String
        },
        name: {
            type: String
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        updateValue: function (value) {
            this.$emit('input', value)
        }
    }
}
</script>

<style scoped>

</style>
