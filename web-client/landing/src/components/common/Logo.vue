<template>
  <div class="logo bg-color-0 shadow-inset-1 p-2 d-flex align-center">
    <router-link class="" to="/">
      <img alt="akurata.lk logo" class="w-100 d-block" :src="require(`@/assets/images/${src}`)"/>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'logo',
  props: {
    src: {
      type: String
    }
  }
}
</script>

<style lang="scss" scoped>
.logo {
  border-radius: 50%;
  width: 48px;
  height: 48px;
}
</style>