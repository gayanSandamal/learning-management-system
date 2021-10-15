import axios from 'axios'
import VueRouter from 'vue-router'
import router from './../router/index.js'
import eventBus from '@/eventBus'

export function baseWithToken (obj, successCallBack, errorCallBack) {
  obj.data = obj.data ? obj.data : {}
  const TOKEN = sessionStorage.getItem("token")
  const USERNAME = sessionStorage.getItem("username")
  obj.data.username = USERNAME
  obj.data.token = TOKEN
  const config = {
    headers: {
      'Authorization': 'Bearer ' + TOKEN,
      'Token': TOKEN
    }
  }
  axios({
    method: obj.method ? obj.method : 'post',
    url: obj.url,
    data: obj.data ? obj.data : undefined,
    config
  })
  .then(response => {
    if (response.status === 200 && response.data.status === 'success') {
      successCallBack(response)
    } else {
      errorCallBack('Error on sending')
    }
  })
  .catch(error => {
    if (error.response && error.response.status === 403) {
      debugger
      const currentRoute = new VueRouter().currentRoute
      router.push({ path: '/' })
      if (currentRoute.name === null) {
        eventBus.$emit('logout', true)
      } else {
        eventBus.$emit('logout')
      }
    } else {
      errorCallBack(error)
    }
  })
}
