import axios from 'axios'
import { baseWithToken } from '@/api/base'

export function register (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/register',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function verifyEmail (obj, succsessCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/verify',
    obj
  )
  .then(function (response) {
    succsessCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function login (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/login',
    obj
  )
  .then(function (response) {
    if (response.status === 200 && response.data.status === 'success') {
      if (obj.rememberMe) {
        sessionStorage.setItem('token', response.data.token)
        sessionStorage.setItem('username', obj.username)
      } else {
        sessionStorage.setItem('token', response.data.token)
        sessionStorage.setItem('username', obj.username)
        // Cookies.set('token', response.data.token)
        // Cookies.set('username', obj.username)
      }
      successCallBack(response)
    } else {
      errorCallBack('Error on logging')
    }
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function sendResetEmail (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/reset-password',
    obj
  )
  .then(function (response) {
    if (response.status === 200 && response.data.status === 'success') {
      successCallBack(response)
    } else {
      errorCallBack('Error on sending')
    }
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getAccount (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-account',
    data: obj
  }, response => {
    successCallBack(response.data.data[0])
  }, error => {
    errorCallBack(error)
  })
}

export function getStates (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-states',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getDistricts (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-districts',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getCities (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-cities',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function sendVerification (obj, successCallBack, errorCallBack) {
  const OTP = generateOTP()
  const URL = `https://app.newsletters.lk/smsAPI?sendsms&apikey=jj40RVXfHAqDlGM2gNIShmPmzSjhfNYK&apitoken=3saL1613665175&type=sms&from=Akurata.lk&to=+94${obj.phone}&text=Your+verification+code+for+Akurata.lk+is+${OTP}&route=0`
  axios.get(
    URL
  )
  .then(function (response) {
    successCallBack(response, OTP)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

function generateOTP() {
  const digits = '0123456789' 
  let OTP = '' 
  for (let i = 0; i < 4; i++ ) { 
    OTP += digits[Math.floor(Math.random() * 10)]
  } 
  return OTP
}

// export function getLocatliation (obj, successCallBack, errorCallBack) {
//   axios.post(
//     process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-localization',
//     obj
//   )
//   .then(function (response) {
//     successCallBack(response)
//   })
//   .catch(function (error) {
//     errorCallBack(error)
//   })
// }