<template>
  <div id="app">
    <div v-if="account.id" class="row">
      <Header :sidebar="sidebar"/>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div v-if="account.id" class="col-lg-2 bg-dark">
          <Sidebar :eventBus="eventBus" :sidebar="sidebar"/>
        </div>
        <div class="" :class="account.id ? 'col-lg-10 fh-head' : 'col-lg-12 vh-100'">
          <router-view class="w-100 pt-4" :eventBus="eventBus" :account="account"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const { ipcRenderer: ipc } = require('electron')
import eventBus from './eventBus'
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
      sidebar: {
        state: false
      }
    }
  },
  components: {
    Header: () => import('@/components/Header.vue'),
    Sidebar: () => import('@/components/Sidebar.vue')
  },
  methods: {
    setAccount(obj) {
      this.account = obj
    },
    getAccount() {
      getAccount({}, response => {
        this.eventBus.$emit('account', response)
      })
    },
    logout(isInitial) {
      debugger
      sessionStorage.clear()
      this.account = {
        id: null,
        username: null,
        firstname: null,
        lastname: null,
        email: null
      }
      if (!isInitial) {
        ipc.send('close')
      }
      this.deleteAllCookies()
      this.$router.push({name: 'Login'})
    },
    deleteAllCookies() {
      const cookies = document.cookie.split(';')
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i]
        const eqPos = cookie.indexOf('=')
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT'
      }
    },
    onlineStatus (event) {
      console.log(event)
    }
  },
  created() {
    window.addEventListener('online', this.onlineStatus)
    window.addEventListener('offline', this.onlineStatus)
    this.eventBus = eventBus
    window.addEventListener('message', message => {
      debugger
      const action = message.data.action
      switch(action) {
        case 'logout':
          this.logout()
          break;
        // case y:
        //   // code block
        //   break;
        default:
          // code block
      }
    })
  },
  mounted() {
    this.eventBus.$on('account', this.setAccount)
    this.eventBus.$on('logout', this.logout)
    this.getAccount()
  },
  beforeDestroy() {
    this.eventBus.$off('account', this.setAccount)
    this.eventBus.$off('logout', this.logout)
  }
}
</script>

<style lang="scss">
// https://mdbgo.io/marta-szymanska/mdb5-demo-pro/pro/theme/full-demo.html
@import url('./assets/styles/bootstrap@5.0.0-beta2.min.css');
@import url('./assets/styles/mdb.dark.min.css');
@import url('./assets/styles/all.css');
// .vnt-input__control {
//   min-width: auto;
//   width: 100%;
// }
.toast:not(.showing):not(.show) {
  opacity: 1 !important;
}
.fh-head {
  height: calc(100vh - 41px);
  overflow-x: hidden;
  overflow-y: auto;
}
.oveflow-x-hidden {
  overflow-x: hidden;
}
.month-picker__container {
  background-color: #fff;
}
.month-picker-input {
  width: 100%;
}
.z-index-1 {
  z-index: 1;
}
.z-index-2 {
  z-index: 2;
}
.z-index-3 {
  z-index: 3;
}
.z-index-99 {
  z-index: 99;
}
* {
  user-select: none;
}
.dark {
  .month-picker__container {
    background-color: #303030;
  }
  .month-picker__year button {
    background-color: #303030;
    color: #fff;
  }
  .month-picker__month {
    flex-basis: calc(33.333% - 0px);
    background-color: #303030;
  }
}
</style>
