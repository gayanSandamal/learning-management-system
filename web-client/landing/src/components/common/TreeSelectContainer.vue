<template>
  <div>
    <label for="parent" class="form-label">
      <span v-text="options.name"></span>
      <small v-if="options.mandatory > 0" class="text-danger">* (required)</small>
    </label>
    <treeselect v-model="options.value" :multiple="false" :options="options.list" />
    <span v-if="options.note">
      <small v-text="options.note"></small>
    </span>
  </div>
</template>

<script>
import Treeselect from '@riophae/vue-treeselect'
export default {
  name: 'treeselect-container',
  components: {
    Treeselect
  },
  props: {
    options: {
      type: Object
    }
  }
}
</script>

<style lang="scss" scoped>
</style>