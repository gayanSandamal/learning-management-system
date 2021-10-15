<template>
  <button class="py-2 px-4 rounded-pill" :class="[labelColor, disabled ? 'dim' : 'clickable']" :disabled="disabled" @click="$emit('click', $event)">
    <slot></slot>
    <span v-text="label"></span>
  </button>
</template>

<script>
export default {
  name: 'button-comp',
  props: {
    labelColor: {
      type: String,
      default: 'color-4'
    },
    label: {
      type: String,
      default: 'Button'
    },
    disabled: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss" scoped>
button {
  outline: none;
}
</style>