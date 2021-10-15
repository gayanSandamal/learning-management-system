import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import VueToastr from 'vue-toastr'

Vue.config.productionTip = false

Vue.use(VueToastr)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
