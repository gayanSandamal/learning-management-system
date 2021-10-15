<template>
  <div class="sidebar shadow-1 p-2" :class="{'with-menus': menus.length > 0}">
    <ul class="m-0 p-0">
      <li>
        <div class="d-flex align-items-center">
          <Logo :src="'logo-akurata.lk.svg'"/>
          <span v-if="menus.length > 0" class="mx-3 menu-label">HOME</span>
        </div>
      </li>
    </ul>
    <ul class="mt-5 p-0">
      <li v-for="menu in menus" :key="menu.id" v-show="isShow(menu)">
        <router-link v-if="menu.link" :to="menu.link" class="d-flex align-items-center text-decoration-none router-link mb-3">
          <span class="material-icons-outlined icon" v-text="menu.icon"></span>
          <span class="mx-3 text-uppercase menu-label" v-text="menu.label"></span>
        </router-link>
        <a v-else class="d-flex align-items-center text-decoration-none router-link mb-3" role="button" @click="expand(menu.id)">
          <span class="material-icons-outlined icon" v-text="menu.icon"></span>
          <span class="mx-3 text-uppercase menu-label" v-text="menu.label"></span>
        </a>
        <ul class="ps-3" v-if="menu.opened">
          <li v-for="childMenu in menu.menus" :key="childMenu.id">
            <router-link :to="childMenu.link" class="d-flex align-items-center text-decoration-none router-link mb-3">
              <span class="material-icons-outlined icon" v-text="childMenu.icon"></span>
              <span class="mx-3 text-uppercase menu-label" v-text="childMenu.label"></span>
            </router-link>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
import Logo from '@/components/common/Logo'
export default {
  name: 'sidebar',
  components: {
    Logo
  },
  props: {
    menus: {
      type: Array
    },
    account: undefined
  },
  methods: {
    expand(id) {
      const MENU = this.menus.find(o => o.id === id)
      this.$set(MENU, 'opened', true)
    },
    isShow (menu) {
      return menu && menu.roles && menu.roles.length > 0 ? menu.roles.indexOf(this.account.roleTypeId) > -1 : false
    }
  }
}
</script>

<style lang="scss" scoped>
$material-icons-font-path: '~material-icons/iconfont/';
@import '~material-icons/iconfont/material-icons.scss';
.menu-label {
  font-size: 11px;
  letter-spacing: 1px;
}
.with-menus {
  background: #fff;
}
</style>