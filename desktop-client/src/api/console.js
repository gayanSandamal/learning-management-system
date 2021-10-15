import axios from 'axios'
import { baseWithToken } from '@/api/base'

export function getUsers (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-users',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getUser (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-user',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function updateUser (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/update-user',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getCategory (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-category',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getUsersByRole (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-users-by-role',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getThumbnailUrl (video_id, successCallBack, errorCallBack) {
  const url = `https://vimeo.com/api/oembed.json?url=https://vimeo.com/${video_id}`
  axios.get(
    url
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function addPosts (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/add-posts',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function updatePost (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/update-post',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getCustomFields (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-custom-fields',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getAssignedClasses (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-assigned-classes',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getPosts (obj, successCallBack, errorCallBack) {
  axios.post(
    process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-posts',
    obj
  )
  .then(function (response) {
    successCallBack(response)
  })
  .catch(function (error) {
    errorCallBack(error)
  })
}

export function getPost (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-post',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getVideos (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-videos',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getClassGroups (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-class-groups',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getAvailableClasses (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-available-classes',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getPayments (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-payments',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function approvePayment (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/approve-payments',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getEnrolledClasses (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-enrolled-classes',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}

export function getEnrolledClassesFull (obj, successCallBack, errorCallBack) {
  baseWithToken({
    url: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/get-enrolled-classes-full',
    data: obj
  }, response => {
    successCallBack(response)
  }, error => {
    errorCallBack(error)
  })
}
