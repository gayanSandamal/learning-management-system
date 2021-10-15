<template>
  <div class="px-3 px-lg-3">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-sm-6 col-md-7 col-lg-5">
        <div class="card bg-color-2 card-curved px-3 shadow">
          <div class="card-body">
            <h5 class="card-title text-center text-white mb-3">Reset email</h5>
            <div class="form">
              <div v-if="!isVerificationStep" class="row">
                <div class="col-12 mb-3">
                  <label for="email" class="form-label text-white">Registered email</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="email" ref="email" placeholder="Enter your registered email" type="email" class="form-control form-field-text-md" v-model="form.email" autofocus>
                  </div>
                  <label v-if="isEmail(form.email)" class="validation-msg" for="email"><small v-text="isEmail(form.email)"></small></label>
                </div>
                <p v-if="isSent" class="text-white">
                  Password reset email has sent. You will receive within few minutes
                </p>
                <div class="col-12 mt-1">
                  <div class="d-flex justify-content-end">
                    <Button class="col-12 col-md-auto" :label="!isSent ? isSending ? 'Sending...' : 'Email reset password link' : 'Email has sent'" :labelColor="'color-5'" @click="send" :disabled="!isEmailEntered || isSent || isSending"/>
                  </div>
                </div>
              </div>
              <div v-else class="row">
                <div class="col-12 mb-3">
                  <label for="email" class="form-label text-white">New password</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="password" ref="password" placeholder="Enter your new password" type="password" class="form-control form-field-text-md" v-model="form.newPassword" autofocus>
                  </div>
                  <label v-if="strongPassword(form.newPassword)" class="validation-msg" for="password"><small v-text="strongPassword(form.newPassword)"></small></label>
                </div>
                <div class="col-12 mb-3">
                  <label for="email" class="form-label text-white">Confirm new password</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="repassword" ref="repassword" placeholder="Confirm your new password" type="password" class="form-control form-field-text-md" v-model="form.newRePassword" autofocus>
                  </div>
                  <label v-if="strongPassword(form.newRePassword)" class="validation-msg" for="repassword"><small v-text="strongPassword(form.newRePassword)"></small></label>
                  <label v-if="!strongPassword(form.newRePassword) && !isConfirmPWMatch" class="validation-msg" for="repassword"><small>Password and the confirm password are not matching</small></label>
                </div>
                <p v-if="isSent" class="text-white">
                  Password reset email has sent. You will receive within few minutes
                </p>
                <div class="col-12 mt-1">
                  <div class="d-flex justify-content-end">
                    <Button class="col-12 col-md-auto" :label="!isSent ? isSending ? 'Updating...' : 'Reset password' : 'Updated'" :labelColor="'color-5'" @click="reset" :disabled="!isPasswordsEntered || !isConfirmPWMatch || isSent || isSending"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import regex from '@/mixins/regex'
import { sendResetEmail } from '@/api/auth'
export default {
  name: 'send',
  mixins: [regex],
  data() {
    return {
      form: {
        email: null
      },
      isSending: false,
      isSent: false
    }
  },
  components: {
    Button: () => import('@/components/common/Button')
  },
  computed: {
    email () {
      return this.$route.query.email
    },
    token () {
      return this.$route.query.token
    },
    isVerificationStep () {
      return (this.email && this.token) || false
    },
    isConfirmPWMatch() {
      return (!this.form.newPassword || !this.form.newRePassword) || (this.form.newPassword === this.form.newRePassword) || false
    },
    isPasswordsEntered () {
      return ((this.form.newPassword && this.form.newPassword.trim() !== '') && (this.form.newRePassword && this.form.newRePassword.trim() !== '')) || false
    },
    isEmailEntered () {
      return (this.form.email && this.form.email.trim() !== '') || false
    }
  },
  methods: {
    send() {
      this.isSending = true
      sendResetEmail(this.form, response => {
        if (response.status === 200) {
          this.$toastr.s('', response.data.message)
          this.isSending = false
          this.isSent = true
        } else {
          this.$toastr.e('', 'Incomplete request')
        }
      }, error => {
        this.$toastr.e('', error.response.data.message)
        this.isSending = false
        console.error(error)
      })
    },
    reset() {
      this.isSending = true
      if (this.isVerificationStep) {
        this.$set(this.form, 'email', this.email)
        this.$set(this.form, 'token', this.token)
        this.$set(this.form, 'password', this.form.newPassword)
        this.$set(this.form, 'is_verification_step', true)
      }
      sendResetEmail(this.form, response => {
        if (response.status === 200) {
          this.$toastr.s('', response.data.message)
          this.isSending = false
          this.isSent = true
          this.form = {
            email: null
          }
          setTimeout(() => {
            this.$router.push({ path: '/login' })
          }, 2000)
        } else {
          this.$toastr.e('', 'Failed to reset')
        }
      }, error => {
        if (error.response.status === 401) {
          this.form = {
            email: null
          }
          this.$router.push({ path: '/reset-password', query: undefined })
        }
        this.$toastr.e('', error.response.data.message)
        this.isSending = false
      })
    }
  },
  mounted () {
    this.$refs.email.focus()
  }
}
</script>