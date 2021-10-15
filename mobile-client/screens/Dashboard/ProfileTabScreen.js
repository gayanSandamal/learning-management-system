import * as React from 'react'
import { ApplicationProvider, Layout, Text } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'

import { alertMsg, logout } from './../../common/script'

export default EnrollmentsTabScreen = props => {
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
        <Text category='h1'>Profile Tab</Text>
      </Layout>
    </ApplicationProvider>
  )
}
