import * as React from 'react';
import { View, TouchableWithoutFeedback, KeyboardAvoidingView, Text as RNText } from 'react-native'
import { useState, useRef } from 'react'
import { storeToken, storeUsername, deleteToken, deleteUsername } from './../APIExicuters/secureStore'
import AsyncStorage from '@react-native-async-storage/async-storage';

import { ApplicationProvider, Layout, IconRegistry, Button, Input, Text, Icon } from '@ui-kitten/components'
import { EvaIconsPack } from '@ui-kitten/eva-icons'
import * as eva from '@eva-design/eva'

import authAPI from '../APIExicuters/authAPI'
import { alertMsg, logout } from './../common/script'
import style from './../common/style'
import { Logo } from './../common/icons'

export default LoginScreen = props => {
    const [userName, setUserName] = useState('gayan95')
    const [userPassword, setUserPassword] = useState('1234')
    const [secureTextEntry, setSecureTextEntry] = useState(true)
    
    const [isLogin, setIsLogin] = useState(false)
    const [usernameStatus, setUsernameFieldStatus] = useState('')
    const [passwordStatus, setPasswordFieldStatus] = useState('')

    const usernameRef = useRef()
    const passwordRef = useRef()

    const login = () => {
        setIsLogin(true)
        if (!userName) {
            alertMsg('Please enter your username')
            setUsernameFieldStatus('danger')
            setPasswordFieldStatus('')
            usernameRef.current.focus()
            setIsLogin(false)
        } else if (!userPassword) {
            alertMsg('Please enter your password')
            setUsernameFieldStatus('')
            setPasswordFieldStatus('danger')
            passwordRef.current.focus()
            setIsLogin(false)
        } else {
            setUsernameFieldStatus('')
            setPasswordFieldStatus('')
            const obj = {
                username: userName,
                password: userPassword,
                rememberMe: false
            }
            authAPI.login(obj, loginResponse => {
                if (loginResponse != null) {
                    const usernamePromise = storeUsername(userName)
                    const tokenPromise = storeToken(loginResponse.token)
                    usernamePromise.then(() => {
                        tokenPromise.then(() => {
                            authAPI.getUser(userResponse => {
                                if (userResponse != null) {
                                    setUserName('')
                                    setUserPassword('')
                                    storeUserObject(userResponse.data[0])
                                    setIsLogin(false)
                                    props.navigation.navigate('DashboardTabs')
                                }
                            }, error => {
                                setIsLogin(false)
                                alertMsg(error.message)
                            })
                        })
                    })
                }
            }, error => {
                setIsLogin(false)
                alertMsg(error.message)
            })
        }
    }
    
    const storeUserObject = async (value) => {
        const json_value = JSON.stringify(value)
        try {
            await AsyncStorage.setItem('@UserObject', json_value)
        } catch (e) {
            console.log(e)
        }
    }

    const AlertIcon = (props) => (
        <Icon {...props} name='alert-circle-outline'/>
    );
    
    const renderIcon = (props) => (
        <TouchableWithoutFeedback onPress={toggleSecureEntry}>
          <Icon {...props} name={secureTextEntry ? 'eye-off' : 'eye'}/>
        </TouchableWithoutFeedback>
    );

    const renderCaption = () => {
        return (
          <View style={style.captionContainer, style.mB15}>
            {AlertIcon(style.captionIcon)}
            <Text style={style.captionText}>Should contain at least 4 characters</Text>
          </View>
        )
    }

    const toggleSecureEntry = () => {
      setSecureTextEntry(!secureTextEntry);
    }

    const clearLogin = () => {
        logout(() => {
        }, () => {
        })
    }

    return (
        <>
            <IconRegistry icons={EvaIconsPack} />
            <ApplicationProvider {...eva} theme={eva.dark}>
                <KeyboardAvoidingView style={{ flex: 1, justifyContent: 'center', alignItems: 'stretch' }}>
                    <Layout style={{ paddingHorizontal: 20, flex: 1, justifyContent: 'center', alignItems: 'stretch' }}>
                        <View style={style.LogoImageContainer}>
                            <Logo width={128} height={128} />
                        </View>
                        <View style={{marginTop: 15}}>
                            <View>
                                <Input
                                    ref={usernameRef}
                                    style={style.input}
                                    status={usernameStatus}
                                    label='Username'
                                    placeholder='Enter your username'
                                    value={userName}
                                    onChangeText={text => setUserName(text)}
                                    onSubmitEditing={() => setFocus()}
                                    disabled={isLogin}
                                />
                                <Input
                                ref={passwordRef}
                                    status={passwordStatus}
                                    value={userPassword}
                                    label='Password'
                                    placeholder='Enter your password'
                                    caption={renderCaption}
                                    accessoryRight={renderIcon}
                                    secureTextEntry={secureTextEntry}
                                    onChangeText={text => setUserPassword(text)}
                                    disabled={isLogin}
                                />
                            </View>

                            <View>
                                <Button
                                    status='success'
                                    onPress={() => { login() }}
                                    disabled={isLogin}
                                >
                                    {isLogin ? `AUTHENTICATING...` : 'LOGIN'}
                                </Button>
                                <Button
                                    status='danger'
                                    onPress={() => { clearLogin() }}
                                    disabled={isLogin}
                                >
                                    CLEAR SECURE STORAGE
                                </Button>
                                <RNText
                                    onPress={() => { props.navigation.navigate('ResetPassword') }}
                                    style={{ color: '#ffffff', textAlign: 'center', marginTop: 30, opacity: isLogin ? 0.5 : 1 }}
                                    disabled={isLogin}>
                                    Reset password
                                </RNText>
                            </View>
                        </View>
                    </Layout>
                </KeyboardAvoidingView>
            </ApplicationProvider>
        </>
    );
}
