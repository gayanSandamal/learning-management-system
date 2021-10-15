import * as React from 'react';
import { StyleSheet,TouchableOpacity, Text } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function CommonButton({icon, iconColor, buttonText, onPress, width, height, buttonColor, borderColor, iconSize, fontSize, fontWeight}) {

    return (

        <TouchableOpacity style={{width:width, backgroundColor:buttonColor, height:height, flexDirection: 'row',justifyContent: 'space-evenly',alignItems:'center',borderWidth:1, borderColor:borderColor,borderRadius: 5}} onPress={onPress}>
            <Ionicons name={icon} size={iconSize} color={iconColor} />
            <Text style={{fontSize:fontSize, fontWeight:fontWeight}}>{buttonText}</Text>
        </TouchableOpacity>

    );
}