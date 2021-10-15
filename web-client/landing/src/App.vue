<template>
  <div id="app" class="bg-light">
    <div class="d-flex">
      <div v-if="!isFromApp" class="left sidebar-wrapper">
        <Sidebar class="d-none d-lg-block" :menus="menus" :account="account"/>
      </div>
      <div class="mid w-100">
        <Header v-if="!isFromApp" :eventBus="eventBus" :account="account"/>
        <router-view class="page" :class="{'is-from-app': isFromApp}" :eventBus="eventBus" :account="account" :menus="menus" :baseUrl="baseUrl"/>
      </div>
    </div>
    <ModalContainer :eventBus="eventBus" :account="account"/>
  </div>
</template>

<script>
import Sidebar from '@/components/common/Sidebar'
import Header from '@/components/common/Header'
// import { getLocatliation } from '@/api/auth'
import eventBus from './eventBus'
import Cookies from 'js-cookie'
import { getAccount } from '@/api/auth'
export default {
  name: 'app',
  data () {
    return {
      eventBus: undefined,
      account: {
        id: null,
        username: null,
        firstname: null,
        lastname: null,
        email: null
      },
      menus: []
    }
  },
  components: {
    ModalContainer: () => import ('@/components/common/ModalContainer'),
    Sidebar: Sidebar,
    Header: Header
  },
  computed: {
    baseUrl() {
      return process.env.VUE_APP_BASE_URL
    },
    currentRoute() {
      return this.$route
    },
    isFromApp () {
      return this.currentRoute.name === 'WatchLessons' || false
    }
  },
  methods: {
    // getLocatliation() {
    //   const obj = {
    //     local: 'en',
    //     section: this.currentRoute.name
    //   }
    //   getLocatliation(obj, response => {
    //     console.log(response.data)
    //   }, error => {
    //     console.error(error)
    //   })
    // },
    setAccount(obj) {
      this.account = obj
    },
    logout() {
      Cookies.remove('token')
      Cookies.remove('username')
      this.account = {
        id: null,
        username: null,
        firstname: null,
        lastname: null,
        email: null
      }
      this.menus = []
      this.$router.push({ path: '/' })
      window.top.postMessage({
        action: 'logout'
      }, '*')
    },
    setMenus(menuList) {
      this.menus = []
      this.menus = menuList
    },
    getAccount() {
      getAccount({}, response => {
        this.eventBus.$emit('account', response)
      })
    }
  },
  watch: {
    currentRoute: {
      handler () {
        this.eventBus.$emit('close-profile-menu')
      }
    }
  },
  created() {
    this.eventBus = eventBus
  },
  mounted() {
    this.eventBus.$on('account', this.setAccount)
    this.eventBus.$on('logout', this.logout)
    this.eventBus.$on('set-menus', this.setMenus)
  },
  beforeDestroy() {
    this.eventBus.$off('account', this.setAccount)
    this.eventBus.$off('logout', this.logout)
    this.eventBus.$off('set-menus', this.setMenus)
  }
}
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap');
@import "./assets/css/reset.css";
// https://flexboxgrid.com/
// @import "./assets/css/flexboxgrid.min.css";
@import "./assets/css/bootstrap5.0.0-beta1.css";
@import "./assets/css/style.scss";
@import "./assets/css/responsive.scss";
@import 'vue-content-placeholders/src/styles.scss';
.sidebar-wrapper {
  z-index: 1000;
}
</style>
