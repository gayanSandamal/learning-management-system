<template>
  <div class="page">
    <section class="banner bg-color-5 py-5">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h3 class="display-5 text-white mb-4">
              <strong>Language Classes</strong>
            </h3>
            <h5 class="text-white">
              Dreaming an overseas profession and language matters? Learn more and join to our classes right now
            </h5>
          </div>
          <div class="col-md-4">
            <img class="w-100 d-block banner-side-image" :src="require(`@/assets/images/home/classes/language-classes.png`)"/>
          </div>
        </div>
      </div>
    </section>
    <section class="my-5">
      <div class="container">
        <ClassGroups :thumbnail="'language-classes.png'" :account="account"/>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'language-classes',
  components: {
    ClassGroups: () => import('@/components/common/ClassGroups')
  },
  props: {
    account: undefined
  }
}
</script>

<style lang="scss" scoped>
.banner {
  // height: 300px;
}
</style>