import * as React from 'react'
import { NavigationContainer } from '@react-navigation/native'
import { createStackNavigator } from '@react-navigation/stack'
import LoginScreen from '../screens/LoginScreen'
import DashboardScreen from '../screens/DashboardScreen'
import PasswordResetScreen from '../screens/PasswordResetScreen'
import SplashScreen from '../screens/SplashScreen'
import * as SecureStore from 'expo-secure-store'

import DashboardBottomTabs from './../components/DashboardBottomTabs'
// import DashboardTabContainer from './../screens/Dashboard/DashboardTabContainerScreen'

import { useEffect, useState } from 'react'

const Stack = createStackNavigator()

const CustomDarkTheme = {
    // ...DefaultTheme,
    dark: true,
    colors: {
        primary: '#00E096',
        text: '#FFFFFF',
        card: '#2E3A59'
    }
}

export default Main = () => {
    const [logged, setLogged] = useState(false)
    const [loading, setLoading] = useState(true)
    useEffect(() => {
        SecureStore.getItemAsync('token').then((result)=>{
            if (result !== null) {
                setLogged(true)
            } else {
                setLogged(false)
            }
            setLoading(false)
        }).catch((error)=>{
            console.error(error)
            setLogged(false)
            setLoading(false)
        })
    },[])

    if (loading) {
        return (
            <NavigationContainer>
                <Stack.Navigator>
                    <Stack.Screen options={{headerShown: false}} name="Loading" component={SplashScreen} />
                </Stack.Navigator>
            </NavigationContainer>
        )
    } else {
        console.log('logged', logged)
        return (
            <NavigationContainer theme={CustomDarkTheme}>
                <Stack.Navigator
                screenOptions={{headerShown: false}}>
                    <Stack.Screen name={logged ? "DashboardTabs" : "Login"} component={logged ? DashboardBottomTabs : LoginScreen} />
                    <Stack.Screen name="ResetPassword" component={PasswordResetScreen} />
                    <Stack.Screen name={!logged ? "DashboardTabs" : "Login"} component={!logged ? DashboardBottomTabs : LoginScreen} />
                    <Stack.Screen name="Dashboard" component={DashboardScreen} />
                </Stack.Navigator>
            </NavigationContainer>
        )
    }
}