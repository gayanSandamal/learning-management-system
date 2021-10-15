import React from 'react'
import { StyleSheet, View } from 'react-native'
import { NavigationContainer, CommonActions } from '@react-navigation/native'
import { ApplicationProvider, BottomNavigation, BottomNavigationTab, Icon } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'

import DashboardTabScreen from './../screens/Dashboard/DashboardTabScreen'
import EnrollmentsTabScreen from './../screens/Dashboard/EnrollmentsTabScreen'

const Stack = createStackNavigator()

const DashboardIcon = (props) => (
  <Icon {...props} name='bar-chart-outline'/>
)

const EnrollmentIcon = (props) => (
  <Icon {...props} name='checkmark-square-outline'/>
)

const EmailIcon = (props) => (
  <Icon {...props} name='email-outline'/>
)

const useBottomNavigationState = (initialState = 0) => {
  const [selectedIndex, setSelectedIndex] = React.useState(initialState)
  return { selectedIndex, onSelect: setSelectedIndex }
}

export default DashboadTabsContainer = props => {
  const topState = useBottomNavigationState()
  const bottomState = useBottomNavigationState()

  return (
    <NavigationContainer>
      <Stack.Navigator>
        <Stack.Screen options={{headerShown: false}} name="DashboardTab" component={DashboardTabScreen} />
        <Stack.Screen options={{headerShown: false}} name="EnrollmentsTab" component={EnrollmentsTabScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  )
}

const styles = StyleSheet.create({
  tabBarInfoContainer: {
    position: 'absolute',
    top: 200,
    left: 0,
    right: 0
  }
})