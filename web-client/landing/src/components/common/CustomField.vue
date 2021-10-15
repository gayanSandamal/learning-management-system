<template>
  <div class="row">
    <div class="col-12 mb-3" v-for="field in fields" :key="field.id">
      <component :is="currentComponent(field.type)" :options="field"></component>
    </div>
  </div>
</template>

<script>
export default {
  name: 'custom-field',
  components: {
    TextFieldContainer: () => import('@/components/common/TextFieldContainer'),
    TreeSelectContainer: () => import('@/components/common/TreeSelectContainer')
  },
  props: {
    fields: {
      type: Array
    }
  },
  methods: {
    currentComponent(type) {
      let component;
      switch (type) {
        case 1:
          component = 'TextFieldContainer';
          break;
        case 2:
          component = 'TreeSelectContainer';
      }
      return component
    }
  }
}
</script>