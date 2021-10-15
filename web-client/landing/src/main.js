import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import VueToastr from 'vue-toastr'
import VTooltip from 'v-tooltip'
import VueContentPlaceholders from 'vue-content-placeholders'

Vue.config.productionTip = false

Vue.use(VueToastr)
Vue.use(VTooltip)
Vue.use(VueContentPlaceholders)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

const meta = document.createElement('meta');
meta.httpEquiv = "expires";
meta.content = "0";
document.getElementsByTagName('head')[0].appendChild(meta);