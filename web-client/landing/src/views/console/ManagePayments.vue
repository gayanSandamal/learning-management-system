<template>
  <div class="page-inner">
    <div class="row w-100">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title w-100 mb-3 d-flex justify-content-between align-items-center">
              <span>Manage Payments</span>
              <Button class="btn-sm" :label="'Get Filters'" :labelColor="'color-7'" @click="is_filter = true"></Button>
            </h5>
            <table class="table w-100 table-striped position-relative z-index-2">
              <thead>
                <tr>
                  <th><small>#</small></th>
                  <th><small>Student name</small></th>
                  <th><small>Student email</small></th>
                  <th><small>Class</small></th>
                  <th><small>Instructor</small></th>
                  <th><small>Enrolled date</small></th>
                  <th class="text-end"><small>Fee</small></th>
                  <th><small>Enrolled time</small></th>
                  <th><small>Actions</small></th>
                </tr>
              </thead>
              <tbody v-if="searching">
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <svg class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr v-for="(payment, index) in payments" :key="payment.id" :class="{'table-warning': payment.hover}" @mouseenter="$set(payment, 'hover', true)" @mouseleave="$set(payment, 'hover', false)">
                  <td>
                    <small v-text="++index"></small>
                  </td>
                  <td>
                    <small class="no-wrap" v-text="`${payment.student_firstname} ${payment.student_lastname}`"></small>
                    <small class="d-block fst-italic" v-text="payment.student_username"></small>
                  </td>
                  <td>
                    <small v-text="payment.student_email"></small>
                  </td>
                  <td>
                    <small class="d-block" v-text="payment.cat_name"></small>
                    <small class="d-block fst-italic" v-text="payment.cat_slug"></small>
                  </td>
                  <td>
                    <small class="d-block no-wrap" v-text="`${payment.paid_to_firstname} ${payment.paid_to_lastname}`"></small>
                    <small class="d-block fst-italic" v-text="payment.paid_to_username"></small>
                  </td>
                  <td>
                    <small class="d-block" v-text="month(payment.enrolled_date)"></small>
                  </td>
                  <td>
                    <small class="d-block" v-text="payment.fee"></small>
                  </td>
                  <td>
                    <small class="d-block" v-text="time(payment.last_updated)"></small>
                  </td>
                  <td>
                    <div class="d-flex align-items-center justify-content-between">
                      <Button v-if="payment_state === 2" class="btn-sm" :labelColor="'color-8'" :label="'Approval'" @click="$set(payment, 'IsApprovalModal', true)"></Button>
                      <Prompt v-if="payment.IsApprovalModal" :title="'Approve enrollment'" @on-close="closeApprovalModal(payment)" @on-ok="closeApprovalModal(payment)" :okLabel="''" :closeLabel="'Cancel'" :btn="'btn-success'" :showOkayButton="false">
                        <div class="row">
                          <div class="col-12 mb-3">
                            <img width="100%" :src="`${baseUrl}/api/uploads/slips/slips/${payment.file_name}`">
                          </div>
                          <div v-if="IsSelectReason && selectedReasonId > -1" class="col-12 mb-3">
                            <p>Select reason to reject and student will be notified</p>
                            <div class="form-check" v-for="rejectingReason in rejectingReasons" :key="rejectingReason.id">
                              <input class="form-check-input" type="radio" name="rejectReason" :id="'reason-'+rejectingReason.id" :value="rejectingReason.id" :checked="rejectingReason.id === selectedReasonId" v-model="selectedReasonId">
                              <label class="form-check-label" :for="'reason-'+rejectingReason.id" v-text="rejectingReason.reason"></label>
                            </div>
                            <div class="form-group mt-2">
                              <label class="mb-2">Must enter the reason to reject if you select "Other"<sup class="text-danger">*</sup> <small class="text-danger">(required)</small></label>
                              <textarea v-if="selectedReasonId === 4" rows="5" class="form-control" v-model="otherRejectReason"></textarea>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="d-flex justify-content-end w-100">
                              <Button class="btn-sm me-3" :labelColor="'color-7'" :label="!isRejecting ? 'Reject' : 'Rejecting...'" :disabled="isRejecting" @click="rejectSection(payment)"></Button>
                              <Button class="btn-sm" :labelColor="'color-5'" :label="!isApproving ? 'Approve' : 'Approving'" :disabled="isApproving" @click="approve(payment,  true)"></Button>
                            </div>
                          </div>
                        </div>
                      </Prompt>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <nav class="d-flex position-relative z-index-1">
              <div class="form-group d-flex align-items-start me-4">
                <div class="d-flex align-items-center">
                  <label class="text-nowrap mb-0 me-2">Per page: </label>
                  <select class="form-select form-select-md pe-5" v-model="pagination.per_page" @change="changePerPage">
                    <option v-for="page in pagination.pages" :key="page" :value="page" v-text="page"></option>
                  </select>
                </div>
              </div>
              <ul class="pagination">
                <li class="page-item" :class="{'disabled' : !isOnFirstPage}">
                  <a class="page-link" :class="{'bg-light' : !isOnFirstPage}" @click="goPages(false)">Previous</a>
                </li>
              </ul>
              <ul class="pagination pagination-middle">
                <li class="page-item" :class="{'active' : pagination.current_page === elm}" v-for="elm in pageCount" :key="elm">
                  <a class="page-link clickable" v-text="elm" @click="selectPage(elm)"></a>
                </li>
              </ul>
              <ul class="pagination">
                <li class="page-item" :class="{'disabled' : !isOnLastPage}">
                  <a class="page-link" :class="{'bg-light' : !isOnLastPage}" @click="goPages(true)">Next</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="filter-container py-3" v-if="is_filter" @click="is_filter = false">
      <div class="container-fluid">
        <div class="row justify-content-end">
          <div class="col-12 col-lg-3">
            <div class="card" @click.stop="function () {}">
              <div class="card-body">
                <h6 class="card-title"><strong>Filters</strong></h6>
                <div class="form">
                  <div class="row">
                    <div class="col-12 mb-2">
                      <label for="searchkey" class="form-label text-secondary"><small>Enter search keyword</small></label>
                      <input type="text" class="form-control" id="searchkey" autofocus v-model="searchKeyword">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 mb-2">
                      <label for="searchby" class="form-label text-secondary"><small>Search by</small></label>
                      <select class="form-select form-select-md" id="searchby" v-model="searchBy">
                        <option v-for="search in searchByList" :key="search.id" v-text="search.label" :value="search.key"></option>
                      </select>
                    </div>
                  </div>
                  <div class="row z-index-top position-relative">
                    <div class="col-12 mb-2">
                      <div class="form-group">
                        <label class="form-label text-secondary"><small>Enrollment month</small></label>
                        <month-picker-input class="w-100 right-align" :default-year="defaultDate.year" :default-month="defaultDate.month" :input-pre-filled="true" :no-default="false" @change="showDate"></month-picker-input>
                      </div>
                    </div>
                  </div>
                  <div class="row position-relative">
                    <div class="col-12 mb-2">
                      <div class="form-group">
                        <label class="form-label text-secondary"><small>Select class</small></label>
                        <treeselect class="tree-small" v-model="selected_category_id" :multiple="false" :options="mappedCats" />
                      </div>
                    </div>
                  </div>
                  <div class="row position-relative">
                    <div class="col-12 mb-2">
                      <label for="instructor" class="form-label text-secondary"><small>Select instructor</small></label>
                      <treeselect class="tree-small" v-model="selected_instructor_id" :multiple="false" :options="mappedInstructors" />
                    </div>
                  </div>
                  <hr>
                  <div class="row position-relative">
                    <div class="col-12 mb-2">
                      <div class="form-group">
                        <label class="form-label text-secondary"><small>Approval state</small></label>
                        <div class="d-flex">
                          <div class="form-check me-2" v-for="state in payment_states" :key="state.id">
                            <input class="form-check-input" type="radio" name="states" id="state" :value="state.key" v-model="payment_state">
                            <label class="form-check-label text-secondary" for="state"><small v-text="state.label"></small></label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row position-relative">
                    <div class="col-12">
                      <Button class="w-100 me-3" v-if="searchKeyword && searchKeyword.trim() !== ''" :label="'Clear'" :labelColor="'color-3'" @click="checkClear"></Button>
                      <Button class="w-100" :label="searching ? '' : 'Apply filter'" :labelColor="'color-5'" @click="getPayments">
                        <svg v-if="searching" class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
                      </Button>
                    </div>
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
import { getPayments, getUsersByRole, approvePayment } from '@/api/console'
import { getCatsByPostType } from '@/api/postType'
import moment from 'moment'
import { MonthPickerInput } from 'vue-month-picker'
import Treeselect from '@riophae/vue-treeselect'

export default {
  name: 'categories',
  data() {
    return {
      is_filter: false,
      payments: [],
      cats: [],
      searchBy: 'u.firstname',
      searchKeyword: null,
      enrollment_month: null,
      searching: false,
      payment_state: 0,
      selected_category_id: null,
      selected_instructor_id: null,
      pagination: {
        per_page: 25,
        record_count: 0,
        current_page: 1,
        pages: [25, 50, 100, 200, 300, 400, 500]
      },
      defaultDate: {
        year: parseInt(moment().format('YYYY')),
        month: parseInt(moment().format('MM'))
      },
      searchByList: [
        {
          id: 0,
          label: `Student's first name`,
          key: 'u.firstname'
        },
        {
          id: 1,
          label: `Student's last name`,
          key: 'u.lastname'
        },
        {
          id: 2,
          label: `Student's e-mail`,
          key: 'u.email'
        }
      ],
      payment_states: [
        {
          id: 1,
          label: 'Approved',
          key: 1
        },
        {
          id: 2,
          label: 'Pending',
          key: 0
        },
        {
          id: 3,
          label: 'Rejected',
          key: 2
        }
      ],
      instructors: [],
      rejectingReasons: [
        {
          id: 1,
          reason: 'Invalid bank slip'
        },
        {
          id: 2,
          reason: 'Uploaded bank slip is not clear'
        },
        {
          id: 3,
          reason: 'Cannot approve at the moment'
        },
        {
          id: 4,
          reason: 'Other'
        }
      ],
      selectedReasonId: -1,
      otherRejectReason: null,
      IsSelectReason: false,
      isApproving: false,
      isRejecting: false
    }
  },
  props: {
    eventBus: undefined,
    account: undefined
  },
  components: {
    Button: () => import('@/components/common/Button'),
    Prompt: () => import('@/components/modals/Prompt'),
    // Datepicker: () => import('vuejs-datepicker'),
    MonthPickerInput,
    Treeselect
  },
  computed: {
    baseUrl() {
      return process.env.VUE_APP_BASE_URL
    },
    postType() {
      return parseInt(this.$route.query.post_type)
    },
    searchByName() {
      return 0
    },
    pageCount() {
      let val = this.pagination.record_count / this.pagination.per_page
      if (val % 1 !== 0) {
        val = Math.trunc(val) + 1
      }
      return val
    },
    pageOffset() {
      let offset = 0
      if (this.pagination.current_page > 1) {
        offset = (this.pagination.current_page - 1) * this.pagination.per_page
      }
      return offset
    },
    isOnLastPage() {
      return this.pageCount > this.pagination.current_page
    },
    isOnFirstPage() {
      return this.pagination.current_page > 1
    },
    mappedCatsClean() {
      let arr = []
      this.cats.map(o => {
        this.$set(o, 'label', `${o.name} (${o.slug})`)
        arr.push(o)
      })
      return arr
    },
    mappedCats() {
      let mappedCats = []
      this.cats.forEach(o => {
        this.$set(o, 'label', o.name)
        if (o.parent_category_id) {
          this.cats.forEach(p => {
            if (!p.children) {
              this.$set(p, 'children', [])
            }
            if (o.parent_category_id === p.id) {
              p.children.push(o)
              this.$set(o, 'is_referred', true)
              this.$set(o, 'index', index)
            }
          })
        }
        mappedCats.push(o)
      })
      let i = mappedCats.length
      while (i--) {
        if (mappedCats[i].is_referred) {
          mappedCats.splice(i, 1)
        }
      }
      let index = 0
      const addDepth = (arr, depth = 0) => {
        arr.forEach(obj => {
          index++
          this.$set(obj, 'level', depth)
          this.$set(obj, 'index', index)
          addDepth(obj.children, depth + 1)
          if (obj.children.length === 0) {
            this.$set(obj, 'children', undefined)
          }
        })
      }
      addDepth(mappedCats)
      return mappedCats
    },
    mappedInstructors() {
      let arr = []
      this.instructors.map(o => {
        this.$set(o, 'label', `${o.firstname} ${o.lastname}`)
        arr.push(o)
      })
      return arr
    }
  },
  methods: {
    getPayments() {
      const obj = {
        per_page: this.pagination.per_page,
        offset: this.pageOffset,
        search_by: this.searchBy,
        search_keyword: this.searchKeyword,
        order_by: 'id',
        order: 'DESC',
        post_type_id: 1,
        item_id: this.selected_category_id,
        state: this.payment_state,
        assignee_id: this.selected_instructor_id,
        enrollment_month: this.enrollment_month ? moment(this.enrollment_month).format('YYYY-MM-DD 00:00:00') : moment().format('YYYY-MM-DD 00:00:00')
      }
      this.searching = true
      getPayments(obj, response => {
        this.searching = false
        if (response.data.status === 'success') {
          this.payments = response.data.records
          this.pagination.record_count = response.data.record_count
        }
      }, error => {
        this.searching = false
        this.$toastr.e('', 'Error loading payments')
        console.error(error)
      })
    },
    checkClear() {
      this.searchBy = 0
      this.searchKeyword = null
      this.getPayments()
    },
    selectPage(elm) {
      this.pagination.current_page = elm
      this.getPayments()
    },
    changePerPage() {
      this.pagination.current_page = 1
      this.getPayments()
    },
    goPages(is_next) {
      if (is_next) {
        if (this.isOnLastPage) {
          this.pagination.current_page += 1
        }
      } else {
        if (this.isOnFirstPage) {
          this.pagination.current_page -= 1
        }
      }
    },
    time(date) {
      const postedDate = moment.utc(date).format()
      const postedStillUtc = moment.utc(postedDate).toDate()
      const postedLocal = moment(postedStillUtc).local().format('YYYY-MM-DD HH:mm:ss')
      return postedLocal
    },
    date(date) {
      const postedDate = moment.utc(date).format()
      const postedStillUtc = moment.utc(postedDate).toDate()
      const postedLocal = moment(postedStillUtc).local().format('YYYY-MM-DD')
      return postedLocal
    },
    month(date) {
      const postedDate = moment.utc(date).format()
      const postedStillUtc = moment.utc(postedDate).toDate()
      const postedLocal = moment(postedStillUtc).local().format('YYYY, MMM')
      return postedLocal
    },
    getCatsByPostType() {
      const obj = {
        post_type_id: 1,
        order_by: 'display_order',
        order: 'ASC',
        per_page: 5000,
        offset: 0,
        search_by: 0
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
    showDate (date) {
      this.enrollment_month = moment(date.from).format('YYYY-MM-01 00:00:00')
    },
    getUsersByRole () {
      const obj = {
        roleId: 1024,
      }
      getUsersByRole(obj, response => {
        if (response.data.status === 'success') {
          this.instructors = response.data.users
        }
      }, error => {
        this.$toastr.e('', 'Error loading instructors')
        console.error(error)
      })
    },
    rejectSection (payment) {
      if (!this.IsSelectReason && this.selectedReasonId === -1) {
        this.IsSelectReason = true
        this.selectedReasonId = 1
      } else if (this.IsSelectReason && this.selectedReasonId > -1) {
        this.approve(payment,  false)
      }
    },
    closeApprovalModal (payment) {
      this.$set(payment, 'IsApprovalModal', false)
      this.IsSelectReason = false
      this.selectedReasonId = -1
    },
    approve (payment, state) {
      let reason = ''
      if (this.selectedReasonId > -1 && this.selectedReasonId !== 4) {
        reason = this.rejectingReasons.find(o => o.id === this.selectedReasonId).reason
      } else {
        reason = this.otherRejectReason
      }
      const obj = {
        id: payment.id,
        status: state ? 1 : 2,
        student_email: payment.student_email,
        student_firstname: payment.student_firstname,
        cat_name: payment.cat_name,
        reason: reason,
        invoice: payment.invoice
      }
      if (state) {
        this.isApproving = true
      } else {
        this.isRejecting = true
      }
      approvePayment (obj, response => {
        if (response.status === 200) {
          this.closeApprovalModal(payment)
          const index = this.payments.indexOf(payment)
          if (index > -1) {
            this.payments.splice(index, 1)
          }
          this.$toastr.s('', response.data.message)
        }
        this.isApproving = false
        this.isRejecting = true
      }, err => {
        this.isApproving = false
        this.isRejecting = true
        this.closeApprovalModal(payment)
        const index = this.payments.indexOf(payment)
        if (index > -1) {
          this.payments.splice(index, 1)
        }
        this.$toastr.e('', err.response.data.message)
      })
    }
  },
  mounted () {
    this.getPayments()
    this.getCatsByPostType()
    this.getUsersByRole()
  },
  beforeDestroy () {
  }
}
</script>

<style lang="scss" scoped>
@import '~@riophae/vue-treeselect/dist/vue-treeselect.css';
.post-cat-select {
  width: 350px;
}
.lesson-thumb {
  width: 100px;
}
.z-index-top {
  z-index: 99;
}
</style>
