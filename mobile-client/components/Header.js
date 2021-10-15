import React from 'react'
import { StyleSheet, View } from 'react-native'
import { Avatar, Icon, MenuItem, OverflowMenu, Text, TopNavigation, TopNavigationAction } from '@ui-kitten/components'
import { alertMsg, logout } from './../common/script'


const MenuIcon = (props) => (
  <Icon {...props} name='more-vertical'/>
);

const HamburgerMenuIcon = (props) => (
  <Icon {...props} name='menu-outline'/>
);

const InfoIcon = (props) => (
  <Icon {...props} name='info'/>
);

const LogoutIcon = (props) => (
  <Icon {...props} name='log-out'/>
);

export default Header = props => {
  debugger
  const user = props.user
  const [menuVisible, setMenuVisible] = React.useState(false)

  const toggleMenu = () => {
    setMenuVisible(!menuVisible)
  }

  
  const logoutNow = () => {
    logout(() => {
        alertMsg('Successfully logout')
        props.navigation.navigate('Login')
    }, () => {
        alertMsg('Error on logout')
    })
  }

  const renderMenuAction = () => (
    <TopNavigationAction icon={MenuIcon} onPress={toggleMenu}/>
  )
  
  const renderHamburgerMenuAction = () => (
    <TopNavigationAction icon={HamburgerMenuIcon} onPress={toggleMenu}/>
  )

  const renderOverflowMenuAction = () => (
    <React.Fragment>
      <OverflowMenu
        anchor={renderMenuAction}
        visible={menuVisible}
        onBackdropPress={toggleMenu}>
        <MenuItem accessoryLeft={InfoIcon} title='About'/>
        <MenuItem accessoryLeft={LogoutIcon} title='Logout' onPress={logoutNow}/>
      </OverflowMenu>
    </React.Fragment>
  );

  const renderTitle = (props) => (
    <View style={styles.titleContainer}>
      <Avatar
        style={styles.logo}
        source={require('./../assets/avatar.png')}
      />
      <Text {...props}>{`${user.firstname} ${user.lastname}`}</Text>
    </View>
  );

  return (
    <TopNavigation
      title={user ? renderTitle : ''}
      accessoryRight={renderOverflowMenuAction}
    />
  );
};

const styles = StyleSheet.create({
  titleContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  logo: {
    marginHorizontal: 16,
  },
});