import React from 'react'
import { StyleSheet, View } from 'react-native'
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs'

import DashboardTabScreen from './../screens/Dashboard/DashboardTabScreen'
import EnrollmentsTabScreen from './../screens/Dashboard/EnrollmentsTabScreen'
import ProfileTabScreen from './../screens/Dashboard/ProfileTabScreen'
import MaterialCommunityIcons from 'react-native-vector-icons/MaterialCommunityIcons'

const Tab = createBottomTabNavigator()

export default BottomTabs = props => {
  return (
    <Tab.Navigator>
      <Tab.Screen
      name="Dashboard"
      component={DashboardTabScreen}
      options={{
        tabBarLabel: 'Dashboard',
        tabBarIcon: ({ color, size }) => (
          <MaterialCommunityIcons name="view-dashboard" color={color} size={size} />
        )
      }}
      />
      <Tab.Screen
      name="Enrollments"
      component={EnrollmentsTabScreen}
      options={{
        tabBarLabel: 'Enrollments',
        tabBarIcon: ({ color, size }) => (
          <MaterialCommunityIcons name="format-list-checkbox" color={color} size={size} />
        ),
      }}
      />
      <Tab.Screen
      name="Profile"
      component={ProfileTabScreen}
      options={{
        tabBarLabel: 'Profile',
        tabBarIcon: ({ color, size }) => (
          <MaterialCommunityIcons name="account" color={color} size={size} />
        ),
      }}
      />
    </Tab.Navigator>
  )
}