<template>
  <div class="header position-relative d-flex align-items-center justify-content-between px-3 w-100 shadow-1 bg-white">
    <!-- <Menu/> -->
    <div v-html="'&nbsp;'"></div>
    <div class="d-flex">
      <!-- <Button :label="'Change language'" :labelColor="'color-8'">
        <span class="btn-icon me-2" v-html="languageIcon"></span>
      </Button> -->
      <div v-if="account && account.id">
        <div class="dropdown">
          <Button :label="accName" :labelColor="'color-1'" @click="isProfileDropdownOpen = !isProfileDropdownOpen">
            <!-- <span class="btn-icon me-2" v-html="languageIcon"></span> -->
          </Button>
          <div class="overlay" v-if="isProfileDropdownOpen" @click="isProfileDropdownOpen = false"></div>
          <ul class="dropdown-menu d-block" v-if="isProfileDropdownOpen">
            <li><router-link class="dropdown-item" to="console/dashboard">Console</router-link></li>
            <!-- <li><a class="dropdown-item" href="#">Another action</a></li> -->
            <li><div class="dropdown-item" role="button" @click="eventBus.$emit('logout')">Logout</div></li>
          </ul>
        </div>
      </div>
      <div v-else>
        <Button :label="'Log in'" @click="$router.push({path: '/login'})"/>
        <!-- <Button class="ms-4" :label="'Register'" :labelColor="'color-5'" @click="goToRegister"/> -->
        <Button class="ms-4" :label="'Register'" :labelColor="'color-5'" @click="$router.push({path: '/register'})"/>
      </div>
    </div>
  </div>
</template>

<script>
// import Menu from '@/components/common/Menu'
import { language } from '@/assets/scripts/svg'
export default {
  name: 'header-comp',
  data() {
    return {
      isProfileDropdownOpen: false
    }
  },
  props: {
    eventBus: undefined,
    account: undefined
  },
  components: {
    // Menu,
    Button: () => import('@/components/common/Button')
  },
  computed: {
    currentRoute() {
      return this.$route
    },
    languageIcon() {
      return language('#E474A3')
    },
    accName() {
      return this.account ? `${this.account.firstname} ${this.account.lastname}` : undefined
    }
  },
  methods: {
    goToRegister() {
      if (this.currentRoute.name === 'Home') {
        this.eventBus.$emit('reset-form', true)
      } else {
        this.$router.push({path: '/'})
      }
    },
    closeProfileMenu() {
      this.isProfileDropdownOpen = false
    }
  },
  mounted() {
    this.eventBus.$on('close-profile-menu', this.closeProfileMenu)
  },
  beforeDestroy() {
    this.eventBus.$off('close-profile-menu', this.closeProfileMenu)
  }
}
</script>
