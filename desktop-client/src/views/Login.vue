<template>
  <div class="login">
    <section>
      <div class="container">
        <div class="row align-items-center justify-content-center fh-head">
          <div class="col-12 col-sm-6 col-md-5 col-lg-4">
            <div class="card bg-color-2 card-curved px-0 px-sm-3 shadow">
              <div class="card-body">
                <h5 class="card-title text-center mb-3">Login</h5>
                <form class="form" @keyup.enter="login">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="username" class="form-label">Account username</label>
                      <div class="input-group input-group-input overflow-hidden radius-1">
                        <input id="username" ref="username" placeholder="Enter your username" type="text" class="form-control form-field-text-md" v-model="form.username">
                      </div>
                      <label v-if="isUsername(form.username)" class="validation-msg" for="username"><small v-text="isUsername(form.username, 'Username can only contain alpha and numeric character. ex: gayan123')"></small></label>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="password" class="form-label">Account password</label>
                      <div class="input-group input-group-input overflow-hidden radius-1">
                        <input id="password" ref="password" type="password" class="form-control form-field-text-md" placeholder="Enter password here" v-model="form.password">
                      </div>
                      <label v-if="strongPassword(form.password)" class="validation-msg" for="password"><small v-text="strongPassword(form.password)"></small></label>
                    </div>
                    <!-- <div class="col-12 mb-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" v-model="form.rememberMe">
                        <label class="form-check-label" for="rememberMe">Keep me logged in</label>
                      </div>
                    </div> -->
                    <!-- <div class="col-12 mb-3">
                      <div class="d-flex flex-wrap justify-content-between">
                        <router-link class="mb-3" to="/">I don't have an account</router-link>
                        <router-link class="" to="/reset-password">Forgot password</router-link>
                      </div>
                    </div> -->
                    <div class="mt-1">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <Button class="w-100 mb-3 mb-md-0 btn-outline-light" :label="'Exit'" :labelColor="'color-1'" @click="exit"/>
                        </div>
                        <div class="col-12 col-md-6">
                          <Button class="w-100 btn-outline-success" :label="isLogin ? 'Authenticating...' : 'Login'" :labelColor="'color-5'" @click="login" :disabled="isLogin"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import regex from '@/mixins/regex'
import { login, getAccount } from '@/api/auth'
export default {
  name: 'Login',
  mixins: [regex],
  data () {
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
    Button: () => import('@/components/Button')
  },
  methods: {
    login() {
      this.isLogin = true
      if (!this.form.username) {
        this.$refs.username.focus()
      } else if (!this.form.password) {
        this.$refs.password.focus()
      }
      login(this.form, response => {
        if (response.status === 200) {
          this.$toastr.s('', response.data.message)
          this.isLogin = false
          const username = JSON.parse(JSON.stringify(this.form.username))
          this.$router.push({name: 'Dashboard'})
          this.getAccount(username)
        } else {
          this.$toastr.e('', 'Incomplete request')
        }
      }, error => {
        this.$toastr.e('', error.response.data.message)
        this.isLogin = false
        console.error(error)
      })
    },
    getAccount() {
      getAccount({}, response => {
        this.eventBus.$emit('account', response)
      })
    },
    exit() {
      this.eventBus.$emit('logout')
    }
  }
}
</script>
