<template>
  <div class="page">
    <section v-if="summary" class="w-100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8 col-md-6 col-lg-5">
            <div class="card bg-color-2 card-curved px-0 px-sm-3 shadow">
              <div class="card-body">
                <h5 class="card-title text-start text-white mb-3" v-text="summary.cat_name"></h5>
                <table class="tbody w-100 text-white">
                  <tr>
                    <td>Class:</td>
                    <td class="ps-2 pb-2" v-text="summary.cat_name"></td>
                  </tr>
                  <tr>
                    <td>Conducted by:</td>
                    <td class="ps-2 pb-2" v-text="`${summary.honorific}.${summary.firstname} ${summary.lastname}`"></td>
                  </tr>
                  <tr>
                    <td>Class fee:</td>
                    <td class="ps-2 pb-2" v-text="`Rs.${summary.fee}/=`"></td>
                  </tr>
                  <tr>
                    <td>Enrolling month:</td>
                    <td class="ps-2 pb-2 text-dark">
                      <month-picker-input :default-year="defaultDate.year" :default-month="defaultDate.month" :input-pre-filled="true" :no-default="false" @change="showDate"></month-picker-input>
                    </td>
                  </tr>
                  <tr>
                    <td>Student name:</td>
                    <td class="ps-2 pb-2" v-text="`${account.firstname} ${account.lastname}`"></td>
                  </tr>
                  <tr>
                    <td>Student email:</td>
                    <td class="ps-2 pb-2" v-text="`${account.email}`"></td>
                  </tr>
                  <tr>
                    <td>Bank slip reference:</td>
                    <td class="ps-2 pb-2" v-text="`${slipReference}`"></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <Button class="w-100 mt-3" :label="'Enroll now'" :labelColor="'color-4'" @click="checkout"/>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <Prompt v-if="isBankTransferModal" :title="referenceNo ? 'Order reference number' : 'Bank Transfer'" @on-close="closeModal" @on-ok="uploadSlipAction" :okLabel="'Upload bank slip'" :closeLabel="referenceNo ? 'Ok' : 'Cancel'" :btn="'btn-success'" :disabled="disabledUploadSlip" :showOkayButton="showOkayButton">
      <div v-if="referenceNo">
        <div class="d-flex justify-content-center w-100">
          <div>
            <div class="d-flex mb-2">
              <img width="50" :src="require('@/assets/logo.png')">
              <p class="mb-0 d-flex align-items-center ms-2"><strong>Refernece PDF document</strong></p>
            </div>
            <p class="lead">www.kapilasiriwardhana.lk</p>
            <p class="mb-0">
              <small>
                <em>Dear {{account.firstname}},<br>
                We received your payment details and<br>
                our teamwill approve your submission once reviewed.<br>
                Reference number for the payment is below.<br>
                Team.</em>
              </small>
            </p>
            <p class="my-2">Reference no: <span class="badge bg-danger">{{referenceNo}}</span></p>
            <p class="mb-0"><small>Time: {{time}}</small></p>
          </div>
        </div>
        <p class="mt-4 text-center mb-0"><em>The payment receipt will download automatically in few seconds</em></p>
        <a class="link-primary mt-2 d-block text-center" role="button" @click="generateReport">Did not download the PDF automatically? Click here</a>
      </div>
      <div v-else class="table-responsive">
        <p class="mb-2">Select a bank</p>
        <select class="form-select mb-3" v-model="selectedBank">
          <option v-for="(bank, index) in banks" :key="index" :value="bank.id" v-text="bank.name"></option>
        </select>
        <table class="table table-sm">
          <tbody>
            <tr>
              <th scope="row">Account name</th>
              <td v-text="selectedBankDetails.acc_name"></td>
            </tr>
            <tr>
              <th scope="row">Account number</th>
              <td v-text="selectedBankDetails.acc_no"></td>
            </tr>
            <tr>
              <th scope="row">Bank</th>
              <td v-text="selectedBankDetails.bank"></td>
            </tr>
            <tr>
              <th scope="row">Branch</th>
              <td v-text="selectedBankDetails.branch"></td>
            </tr>
            <tr>
              <th scope="row">Amount</th>
              <td v-text="`Rs.${summary.fee}/=`"></td>
            </tr>
          </tbody>
        </table>
        <hr>
        <p class="text-danger mt-3"><small>***Upload a scanned image or a clear photograph of the bank deposit slip***</small></p>
        <div class="form-group mb-3" :class="{'file-added' : !disabledUploadSlip}">
          <div class="w-100 d-flex align-items-center mb-4">Upload the bank slip<small class="ms-2 text-danger">(only .jpg and .png files are allowed)</small></div>
          <input v-show="false" type="file" class="form-control-file inputfile" id="uploadBankSlip" accept="image/png, image/jpeg">
          <label for="uploadBankSlip" class="w-100 d-flex" role="button">
            <span class="w-100"><small v-text="upload_file_name"></small></span>
            <strong class="w-100 text-nowrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
              Choose a file…
            </strong>
          </label>
        </div>
        <div class="progress mb-0 mt-0" v-if="uploadProgress !== 0">
          <div class="progress-bar" :class="{'bg-success': uploadProgress >= 100, 'progress-bar-striped' : uploadProgress < 100}" :style="{'width': uploadProgress + '%'}" v-text="progressText"></div>
        </div>
      </div>
    </Prompt>
  </div>
</template>

<script>
import moment from 'moment'
import Compressor from 'compressorjs'
import Resumable from 'resumablejs'
import { getPaymentSummary, addEnrollment } from '@/api/postType'
import { MonthPickerInput } from 'vue-month-picker'
export default {
  name: 'grade-1-to-al',
  data() {
    return {
      summary: undefined,
      defaultDate: {
        year: parseInt(moment().format('YYYY')),
        month: parseInt(moment().format('MM'))
      },
      form: {
        date: null
      },
      isBankTransferModal: false,
      referenceNo: null,
      file: [],
      r: undefined,
      selectedBank: 1,
      banks: [
        {
          id: 1,
          name: 'Sampath Bank'
        },
        {
          id: 2,
          name: 'Hatton National Bank'
        },
        {
          id: 3,
          name: 'Commercial Bank'
        },
        {
          id: 4,
          name: 'Bank of Ceylon'
        }
      ],
      bankSlip: {
        id: null
      },
      upload_file_name: null,
      uploadProgress: 0,
      disabledUploadSlip: true,
      payment_type: 0, // bank transfer
      showOkayButton: true,
      disabled: false
    }
  },
  components: {
    Button: () => import('@/components/common/Button'),
    Prompt: () => import('@/components/modals/Prompt'),
    MonthPickerInput
  },
  props: {
    account: undefined
  },
  computed: {
    assigneeId () {
      return this.$route.query.assignee_id
    },
    postTypeId () {
      return this.$route.query.post_type_id
    },
    enrollmentMode () {
      return this.$route.query.enrollment_mode
    },
    catId () {
      return this.$route.query.cat_id
    },
    itemId () {
      return this.$route.query.item_id
    },
    selectedBankDetails() {
      let selectedBank
      if (this.selectedBank === 1) {
        selectedBank = {
          acc_name: 'P.P.P.M.P.K. SIRIWARDENA',
          acc_no: '1200-5214-0576',
          bank: 'Sampath Bank',
          branch: 'Kiribathgoda Super Branch'
        }
      } else if (this.selectedBank === 2) {
        selectedBank = {
          acc_name: 'P.P.P.M.P.K. SIRIWARDENA',
          acc_no: '163020065821',
          bank: 'Hatton National Bank',
          branch: 'Yakkala'
        }
      } else if (this.selectedBank === 3) {
        selectedBank = {
          acc_name: 'P.P.P.M.P.K. SIRIWARDENA',
          acc_no: '8850019500',
          bank: 'Commercial Bank',
          branch: 'Yakkala'
        }
      } else if (this.selectedBank === 4) {
        selectedBank = {
          acc_name: 'P.P.P.M.P.K. SIRIWARDENA',
          acc_no: '1494184',
          bank: 'Bank of Ceylon',
          branch: 'Anuradhapura'
        }
      }
      return selectedBank
    },
    progressText() {
      let text
      if (this.uploadProgress < 100) {
        text = this.uploadProgress + '%'
      } else {
        text = 'Successfully uploaded'
      }
      return text
    },
    slipReference() {
      return `${this.summary.assignee_id}/${this.account.id}`
    }
  },
  methods: {
    getPaymentSummary () {
      const obj = {
        assignee_id: this.assigneeId,
        post_type_id: this.postTypeId,
        item_id: this.catId
      }
      getPaymentSummary(obj, response => {
        this.summary = response.data.summary
      }, error => {
        console.error(error)
      })
    },
    showDate (date) {
      this.form.date = moment(date.from).format('YYYY-MM-01 00:00:00')
    },
    checkout() {
      this.showOkayButton = true
      this.isBankTransferModal = true
      this.initiResumable(() => {
        setTimeout(() => {
          let uploadBankSlip = document.getElementById('uploadBankSlip')
          setTimeout(() => {
            if (uploadBankSlip) {
              this.r.assignBrowse(uploadBankSlip)
            } else {
              console.error('error initiating uploadBankSlip')
            }
          }, 100)
        }, 1000)
      })
    },
    uploadSlipAction() {
      this.r.upload()
    },
    closeModal() {
      this.isBankTransferModal = false
      this.disabledUploadSlip = true
      this.showOkayButton = false
      this.disabled = false
      this.clearBankSlip()
    },
    clearBankSlip() {
      this.uploadProgress = 0
      this.disabledUploadSlip = true
      this.upload_file_name = null
      this.referenceNo = null
    },
    compress(object) {
      let self = this
      new Compressor(object.file, {
        quality: 0.8,
        convertSize: 500000,
        maxWidth: 1600,
        success(result) {
          let copy = JSON.parse(JSON.stringify(object.file))
          result.uniqueIdentifier = copy.uniqueIdentifier
          object.file = result
          self.upload_file_name = result.name
        },
        error(err) {
          self.file = null
          self.$toastr.w('', err.message)
        }
      })
    },
    initiResumable(successCallback) {
      this.r = new Resumable({
        target: process.env.VUE_APP_PUBLIC_PATH+'/web-api/api/upload-slip',
        chunkSize: 0.05*1024*1024,
        simultaneousUploads: 1,
        testChunks: false,
        throttleProgressCallbacks: 1,
        fileType: ['jpeg', 'jpg', 'png']
      })
      this.r.on('fileAdded', (file) => {
        this.uploadProgress = 0
        this.disabledUploadSlip = false
        this.compress(file)
      })
      this.r.on('fileSuccess', (file, message) => {
        let message_object = JSON.parse(message)
        if (message_object.status === 'success') {
          if (message_object.id) {
            this.addEnrollment(message_object.id)
          }
        }
      })
      this.r.on('fileError', (file, message) => {
        console.error('error', file, message)
        this.clearBankSlip()
      })
      this.r.on('fileProgress', () => {
        this.uploadProgress = Math.floor(this.r.progress()*100)
      })
      successCallback()
    },
    addEnrollment(id) {
      const obj = {
        user_id: this.account.id,
        assignee_id: this.assigneeId,
        post_type_id: this.postTypeId,
        enrollment_mode: this.enrollmentMode,
        item_id: this.itemId,
        cat_id: this.catId,
        slip_id: id,
        date: this.form.date,
        slip_ref: this.slipReference,
        student_email: this.account.email,
        student_firstname: this.account.firstname,
        cat_name: this.summary.cat_name
      }
      addEnrollment(obj, response => {
        this.$toastr.s('', response.data.message)
        this.closeModal()
        this.$router.push({path: '/'})
      }, error => {
        console.error(error)
      })
    }
  },
  mounted() {
    this.getPaymentSummary()
  }
}
</script>

<style lang="scss" scoped>
</style>