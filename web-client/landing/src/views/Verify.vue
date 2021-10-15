<template>
  <div class="px-3 px-lg-3">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-sm-6 col-md-7 col-lg-5">
        <div class="card bg-color-2 card-curved px-3 shadow">
          <div class="card-body">
            <h5 class="card-title text-center text-white my-5" v-text="verifyState"></h5>
            <!-- <div class="form">
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="email" class="form-label text-white">Registered email</label>
                  <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
                    <input id="email" placeholder="Enter your registered email" type="email" class="form-control form-field-text-md" v-model="form.email" autofocus>
                  </div>
                  <label v-if="isEmail(form.email)" class="validation-msg" for="email"><small v-text="isEmail(form.email)"></small></label>
                </div>
                <div class="col-12 mt-1">
                  <div class="d-flex justify-content-end">
                    <Button class="col-12 col-md-auto" :label="isSending ? 'Sending...' : 'Email reset password link'" :labelColor="'color-5'" @click="send" :disabled="isSending"/>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import regex from '@/mixins/regex'
import { verifyEmail } from '@/api/auth'
export default {
  name: 'send',
  mixins: [regex],
  data() {
    return {
      form: {
        email: null
      },
      isSending: false,
      isVerified: 0
    }
  },
  components: {
    // Button: () => import('@/components/common/Button')
  },
  computed: {
    verifyState() {
      let msg = ''
      if (this.isVerified === 0) {
        msg = 'Verifying your account. Please wait...'
      } else if (this.isVerified === 1) {
        msg = 'Verification success'
      } else if (this.isVerified === 2) {
        msg = 'Verification failed'
      }
      return msg
    }
  },
  methods: {
    send() {
      this.isSending = true
      this.isVerified = 0
      const obj = {
        email: this.$route.query.email,
        token: this.$route.query.token,
      }
      verifyEmail(obj, response => {
        if (response.status === 200) {
          this.$toastr.s('', response.data.message)
          this.isSending = false
          this.isVerified = 1
          this.$router.push({path: '/'})
        } else {
          this.isVerified = 2
          this.$toastr.e('', 'Incomplete request')
        }
      }, error => {
        this.isVerified = 2
        this.$toastr.e('', error.response.data.message)
        this.isSending = false
        console.error(error)
      })
    }
  },
  mounted () {
    this.send()
  }
}
</script>