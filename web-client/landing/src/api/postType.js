import axios from 'axios'
import { baseWithToken } from '@/api/base'

export function getCatsByPostType (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/post-type',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function addCatsByPostType (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/add-categories',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function updateCategory (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/update-categories',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function deleteCategoryPermanently (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/delete-category-permanently',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getPaymentSummary (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-payment-summary',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function addEnrollment (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/add-enrollment',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getLesson (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-lesson',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function initAttempts (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/init-attempts',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function setAttempts (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/set-attempts',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}