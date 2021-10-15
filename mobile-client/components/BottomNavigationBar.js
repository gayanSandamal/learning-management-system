import * as React from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { TouchableOpacity } from 'react-native-gesture-handler';

export default function BottomNavigationBar({ icon1, icon2, icon3, icon4, onPress1, onPress2, onPress3, onPress4, color1, color2, color3, color4, text1, text2, text3, text4, textColor }) {
    return (
        <View style={styles.tabBarInfoContainer}>
          <View style={styles.iconContainer}>
              <TouchableOpacity style={styles.touchContainer} onPress={onPress1}>
                  <Ionicons name={icon1} size={30} color={color1} />
                  <Text style={{color:textColor}}>{text1}</Text>
              </TouchableOpacity>
            </View>
            <View style={styles.iconContainer}>
              <TouchableOpacity style={styles.touchContainer} onPress={onPress2}>
                  <Ionicons name={icon2} size={30} color={color2} />
                  <Text style={{color:textColor}}>{text2}</Text>
              </TouchableOpacity>
            </View>
            <View style={styles.iconContainer}>
              <TouchableOpacity style={styles.touchContainer} onPress={onPress3}>
                  <Ionicons name={icon3} size={30} color={color3} />
                  <Text style={{color:textColor}}>{text3}</Text>
              </TouchableOpacity>
            </View>
            <View style={styles.iconContainer}>
              <TouchableOpacity style={styles.touchContainer} onPress={onPress4}>
                  <Ionicons name={icon4} size={30} color={color4} />
                  <Text style={{color:textColor}}>{text4}</Text>
              </TouchableOpacity>
            </View>
        </View>
    );
}


const styles = StyleSheet.create({
  tabBarInfoContainer: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    ...Platform.select({
      ios: {
        shadowColor: 'black',
        shadowOffset: { width: 0, height: -3 },
        shadowOpacity: 0.1,
        shadowRadius: 3,
      },
      android: {
        elevation: 20,
      },
    }),
    alignItems: 'center',
    backgroundColor: '#fbfbfb',
    height: 50,
    flexDirection: 'row',
    justifyContent: 'space-evenly'
  },
  touchContainer: {
      alignItems:'center',

  },
  iconContainer: {
    width: '25%'
  }
});