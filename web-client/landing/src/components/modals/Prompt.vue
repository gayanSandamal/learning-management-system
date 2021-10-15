<template>
  <div class="modal d-block" @click.stop="$emit('on-close')">
    <div class="modal-dialog modal-dialog-centered" @click.stop="emptyMethod">
      <div class="modal-content">
        <div v-if="title" class="modal-header">
          <h5 class="modal-title" v-text="title"></h5>
        </div>
        <div v-if="isBody" class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <Button :label="closeLabel" :labelColor="'color-7'" @click.stop="$emit('on-close')"/>
          <Button :label="okLabel" :labelColor="'color-5'" v-if="showOkayButton" :disabled="disabled" @click.stop="!disabled ? $emit('on-ok') : emptyMethod"/>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'prompt',
  data() {
    return {
    }
  },
  components: {
    Button: () => import('@/components/common/Button')
  },
  props: {
    title: {
      type: String,
      default: null
    },
    isBody: {
      type: Boolean,
      default: true
    },
    okLabel: {
      type: String,
      default: null
    },
    closeLabel: {
      type: String,
      default: null
    },
    btn: {
      type: String,
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    },
    showOkayButton: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    emptyMethod() {
    }
  },
  mounted() {
    document.querySelector('body').classList.add('modal-open')
  },
  beforeDestroy() {
    document.querySelector('body').classList.remove('modal-open')
  }
}
</script>

<style lang="scss" scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.3);
  overflow-x: hidden;
  overflow-y: auto;
}
</style>