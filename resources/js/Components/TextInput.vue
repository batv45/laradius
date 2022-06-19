<template>
    <div :class="$attrs.class ? $attrs.class : 'mb-3'">
        <label v-if="label" class="form-label" :class="{'required': required}" :for="id">{{ label }}:</label>
        <input :id="id" ref="input"
               v-bind="{ ...$attrs, class: null }"
               class="form-control"
               :class="{
                    'is-invalid': error,
                   'form-number': type === 'number' ,
                   'form-control-sm': sm
                }"
               :type="type"
               :value="value"
               :required="required"
               @input="$emit('input', $event.target.value)" />
        <div v-if="error" class="invalid-feedback">{{ error }}</div>
        <small v-if="hint" class="form-hint"><question-mark-icon class="icon-sm me-1 ms-0 text-muted"/><span v-html="hint"></span></small>
    </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
    inheritAttrs: false,
    props: {
        required:{
          type: Boolean,
          default: false
        },
        sm:{
          type: Boolean,
          default: false
        },
        id: {
            type: String,
            default() {
                return `text-input-${uuid()}`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        value: undefined,
        error: String,
        label: String,
        hint: String,
        modelValue: String,
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
