<template>
  <div class="page-inner">
    <component :is="dashboard"/>
  </div>
</template>

<script>
import { roles } from '@/enum/enum'
export default {
  name: 'dashboard',
  components: {
    AdminDashboard: () => import('./dashboards/AdminDashboard')
  },
  props: {
    account: undefined
  },
  computed: {
    dashboard () {
      const roleId = this.account.roleTypeId
      let component = ''
      switch (roleId) {
        case roles.admin:
          component = 'AdminDashboard'
          break;
        case roles.instructor:
          component = ''
      }
      return component
    }
  }
}
</script>