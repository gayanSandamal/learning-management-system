<template>
  <aside>
    <div class="d-none d-lg-block">
      <Menus :eventBus="eventBus"/>
    </div>
    <div v-if="sidebar.state" class="d-block d-lg-none">
      <Menus :eventBus="eventBus"/>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'sidebar',
  props: {
    eventBus: undefined,
    sidebar: {
      type: Object
    }
  },
  components: {
    Menus: () => import('@/components/Menus.vue')
  }
}
</script>