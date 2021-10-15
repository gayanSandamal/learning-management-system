import axios from 'axios'

export function getFeatureDownloads (successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-feature-downloads'
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}
