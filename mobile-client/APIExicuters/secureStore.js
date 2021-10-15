import * as SecureStore from 'expo-secure-store'
import AsyncStorage from '@react-native-async-storage/async-storage'

export const storeToken = async (value) => {
  return await SecureStore.setItemAsync('token', value)
}

export const storeUsername = async (value) => {
  await SecureStore.setItemAsync('username', value)
}

export const getToken = async () => {
  return await SecureStore.getItemAsync('token')
}

export const getUsername = async () => {
  return await SecureStore.getItemAsync('username')
}

export const deleteToken = async () => {
  return await SecureStore.deleteItemAsync('token')
}

export const deleteUsername = async () => {
  return await SecureStore.deleteItemAsync('username')
}

export const clearAsyncStorage = async () => {
  const keys = ['@UserObject']
  return await AsyncStorage.multiRemove(keys)
}