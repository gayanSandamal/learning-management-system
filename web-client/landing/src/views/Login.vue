<template>
  <div class="px-3 px-lg-3">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-sm-6 col-md-7 col-lg-5 col-xxl-3">
        <div class="card bg-color-2 card-curved px-0 px-sm-3 shadow">
          <div class="card-body">
            <h5 class="card-title text-center text-white mb-3">Login</h5>
            <form class="form">
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="username" class="form-label text-white">Account username</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="username" ref="username" placeholder="Enter your username" type="text" class="form-control form-field-text-md" v-model="form.username">
                  </div>
                  <label v-if="isUsername(form.username)" class="validation-msg" for="username"><small v-text="isUsername(form.username, 'Username can only contain alpha and numeric character. ex: gayan123')"></small></label>
                </div>
                <div class="col-12 mb-3">
                  <label for="password" class="form-label text-white">Account password</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="password" ref="password" type="password" class="form-control form-field-text-md" placeholder="Enter password here" v-model="form.password">
                  </div>
                  <label v-if="strongPassword(form.password)" class="validation-msg" for="password"><small v-text="strongPassword(form.password)"></small></label>
                </div>
                <div class="col-12 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" v-model="form.rememberMe">
                    <label class="form-check-label text-white" for="rememberMe">Keep me logged in</label>
                  </div>
                </div>
                <div class="col-12 mb-3">
                  <div class="d-flex flex-wrap justify-content-between">
                    <router-link class="text-white mb-3" to="/">I don't have an account</router-link>
                    <router-link class="text-white" to="/reset-password">Forgot password</router-link>
                  </div>
                </div>
                <div class="col-12 mt-1">
                  <div class="d-flex justify-content-end">
                    <Button class="col-12 col-md-auto" :label="isLogin ? 'Authenticating...' : 'Login'" :labelColor="'color-5'" @click="login($event)" :disabled="isLogin"/>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import regex from '@/mixins/regex'
import { login, getAccount } from '@/api/auth'
export default {
  name: 'login',
  mixins: [regex],
  data() {
    return {
      form: {
        username: null,
        password: null,
        rememberMe: false
      },
      isLogin: false
    }
  },
  props: {
    eventBus: undefined
  },
  components: {
    Button: () => import('@/components/common/Button')
  },
  computed: {
    toUrl() {
      return this.$route.query.to
    }
  },
  methods: {
    login(event) {
      if (event) {
        event.preventDefault()
      }
      if (this.form.username === null || (this.form.username.trim() === '')) {
        this.$toastr.e('', 'Please enter username')
      } else if (this.form.password === null || (this.form.password.trim() === '')) {
        this.$toastr.e('', 'Please enter password')
        this.$refs.password.focus()
      } else {
        this.isLogin = true
        login(this.form, response => {
          if (response.status === 200) {
            this.$toastr.s('', response.data.message)
            this.isLogin = false
            const username = JSON.parse(JSON.stringify(this.form.username))
            this.getAccount(username)
            if (this.toUrl) {
              this.$router.push({path: this.toUrl})
            } else {
              this.$router.push({path: '/'})
            }
          } else {
            this.$toastr.e('', 'Incomplete request')
          }
        }, error => {
          this.$toastr.e('', error.response.data.message)
          this.isLogin = false
        })
      }
    },
    getAccount() {
      getAccount({}, response => {
        this.eventBus.$emit('account', response)
      })
    }
  },
  mounted () {
    this.$refs.username.focus()
  }
}
</script>