import * as React from 'react'
import { View } from 'react-native'
import { ApplicationProvider, Layout, Text } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'
import style from './../common/style'
import { Logo } from './../common/icons'

export default function MainLoadingScreen (props) {
    return (
        <ApplicationProvider {...eva} theme={eva.dark}>
            <Layout style={{ paddingHorizontal: 20, flex: 1, justifyContent: 'center', alignItems: 'stretch' }}>
                <View style={style.LogoImageContainer}>
                    <Logo width={128} height={128} />
                </View>
                <Text style={{marginVertical: 20, textAlign: 'center'}}>Akurata.lk</Text>
            </Layout>
        </ApplicationProvider>
    );
}
