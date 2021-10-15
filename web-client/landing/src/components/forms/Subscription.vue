<template>
  <div class="card bg-color-2 card-curved px-0 px-sm-3 shadow">
    <div class="card-body">
      <h5 class="card-title text-center text-white mb-3">Register Now at Akurata.lk!</h5>
      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
      <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
      <div class="form-fill-indicator w-100 position-relative mb-4">
        <div class="d-flex w-100">
          <div class="man-walk-container">
            <div v-if="completionProgress < 100" class="man-walk" :style="{left: walkman, width: walkmanWidth}" v-html="walkIcon"></div>
            <div v-else class="man-walk" :style="{left: walkman}" v-html="wonIcon"></div>
          </div>
          <div class="flag position-absolute" :class="{'won' : completionProgress === 100}" v-html="flagIcon"></div>
        </div>
        <div class="ground bg-color-5"></div>
      </div>
      <div v-if="!isRegistered" class="form">
        <div class="row" v-if="1 === page">
          <div class="col-12 mb-3">
            <label for="firstname" class="form-label text-white">Student's first name<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="firstname" placeholder="Gayan" type="text" class="form-control form-field-text-md" v-model="form.firstname">
            </div>
            <label v-if="isName(form.firstname)" class="validation-msg" for="firstname"><small v-text="isName(form.firstname)"></small></label>
          </div>
          <div class="col-12 mb-3">
            <label for="lastname" class="form-label text-white">Student's last name<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="lastname" placeholder="Sandamal" type="text" class="form-control form-field-text-md" v-model="form.lastname">
            </div>
            <label v-if="isName(form.lastname)" class="validation-msg" for="lastname"><small v-text="isName(form.lastname)"></small></label>
          </div>
          <div class="col-12 mb-3">
            <label for="email" class="form-label text-white">Student's email<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="email" placeholder="gayan.sandamal@gmail.com" type="text" class="form-control form-field-text-md" v-model="form.email">
            </div>
            <label v-if="isEmail(form.email)" class="validation-msg" for="email"><small v-text="isEmail(form.email)"></small></label>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex justify-content-end">
              <Button :label="'Next'" :labelColor="'color-5'" @click="page = 2" :disabled="!page1Validated"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="2 === page">
          <div class="col-12 mb-3">
            <label for="phone" class="form-label text-white">Student's phone number<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <span class="input-group-text text-md">+94</span>
              <input id="phone" type="text" class="form-control form-field-text-md" v-model="form.studentPhone" :disabled="isStudentPhoneDisabled" placeholder="771234567">
            </div>
            <label v-if="isPhone(form.studentPhone)" class="validation-msg" for="phone"><small v-text="isPhone(form.studentPhone)"></small></label>
          </div>
          <div class="col-12 mb-3" v-if="studentPhoneVerify.realOtp !== null">
            <label for="student-verifiy" class="form-label text-white">Enter verification code<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="student-verifiy" type="text" class="form-control form-field-text-md" v-model="studentPhoneVerify.verifyOtp" :disabled="studentPhoneVerify.verified" @input="verifyStudentOTP">
            </div>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 1" :disabled="studentPhoneVerify.isVerificationCodeSent && !studentPhoneVerify.realOtp"/>

              <Button v-if="!studentPhoneVerify.realOtp" :label="'Send verification code'" :labelColor="'color-5'" @click="sendStudentVerification" :disabled="(!form.studentPhone || isPhone(form.studentPhone) !== undefined) || studentPhoneVerify.isVerificationCodeSent"/>

              <Button v-if="studentPhoneVerify.isVerificationCodeSent && studentPhoneVerify.realOtp && !studentPhoneVerify.verified" :label="`(${studentPhoneVerify.timer} seconds) remaining to verify`" :labelColor="'color-5'" @click="verifyStudentPhone"/>

              <Button v-if="studentPhoneVerify.isVerificationCodeSent && studentPhoneVerify.realOtp && studentPhoneVerify.verified" :label="'Verified, Next'" :labelColor="'color-5'" @click="page = 3"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="3 === page">
          <div class="col-12 mb-3">
            <label for="parent-phone" class="form-label text-white">Parent's phone number<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1" :class="{'disabled': form.sameAsStudent}">
              <span class="input-group-text text-md">+94</span>
              <input id="parent-phone" type="text" class="form-control form-field-text-md" :disabled="isParentPhoneDisabled" v-model="form.parentPhone">
            </div>
            <label v-if="isPhone(form.parentPhone)" class="validation-msg" for="parent-phone"><small v-text="isPhone(form.parentPhone)"></small></label>
          </div>
          <div class="col-12 mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="parentnumber" role="button" v-model="form.sameAsStudent" @change="useStudentPhone(form.sameAsStudent)">
              <label class="form-check-label text-white" for="parentnumber" role="button">Same as student’s phone number</label>
            </div>
          </div>
          <div class="col-12 mb-3" v-if="!form.sameAsStudent && parentPhoneVerify.realOtp !== null">
            <label for="student-verifiy" class="form-label text-white">Enter verification code<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="student-verifiy" type="text" class="form-control form-field-text-md" v-model="parentPhoneVerify.verifyOtp" :disabled="parentPhoneVerify.verified" @input="verifyParentOTP">
            </div>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 2" :disabled="parentPhoneVerify.isVerificationCodeSent && !parentPhoneVerify.realOtp"/>

              <Button v-if="!parentPhoneVerify.realOtp && !form.sameAsStudent" :label="'Send verification code'" :labelColor="'color-5'" @click="sendParentVerification" :disabled="(!form.parentPhone || isPhone(form.parentPhone) !== undefined) || parentPhoneVerify.isVerificationCodeSent"/>

              <Button v-if="parentPhoneVerify.isVerificationCodeSent && parentPhoneVerify.realOtp && !parentPhoneVerify.verified && !form.sameAsStudent" :label="`(${parentPhoneVerify.timer} seconds) remaining to verify`" :labelColor="'color-5'" @click="verifyParentPhone"/>

              <Button v-if="parentPhoneVerify.isVerificationCodeSent && parentPhoneVerify.realOtp && parentPhoneVerify.verified && !form.sameAsStudent" :label="'Verified, Next'" :labelColor="'color-5'" @click="page = 4"/>

              <Button v-if="form.sameAsStudent" :label="'Next'" :labelColor="'color-5'" @click="page = 4"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="4 === page">
          <div class="col-12 mb-3">
            <label for="phone" class="form-label text-white">Student's gender<span class="required-text"> (required)</span></label>
            <div class="btn-group d-flex w-100" role="group" aria-label="Basic outlined example">
              <button type="button" class="btn-group-choice-lg rounded-pill d-flex justify-content-center align-items-center w-100" :class="{'bg-color-4': form.gender === 1}" @click="form.gender = 1">
                <span class="color-4" :class="{'text-white': form.gender === 1}">Male</span>
                <div v-if="form.gender === 1" class="gender-icon ms-2" v-html="maleIconWhite"></div>
                <div v-else class="gender-icon ms-2" v-html="maleIcon"></div>
              </button>
              <button type="button" class="btn-group-choice-lg rounded-pill d-flex justify-content-center align-items-center w-100" :class="{'bg-color-8': form.gender === 2}" @click="form.gender = 2">
                <span class="color-8" :class="{'text-white': form.gender === 2}">Female</span>
                <div v-if="form.gender === 2" class="gender-icon ms-2" v-html="femaleIconWhite"></div>
                <div v-else class="gender-icon ms-2" v-html="femaleIcon"></div>
              </button>
            </div>
          </div>
          <div class="col-12 mb-3">
            <label for="phone" class="form-label text-white">Student's date of birth<span class="required-text"> (required)</span></label>
            <datepicker v-model="form.dob" class="datepicker-component" format="yyyy-MM-dd" placeholder="YYYY-MM-DD"></datepicker>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 3"/>
              <Button :label="'Next'" :labelColor="'color-5'" @click="page = 5" :disabled="!form.dob"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="5 === page">
          <div class="col-12 mb-3">
            <label for="address" class="form-label text-white">Student's address<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-textarea input-group-lg overflow-hidden radius-1">
              <textarea class="form-control form-field-text-md" rows="3" placeholder="No 1/2/3, Colombo Sri Lanka" id="address" v-model="form.address"></textarea>
            </div>
            <!-- <label v-if="isAddress(form.address)" class="validation-msg" for="address"><small v-text="isAddress(form.address)"></small></label> -->
          </div>
          <div class="col-12 mb-3">
            <label for="state" class="form-label text-white">Province<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.state" @change="getDistricts(form.state)">
                <option v-for="state in states" :key="state.id" :value="state.id" v-text="state.label"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mb-3" v-if="districts.length">
            <label for="state" class="form-label text-white">District<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.district" @change="getCities(form.district)">
                <option v-for="district in districts" :key="district.id" :value="district.id" v-text="district.label"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mb-3" v-if="cities.length">
            <label for="state" class="form-label text-white">City<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.city">
                <option v-for="city in cities" :key="city.id" :value="city.id" v-text="city.label"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 4"/>
              <Button :label="'Next'" :labelColor="'color-5'" @click="page = 6" :disabled="!page5Validated"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="6 === page">
          <!-- <div class="col-12 mb-3">
            <label for="school" class="form-label text-white">Student's school name<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="school" type="text" class="form-control form-field-text-md" v-model="form.school">
            </div>
            <label v-if="isNameWithSpaces(form.school)" class="validation-msg" for="school"><small v-text="isNameWithSpaces(form.school, 'Invalid school name format. Cannot contain numbers or special characters')"></small></label>
          </div> -->
          <div class="col-12 mb-3">
            <label for="state" class="form-label text-white">What are you looking for<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.cat_id" @change="getChildCat(form.cat_id)">
                <option v-for="cat in cats" :key="cat.id" :value="cat.id" v-text="cat.name"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mb-3" v-if="child_cats.length > 0">
            <label for="state" class="form-label text-white">Select class category<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.child_cat_id" @change="getChildCat2(form.child_cat_id)">
                <option v-for="cat in child_cats" :key="cat.id" :value="cat.id" v-text="cat.name"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mb-3" v-if="child_cats2.length > 0">
            <label for="state" class="form-label text-white">Select class<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-select input-group-lg overflow-hidden radius-1">
              <select class="form-select" id="state" v-model="form.child_cat_id2">
                <option v-for="cat in child_cats2" :key="cat.id" :value="cat.id" v-text="cat.name"></option>
              </select>
            </div>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 5"/>
              <Button :label="'Next'" :labelColor="'color-5'" @click="page = 7" :disabled="!page6Validated"/>
            </div>
          </div>
        </div>
        <div class="row" v-else-if="7 === page">
          <div class="col-12 mb-3">
            <label for="username" class="form-label text-white">Account username<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="username" type="text" class="form-control form-field-text-md" placeholder="Username cannot contain any special characters" v-model="form.username" :disabled="isRegistering || isRegistered">
            </div>
            <label v-if="isUsername(form.username)" class="validation-msg" for="username"><small v-text="isUsername(form.username, 'Username can only contain alpha and numeric character. ex: gayan123')"></small></label>
          </div>
          <div class="col-12 mb-3">
            <label for="password" class="form-label text-white">Account password<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="password" type="password" class="form-control form-field-text-md" placeholder="Enter password here" v-model="form.password" :disabled="isRegistering || isRegistered">
            </div>
            <label v-if="strongPassword(form.password)" class="validation-msg" for="password"><small v-text="strongPassword(form.password)"></small></label>
          </div>
          <div class="col-12 mb-3">
            <label for="repassword" class="form-label text-white">Confirm password<span class="required-text"> (required)</span></label>
            <div class="input-group input-group-input input-group-lg overflow-hidden radius-1">
              <input id="repassword" type="password" class="form-control form-field-text-md" placeholder="Re-enter the password to confirm" v-model="form.repassword" :disabled="isRegistering || isRegistered">
            </div>
            <label v-if="!isConfirmPWMatch" class="validation-msg" for="password"><small>Confirmation password is not matching with the above password</small></label>
          </div>
          <div class="col-12 mt-1">
            <div class="d-flex flex-wrap justify-content-between">
              <Button class="mb-3 mb-lg-0" :label="'Back'" @click="page = 6" :disabled="isRegistering || isRegistered"/>
              <Button :label="registerBtnLabel" :labelColor="'color-5'" @click="register" :disabled="!page7Validated || (isRegistering || isRegistered)"/>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="d-block">
        <h1 class="display-6 w-100 text-center text-white">Successfully registered</h1>
        <p class="w-100 text-center text-white">More details - 0707 76 76 76</p>
        <p class="w-100 text-center text-white bg-danger p-1 rounded">Email verification has sent to your email. Please verify</p>
      </div>
    </div>
  </div>
</template>

<script>
import { walk, won, flag, male, maleWhite, female, femaleWhite } from '@/assets/scripts/svg'
import Datepicker from 'vuejs-datepicker'
import { register, getStates, getDistricts, getCities, sendVerification } from '@/api/auth'
import { getCatsByPostType } from '@/api/postType'
import regex from '@/mixins/regex'
export default {
  name: 'subscription',
  mixins: [regex],
  data () {
    return {
      states: [],
      districts: [],
      cities: [],
      cats: [],
      child_cats: [],
      child_cats2: [],
      studentPhoneVerify: {
        isVerificationCodeSent: false,
        realOtp: null,
        verifyOtp: null,
        timer: 120,
        verified: false
      },
      parentPhoneVerify: {
        isVerificationCodeSent: false,
        realOtp: null,
        verifyOtp: null,
        timer: 120,
        verified: false
      },
      page: 1,
      form: {
        firstname: null,
        lastname: null,
        email: null,
        studentPhone: null,
        sameAsStudent: false,
        parentPhone: null,
        gender: 1,
        dob: new Date('2010-01-01T00:00:00'),
        address: null,
        state: null,
        district: null,
        city: null,
        post_type_id: 1,
        // school: null,
        // grade: null,
        username: null,
        password: null,
        repassword: null,
        cat_id: null,
        child_cat_id: null,
        child_cat_id2: null,
      },
      isRegistering: false,
      isRegistered: false
    }
  },
  props: {
    eventBus: undefined
  },
  components: {
    Button: () => import('@/components/common/Button'),
    Datepicker
  },
  computed: {
    walkIcon() {
      return walk
    },
    wonIcon() {
      return won
    },
    flagIcon() {
      return flag
    },
    maleIcon() {
      return male
    },
    maleIconWhite() {
      return maleWhite
    },
    femaleIcon() {
      return female
    },
    femaleIconWhite() {
      return femaleWhite
    },
    isStudentPhoneDisabled() {
      let state = false
      if (this.studentPhoneVerify.isVerificationCodeSent) {
        state = true
      }
      if (this.studentPhoneVerify.verified) {
        state = true
      }
      return state
    },
    isParentPhoneDisabled() {
      let state = false
      if (this.form.sameAsStudent) {
        state = true
      } else {
        if (this.parentPhoneVerify.isVerificationCodeSent) {
          state = true
        }
        if (this.parentPhoneVerify.verified) {
          state = true
        }
      }
      return state
    },
    page1Validated() {
      let state = true
      if (!this.form.firstname || !this.form.lastname || !this.form.email) {
        state = false
      } else if (this.isName(this.form.firstname) !== undefined || this.isName(this.form.lastname) !== undefined || this.isEmail(this.form.email) !== undefined) {
        state = false
      }
      return state
    },
    page5Validated() {
      let state = true
      if (!this.form.address || !this.form.state || !this.form.district || !this.form.city) {
        state = false
      }
      // else if (this.isAddress(this.form.address) !== undefined) {
      //   state = false
      // }
      return state
    },
    page6Validated() {
      let state = true
      if (this.cats.length > 0 && !this.form.cat_id) {
        state = false
      } else if (this.child_cats.length > 0 && !this.form.child_cat_id) {
        state = false
      } else if (this.child_cats2.length > 0 && !this.form.child_cat_id2) {
        state = false
      }
      return state
    },
    page7Validated() {
      let state = true
      if (!this.form.username || !this.form.password || !this.form.repassword) {
        state = false
      } else if (this.strongPassword(this.form.password) !== undefined) {
        state = false
      } else if (!this.isConfirmPWMatch) {
        state = false
      }
      return state
    },
    isConfirmPWMatch() {
      return (!this.form.password || !this.form.repassword) || (this.form.password === this.form.repassword) || false
    },
    completionProgress() {
      const total = 7
      let currentPage = this.page - 1
      let percentage = 0
      if (this.isRegistered) {
        percentage = 100
      } else {
        percentage = parseInt(100 / total * currentPage)
      }
      return percentage
    },
    walkman() {
      return `${this.completionProgress}%`
    },
    walkmanWidth() {
      return `calc(100% - ${this.completionProgress}%)`
    },
    registerBtnLabel() {
      let label = 'Register'
      if (this.isRegistering && !this.isRegistered) {
        label = 'Registering...'
      } else if (!this.isRegistering && this.isRegistered) {
        label = 'Registered'
      }
      return label
    }
  },
  methods: {
    register() {
      this.isRegistering = true
      this.isRegistered = false
      register(this.form, response => {
        if (response.data.status === 'success') {
          this.isRegistered = true
          this.resetFields(false)
          this.$toastr.s('', response.data.message)
        }
      }, error => {
        if (error.response.status === 401) {
          this.$toastr.e('', error.response.data.message)
        } else if (error.response.status === 402) {
          this.$toastr.e('', error.response.data.message)
        } else {
          this.$toastr.e('', error.response.data.message)
        }
        this.isRegistering = false
        this.isRegistered = false
      })
    },
    getStates() {
      getStates({country_id: 1}, response => {
        if (response.data.status === 'success') {
          this.states = response.data.states
          this.districts = []
          this.cities = []
          this.form.district = null
          this.form.city = null
        }
      }, error => {
        this.$toastr.e('', 'Error loading provinces')
        console.error(error)
      })
    },
    getDistricts(state_id) {
      getDistricts({state_id: state_id}, response => {
        if (response.data.status === 'success') {
          this.districts = response.data.districts
          this.cities = []
          this.form.district = null
          this.form.city = null
        }
      }, error => {
        this.$toastr.e('', 'Error loading districts')
        console.error(error)
      })
    },
    getCities(district_id) {
      getCities({district_id: district_id}, response => {
        if (response.data.status === 'success') {
          this.cities = response.data.cities
          this.form.city = null
        }
      }, error => {
        this.$toastr.e('', 'Error loading cities')
        console.error(error)
      })
    },
    getCatsByPostType() {
      const obj = {
        post_type_id: this.form.post_type_id,
        order_by: 'display_order',
        order: 'ASC',
        parent: 'no_parent'
      }
      getCatsByPostType(obj, response => {
        if (response.data.status === 'success') {
          this.cats = response.data.cats
        }
      }, error => {
        this.$toastr.e('', 'Error loading cats')
        console.error(error)
      })
    },
    getChildCat(cat_id) {
      this.child_cats = []
      this.child_cats2 = []
      this.form.child_cat_id = null
      this.form.child_cat_id2 = null
      const obj = {
        post_type_id: this.form.post_type_id,
        order_by: 'display_order',
        order: 'ASC',
        parent_id: cat_id
      }
      getCatsByPostType(obj, response => {
        if (response.data.status === 'success') {
          this.child_cats = response.data.cats
        }
      }, error => {
        this.$toastr.e('', 'Error loading cats')
        console.error(error)
      })
    },
    getChildCat2(cat_id) {
      this.child_cats2 = []
      this.form.child_cat_id2 = null
      const obj = {
        post_type_id: this.form.post_type_id,
        order_by: 'display_order',
        order: 'ASC',
        parent_id: cat_id
      }
      getCatsByPostType(obj, response => {
        if (response.data.status === 'success') {
          this.child_cats2 = response.data.cats
        }
      }, error => {
        this.$toastr.e('', 'Error loading cats')
        console.error(error)
      })
    },
    verifyStudentOTP() {
      if (this.studentPhoneVerify.verifyOtp.length >= 4) {
        this.verifyStudentPhone()
      }
    },
    verifyParentOTP() {
      if (this.parentPhoneVerify.verifyOtp.length >= 4) {
        this.verifyParentPhone()
      }
    },
    sendStudentVerification() {
      this.studentPhoneVerify.isVerificationCodeSent = true
      this.studentPhoneVerify.realOtp = null
      this.studentPhoneVerify.verifyOtp = null
      this.studentCountdown()
      sendVerification({phone: this.form.studentPhone}, (response, otp) => {
        if (response.data.status === 'queued') {
          this.studentPhoneVerify.realOtp = otp
          this.$toastr.s('', 'Verification is sent. Please check your phone')
        }
      }, error => {
        this.studentPhoneVerify.isVerificationCodeSent = false
        this.studentPhoneVerify.timer = 120
        this.$toastr.e('', 'Error sending verification code')
        console.error(error)
      })
    },
    sendParentVerification() {
      this.parentPhoneVerify.isVerificationCodeSent = true
      this.parentPhoneVerify.realOtp = null
      this.parentPhoneVerify.verifyOtp = null
      this.parentCountdown()
      sendVerification({phone: this.form.studentPhone}, (response, otp) => {
        if (response.data.status === 'queued') {
          this.$toastr.s('', 'Verification is sent. Please check your phone')
          this.parentPhoneVerify.realOtp = otp
        }
      }, error => {
        this.parentPhoneVerify.isVerificationCodeSent = false
        this.parentPhoneVerify.timer = 120
        this.$toastr.e('', 'Error sending verification code')
        console.error(error)
      })
    },
    verifyStudentPhone() {
      if (parseInt(this.studentPhoneVerify.realOtp) === parseInt(this.studentPhoneVerify.verifyOtp)) {
        this.studentPhoneVerify.verified = true
        this.$toastr.s('', 'Phone number is verified. Click next')
      } else {
        this.studentPhoneVerify.verified = false
        this.$toastr.e('', 'Verification code is incorrect')
      }
    },
    verifyParentPhone() {
      if (parseInt(this.parentPhoneVerify.realOtp) === parseInt(this.parentPhoneVerify.verifyOtp)) {
        this.parentPhoneVerify.verified = true
        this.$toastr.s('', 'Phone number is verified. Click next')
      } else {
        this.parentPhoneVerify.verified = false
        this.$toastr.e('', 'Verification code is incorrect')
      }
    },
    studentCountdown() {
      const threashold = 1000
      let counter = setInterval(() => {
        this.studentPhoneVerify.timer--
        if (this.studentPhoneVerify.timer === 0) {
          clearInterval(counter)
          if (!this.studentPhoneVerify.verified) {
            this.studentPhoneVerify.isVerificationCodeSent = false
            this.studentPhoneVerify.realOtp = null
            this.studentPhoneVerify.verifyOtp = null
            this.studentPhoneVerify.timer = 120
          }
        }
      }, threashold)
    },
    parentCountdown() {
      const threashold = 1000
      let counter = setInterval(() => {
        this.parentPhoneVerify.timer--
        if (this.parentPhoneVerify.timer === 0) {
          clearInterval(counter)
          if (!this.parentPhoneVerify.verified) {
            this.parentPhoneVerify.isVerificationCodeSent = false
            this.parentPhoneVerify.realOtp = null
            this.parentPhoneVerify.verifyOtp = null
            this.parentPhoneVerify.timer = 120
          }
        }
      }, threashold)
    },
    useStudentPhone(state) {
      if (state) {
        this.form.parentPhone = this.form.studentPhone
      } else {
        this.form.parentPhone = null
        this.parentPhoneVerify.isVerificationCodeSent = false
        this.parentPhoneVerify.realOtp = null
        this.parentPhoneVerify.verifyOtp = null
        this.parentPhoneVerify.timer = 60
        this.parentPhoneVerify.verified = false
      }
    },
    resetFields(initial) {
      this.studentPhoneVerify = {
        isVerificationCodeSent: false,
        realOtp: null,
        verifyOtp: null,
        timer: 120,
        verified: false
      }
      this.parentPhoneVerify = {
        isVerificationCodeSent: false,
        realOtp: null,
        verifyOtp: null,
        timer: 120,
        verified: false
      }
      this.page = 1
      this.form = {
        firstname: null,
        lastname: null,
        email: null,
        studentPhone: null,
        sameAsStudent: false,
        parentPhone: null,
        gender: 1,
        dob: null,
        address: null,
        state: null,
        district: null,
        city: null,
        // school: null,
        // grade: null,
        username: null,
        password: null,
        repassword: null,
      }
      this.isRegistering = false
      if (initial) {
        this.isRegistered = false
      }
    }
  },
  mounted() {
    this.getStates()
    this.getCatsByPostType()
    this.eventBus.$on('reset-form', this.resetFields)
  },
  beforeDestroy() {
    this.eventBus.$off('reset-form', this.resetFields)
  }
}
</script>

<style lang="scss" scoped>
.ground {
  height: 0.3rem;
  width: 100%;
  border-radius: 1rem;
}
.man-walk-container {
  width: calc(100% - 56px);
  .man-walk {
    transition: 0.2s;
    left: 0%;
    position: relative;
    display: inline-block;
  }
}
.flag {
  right: 0;
  bottom: 5px;
  &.won {
    transform: translateY(-23px) scale(0.75);
  }
}
</style>