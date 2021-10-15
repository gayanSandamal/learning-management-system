<template>
  <ul class="nav navbar-dark flex-column sidebar-left">
    <li class="nav-item">
      <router-link class="nav-link text-white" :to="{name: 'Dashboard'}">
        <small>Dashboard</small>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link text-white" :to="{name: 'Profile'}">
        <small>Profile</small>
      </router-link>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" role="button" @click="eventBus.$emit('logout')">
        <small>Logout</small>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  name: 'menus',
  props: {
    eventBus: undefined,
    sidebar: {
      type: Object
    }
  }
}
</script>

<style lang="scss" scoped>
ul {
  &.sidebar-left {
    li {
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.75px;
    }
  }
}
</style>