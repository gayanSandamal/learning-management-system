import { Alert } from 'react-native'
import { deleteToken, deleteUsername, clearAsyncStorage } from './../APIExicuters/secureStore'

export const alertMsg = (msg) => {
  Alert.alert(msg)
}

export const logout = (successCallback, errorCallback) => {
  const deleteTokenPromise = deleteToken()
  const deleteUsernamePromise = deleteUsername()
  const clearAsyncStore = clearAsyncStorage()
  return Promise.all([deleteTokenPromise, deleteUsernamePromise, clearAsyncStore]).then(() => {
    successCallback()
  }).catch(() => {
    errorCallback()
  })
}