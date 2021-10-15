import { StyleSheet } from 'react-native';

export default StyleSheet.create({
  // login screen
  container: {
    flex: 1,
    backgroundColor: 'white',
    alignItems: 'center',
    paddingVertical: 20,
    paddingHorizontal: 15
  },
  LogoImageContainer: {
    height: 128,
    alignSelf: 'center',
    resizeMode: 'contain'
  },
  inputContainer: {
    flex: 1,
    alignItems: 'center',
    flexDirection: 'column',
  },
  input: {
    marginBottom: 15
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
  textInput: {
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
  },
  // reset screen
  loginButton: {
    flex: 1
  },
  headerTextContainer: {
    marginTop: 50,
  },
  headerText: {
    color: 'black',
    fontSize: 40
  },
  // home screen
  flatListContainer: {
    flex: 1,
    backgroundColor: '#fff',
    marginBottom: 55
  },
  classDataCard: {
    flex: 1,
    height: 200,
    width: '100%',
    backgroundColor: 'red',
    borderColor: '#ffffff',
    borderRadius: 20,
    borderWidth: 3,
    alignItems: 'center',
    alignSelf: 'center',
    flexDirection: 'column',
    justifyContent: 'space-evenly'
  },
  promotionImage: {
    height: '100%',
    width: '70%',
    marginRight: 20
  },
  clientLogo: {
    height: 70,
    width: 70,
    resizeMode: 'contain',
    borderRadius: 35
  },
  searchBar: {
    marginLeft: 10,
    marginRight: 10,
    height: 20,
  },
  searchBarContainer: {
    marginLeft: 40,
    marginRight: 40,
    height: 20,
    borderRadius: 5,
    marginBottom: 2,
    backgroundColor: '#dedede'
  },

  // new
  // margin 10 begins
  m10: {
    margin: 10
  },
  mY10: {
    marginHorizontal: 10
  },
  mX10: {
    marginVertical: 10
  },
  mT10: {
    marginTop: 10
  },
  mR10: {
    marginRight: 10
  },
  mB10: {
    marginBottom: 10
  },
  mL10: {
    marginLeft: 10
  },
  // margin 10 ends
  // margin 15 begins
  m15: {
    margin: 15
  },
  mY15: {
    marginHorizontal: 15
  },
  mX15: {
    marginVertical: 15
  },
  mT15: {
    marginTop: 15
  },
  mR15: {
    marginRight: 15
  },
  mB15: {
    marginBottom: 15
  },
  mL15: {
    marginLeft: 15
  },
  // margin 15 ends
  topContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  card: {
    flex: 1,
    margin: 2,
  },
  footerContainer: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
  footerControl: {
    marginHorizontal: 2,
  },
  captionContainer: {
    display: 'flex',
    flexDirection: 'column'
  },
  captionIcon: {
    width: 10,
    height: 10,
    marginRight: 5
  },
  captionText: {
    fontSize: 12,
    fontWeight: "400",
    // fontFamily: "opensans-regular",
    color: "#8F9BB3",
  }
});
