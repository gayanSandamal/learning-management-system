<template>
  <div class="page">
    <section class="banner bg-color-5 py-5">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h3 class="display-5 text-white mb-4">
              <strong>Grade 1 to A/L</strong>
            </h3>
            <h5 class="text-white">
              We have classes starting from grade 1 to G.C.E advanced<br>level where you can select your class and join.
            </h5>
          </div>
          <div class="col-md-4">
            <img class="banner-side-image w-100 d-block" :src="require(`@/assets/images/home/classes/1-to-al.png`)"/>
          </div>
        </div>
      </div>
    </section>
    <section class="my-5">
      <div class="container">
        <ClassGroups :thumbnail="'1-to-al.png'" :account="account"/>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'grade-1-to-al',
  components: {
    ClassGroups: () => import('@/components/common/ClassGroups')
  },
  props: {
    account: undefined
  }
}
</script>

<style lang="scss" scoped>
</style>