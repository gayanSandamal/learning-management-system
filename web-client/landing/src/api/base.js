import axios from 'axios'
import Cookies from 'js-cookie'
import router from './../router/index.js'
import eventBus from './../eventBus'

export function baseWithToken (obj, successCallBack, errorCallBack) {
  obj.data = obj.data ? obj.data : {}
  const TOKEN = Cookies.get('token')
  const USERNAME = Cookies.get('username')
  obj.data.username = USERNAME
  axios({
    method: obj.method ? obj.method : 'post',
    url: obj.url,
    data: obj.data ? obj.data : undefined,
    headers: { 'Authorization': 'Bearer ' + TOKEN}
  })
  .then(response => {
    if (response.status === 200 && response.data.status === 'success') {
      successCallBack(response)
    } else {
      errorCallBack('Error on sending')
    }
  })
  .catch(error => {
    if (error.response.status === 403) {
      router.push({ path: '/' })
      eventBus.$emit('fade-out-modal')
      eventBus.$emit('logout')
    } else {
      errorCallBack(error)
    }
  })
}
