<template>
  <div>
    <label for="parent" class="form-label">
      <span v-text="options.name"></span>
      <small v-if="options.mandatory > 0" class="text-danger">* (required)</small>
    </label>
    <input type="text" class="form-control" id="fee" v-model="options.value" :placeholder="options.placeholder">
    <span v-if="options.note">
      <small v-text="options.note"></small>
    </span>
  </div>
</template>

<script>
export default {
  name: 'textfield-container',
  props: {
    options: {
      type: Object
    }
  }
}
</script>

<style lang="scss" scoped>
</style>