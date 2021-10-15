import * as React from 'react';
import { StyleSheet, KeyboardAvoidingView, View, TextInput, Text as RNText } from 'react-native';
import { useState, useRef } from 'react';
import authAPI from '../APIExicuters/authAPI';
import style from './../common/style'
import { alertMsg } from './../common/script'
import { ApplicationProvider, Layout, IconRegistry, Button, Input, Text, Icon } from '@ui-kitten/components'
import * as eva from '@eva-design/eva'

export default PasswordResetScreen = props => {
    const [userEmail, setUserEmail] = useState('')
    const [isSending, setisSending] = useState(false)
    const [emailStatus, setEmailFieldStatus] = useState('')
    const emailRef = useRef()

    const resetPassword = () => {
        if (!userEmail) {
            alertMsg('Please enter your email')
            setEmailFieldStatus('danger')
            emailRef.current.focus()
        } else {
            setEmailFieldStatus('')
            setisSending(true)
            authAPI.passwordReset(userEmail, response => {
                if (response != null) {
                    setisSending(false)
                    props.navigation.navigate('Login')
                }
            }, error => {
                setisSending(false)
                alertMsg(error.message)
            })
        }
    }

    return (
        <>
            <ApplicationProvider {...eva} theme={eva.dark}>
                <KeyboardAvoidingView style={{ flex: 1, justifyContent: 'center', alignItems: 'stretch' }}>
                    <Layout style={{ paddingHorizontal: 20, flex: 1, justifyContent: 'center', alignItems: 'stretch' }}>
                        <Text style={{marginVertical: 20, textAlign: 'center'}}>Password reset</Text>
                        <View>
                            <View>
                                <Input
                                    autoFocus={true}
                                    ref={emailRef}
                                    style={style.input}
                                    status={emailStatus}
                                    label='Email'
                                    placeholder='Enter your email'
                                    value={userEmail}
                                    onChangeText={text => setUserEmail(text)}
                                    disabled={isSending}
                                />
                            </View>

                            <View>
                                <Button
                                    status='success'
                                    onPress={() => { resetPassword() }}
                                    disabled={isSending}
                                >
                                    {isSending ? `SENDING...` : 'SEND RESET LINK'}
                                </Button>
                                <RNText
                                    onPress={() => { props.navigation.navigate('Login') }}
                                    style={{ color: '#ffffff', textAlign: 'center', marginTop: 30, opacity: isSending ? 0.5 : 1 }}
                                    disabled={isSending}>
                                    I remember password, Login
                                </RNText>
                            </View>
                        </View>
                    </Layout>
                </KeyboardAvoidingView>
            </ApplicationProvider>
        </>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'white',
        alignItems: 'center'
    },
    LogoImageContainer: {
        height: 100,
        width: 100,
        alignSelf: 'center',
        resizeMode: 'contain'
    },
    formContainer: {
        flex: 1,
        alignItems: 'center',
        flexDirection: 'column',
    },
    userInput: {
        width: 300,
        height: 30,
        borderBottomColor: '#9e9e9e',
        borderBottomWidth: 2,
        //margin:30
        //backgroundColor:'#dedede',
        //borderRadius:5
    },
    TextInput: {
        alignItems: 'center',
        justifyContent: 'space-evenly',
        flex: 2
    },
    loginButton: {
        flex: 1
    },
    headerTextContainer: {
        marginTop: 50,
    },
    headerText: {
        color: 'black',
        fontSize: 40
    }
});
