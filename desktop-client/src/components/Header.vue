<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3 py-0 pe-0">
    <div class="container-fluid pe-12px">
      <router-link class="navbar-brand" :to="{name: 'Dashboard'}"><small>{{page}}</small></router-link>
      <button class="navbar-toggler" type="button" @click="toggleMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="w-100" style="-webkit-app-region: drag; cursor: all-scroll!important; height: 41px;"></div>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item d-flex">
            <!-- <a class="nav-link active" aria-current="page" style="-webkit-app-region: drag; cursor: all-scroll!important;">
              <i class="fas fa-arrows-alt"></i>
            </a> -->
            <a class="nav-link active bg-success px-3" aria-current="page" role="button" @click="minimize">
              <i class="fas fa-window-minimize"></i>
            </a>
            <a class="nav-link active bg-warning px-3" aria-current="page" role="button" @click="maximize">
              <i class="fas fa-window-maximize"></i>
            </a>
            <a class="nav-link active bg-danger px-3" aria-current="page" role="button" @click="close">
              <i class="fas fa-times"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
const { ipcRenderer: ipc } = require('electron')
export default {
  name: 'header-component',
  props: {
    sidebar: {
      type: Object
    }
  },
  computed: {
    page () {
      return this.$route.meta.name
    }
  },
  methods: {
    toggleMenu () {
      this.$set(this.sidebar, 'state', !this.sidebar.state)
    },
    minimize () {
      ipc.send('minimize')
    },
    maximize () {
      ipc.send('maximize')
    },
    close () {
      ipc.send('close')
    }
  }
}
</script>

<style lang="scss" scoped>
.pe-12px {
  padding-right: 12px !important;
}
</style>