import * as React from 'react';
import { StyleSheet, View } from 'react-native';
import { WebView } from 'react-native-webview';

export default PreviewScreen = props => {

  return (        
    <WebView source={{ uri: 'https://www.youtube.com/' }} />       
  );
  
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'white',
        alignItems: 'center'
    }
});
