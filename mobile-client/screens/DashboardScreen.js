import * as React from 'react'
import { StyleSheet, SafeAreaView } from 'react-native'
import AsyncStorage from '@react-native-async-storage/async-storage'

import { ApplicationProvider, Layout, Text } from '@ui-kitten/components'
import { EvaIconsPack } from '@ui-kitten/eva-icons'
import * as eva from '@eva-design/eva'

import Header from './../components/Header'
import Constants from 'expo-constants'
// import homeAPI from '../APIExicuters/homeAPI';
import { useState, useEffect } from 'react'

export default DashboardScreen = props => {
  const statusBarHeight = Constants.statusBarHeight
  // const [userClasses, setUserClasses] = useState([])
  const [user, setUser] = useState()

  useEffect(() => {
    getUser()
  }, [])

  const getUser = async () => {
    const value = await AsyncStorage.getItem('@UserObject')
    try {
      if (value !== null) {
        const user = JSON.parse(value)
        setUser(user)
      }
    } catch (error) {
    }
  }

  // const getUserEnrolledClasses = () => {
  //   var offSet = 0
  //   var perPage = 10
  //   homeAPI.getUserClasses(userDetails.username, userToken, userDetails.id, offSet, perPage, response => {
  //     setUserClasses(response.classes)
  //     spinner = false
  //   }, error => {
  //     console.error('error', error)
  //     spinner = false
  //   })
  // }
  return (
    <ApplicationProvider {...eva} theme={eva.dark}>
      <SafeAreaView style={{marginTop: statusBarHeight}}>
        <Header {...props} user={user}/>
      </SafeAreaView>
    </ApplicationProvider>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1
  }
})