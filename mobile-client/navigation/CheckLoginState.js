import * as React from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';


export default {
    async checkLogin () {
        try {
            const value = await AsyncStorage.getItem('@token')
            if(value !== (null || undefined || '')) {
                console.log('@token', value)
                return true
            } else {
                console.log('@token', value)
                return false
            }
        } catch(e) {
            console.log(e)
            return false
        }
    }
}