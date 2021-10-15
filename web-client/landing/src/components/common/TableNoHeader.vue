<template>
  <div class="w-100">
    <table v-for="category in categories" :key="category.id" class="table w-100 m-0">
      <tbody>
        <tr>
          <td class="t-col-1" v-text="category.index"></td>
          <td class="t-col-2" v-text="`${levelIndicator(category.level)}${category.name}`"></td>
          <td class="t-col-3">
            <span v-text="category.slug" v-tooltip="category.slug" class="text-line-1"></span>
          </td>
          <td class="t-col-4">
            <Button class="me-3 text-warning" :label="'Edit'" @click="$router.push({query: {modal: 'EditCategoryModal', post_type: 1, category_id: category.id}})"></Button>
          </td>
        </tr>
        <tr v-if="category.categories.length > 0">
          <td class="p-0" colspan="4">
            <table-no-header :categories="category.categories"></table-no-header>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import TableNoHeader from './TableNoHeader' 
export default {
  name: 'table-no-header',
  props: {
    categories: {
      type: Array
    }
  },
  components: {
    Button: () => import('@/components/common/Button'),
    TableNoHeader
  },
  methods: {
    levelIndicator (level) {
      let str = ''
      for (let step = 1; step <= level; step++) {
        str = str + '- '
      }
      return str + ' '
    }
  }
}
</script>

<style lang="scss" scoped>
.t-col-1 {
  width: 10%;
}
.t-col-2 {
  // width: 40%;
  width: 60%;
}
.t-col-3 {
  // width: 40%;
  width: 30%;
}
.t-col-4 {
  width: 10%;
}
</style>