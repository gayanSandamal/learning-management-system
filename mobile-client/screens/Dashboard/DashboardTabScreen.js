import * as React from 'react'
import { ApplicationProvider, Layout, Text, Button } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'

import { alertMsg, logout } from './../../common/script'

export default DashboardTabScreen = props => {
  const clearLogin = () => {
    logout(() => {
      alertMsg('Successfully logout')
      props.navigation.navigate('Login')
    }, () => {
    })
  }
  return (
    <ApplicationProvider {...eva} theme={eva.dark}>
      <Layout style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <Text category='h1'>Dashboard Tab</Text>
        <Button
          status='danger'
          onPress={() => { clearLogin() }}
          disabled={false}
        >
          CLEAR SECURE STORAGE
        </Button>
      </Layout>
    </ApplicationProvider>
  )
}
