<template>
  <div class="ps-3 py-3">
    <router-view :eventBus="eventBus" :account="account"></router-view>
  </div>
</template>

<script>
export default {
  name: 'console',
  props: {
    eventBus: undefined,
    account: undefined
  },
  mounted() {
    // https://www.npmjs.com/package/material-icons
    const menus = [
      {
        id: 1,
        label: 'Dashboard',
        link: '/console/dashboard/',
        icon: 'dashboard',
        roles: [1023, 1024, 1025, 1026]
      },
      {
        id: 2,
        label: 'LMS',
        link: undefined,
        icon: 'menu_book',
        roles: [1024, 1025, 1026],
        menus: [
          {
            id: 1,
            label: 'Classes',
            link: '/console/lms/categories/1',
            icon: 'category',
            roles: [1025, 1026]
          },
          {
            id: 2,
            label: 'Lessons',
            link: '/console/lms/lessons',
            icon: 'menu_book',
            roles: [1023, 1024, 1025, 1026]
          },
          {
            id: 3,
            label: 'Add Lesson',
            link: '/console/lms/lesson',
            icon: 'menu_book',
            roles: [1025, 1026]
          }
        ]
      },
      {
        id: 3,
        label: 'Users',
        link: '/console/users/',
        icon: 'supervisor_account',
        roles: [1025, 1026]
      },
      {
        id: 4,
        label: 'Payments',
        link: '/console/manage-payments/',
        icon: 'payment',
        roles: [1025, 1026]
      }
    ]
    
    this.eventBus.$emit('set-menus', menus)
  }
}
</script>