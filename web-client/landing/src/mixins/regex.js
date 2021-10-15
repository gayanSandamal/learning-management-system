export default {
  methods: {
    isName(str) { 
      return str ? !str.match(/^[A-Za-z]+$/g) ? 'Invalid name format' : undefined : undefined
    },
    isUsername(str, msg) { 
      return str ? !str.match(/^[a-zA-Z0-9]+$/g) ? msg ? msg : 'Invalid username format' : undefined : undefined
    },
    isNameWithSpaces(str, msg) { 
      return str ? !str.match(/^[A-Za-z\s ]+$/g) ? msg ? msg : 'Invalid name format' : undefined : undefined
    },
    isEmail(str) { 
      return str ? !str.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g) ? 'Invalid email format' : undefined : undefined
    },
    isPhone(str) { 
      return str ? !str.match(/^\d{9}$/g) ? 'Invalid email format. Remove leading zeros if exists. Ex: 771234567' : undefined : undefined
    },
    isAddress(str) { 
      return str ? !str.match(/^[a-zA-Z0-9\s,'-/]*$/g) ? 'Invalid address format' : undefined : undefined
    },
    strongPassword(str) { 
      // return str ? !str.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z\s*!@#$%^&()]{8,}$/g) ? 'Password should contain at least one digit, one lower case, one upper case and minimum length is 8. Ex: acRo9@32' : undefined : undefined
      return str ? !str.match(/^.{4,}$/g) ? 'Password should contain at least 4 characters' : undefined : undefined
    }
  }
}