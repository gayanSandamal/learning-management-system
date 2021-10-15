<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="display-6 fs-3">
          Enrolled classes
        </h1>
        <div class="w-100 z-index-99 position-relative mb-3">
          <month-picker-input class="w-100 dark" :default-year="defaultDate.year" :default-month="defaultDate.month" :input-pre-filled="true" :no-default="false" @change="showDate"></month-picker-input>
        </div>
        <div class="w-100 z-index-1 position-relative mb-3">
          <div class="form-check form-check-inline" v-for="state in payment_states" :key="state.id">
            <input class="form-check-input" type="radio" name="states" id="state" :value="state.key" v-model="payment_state" @change="stateChange">
            <label class="form-check-label" for="state"><small v-text="state.label"></small></label>
          </div>
        </div>
        <ul class="list-group z-index-1 position-relative">
          <li v-for="enrolled_class in enrolled_classes" :key="enrolled_class.post_id" class="list-group-item">
            <div class="row align-items-start">
              <div class="col-md-8">
                <table>
                  <tbody>
                    <tr>
                      <td>
                        <small>Lesson: </small>
                      </td>
                      <td>
                        <p class="m-0">
                          <small v-text="enrolled_class.post_name"></small>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <small>Class: </small>
                      </td>
                      <td>
                        <p class="m-0">
                          <span class="badge bg-success" v-text="`${enrolled_class.cat_name}`"></span>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <small>Slug: </small>
                      </td>
                      <td>
                        <p class="m-0">
                          <small v-text="`${enrolled_class.cat_slug}`"></small>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <small>Instructor: </small>
                      </td>
                      <td>
                        <p class="m-0 fst-italic">
                          <small v-text="`${enrolled_class.assignee_firstname}`"></small>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <small>Fee: </small>
                      </td>
                      <td>
                        <p class="m-0">
                          <span class="badge bg-danger" v-text="`Rs.${enrolled_class.fee}/=`"></span>
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- <p class="m-0">
                  <small v-text="enrolled_class.post_name"></small>
                </p>
                <p class="m-0">
                  <span class="badge bg-success" v-text="`${enrolled_class.cat_name} (${enrolled_class.cat_slug})`"></span>
                </p>
                <p class="m-0 fst-italic">
                  <small v-text="`by ${enrolled_class.assignee_firstname}`"></small>
                </p>
                <p>
                  <span class="badge bg-danger" v-text="`Rs.${enrolled_class.fee}/=`"></span>
                </p> -->
              </div>
              <div class="col-md-4">
                <Button v-if="payment_state === 1" class="w-100 mt-2 btn-outline-success" :label="'Watch lesson'" :labelColor="'color-5'" @click="$router.push({name: 'Lesson', query: {item_id: enrolled_class.item_id, post_id: enrolled_class.post_id}})"/>
              </div>
            </div>
          </li>
        </ul>
        <nav class="d-flex position-relative z-index-1 mt-3">
          <div class="form-group d-flex align-items-start me-4">
            <div class="d-flex align-items-center">
              <label class="text-nowrap mb-0 me-2">Per page: </label>
              <select class="form-select pe-5 bg-dark text-white" v-model="pagination.per_page" @change="changePerPage">
                <option v-for="page in pagination.pages" :key="page" :value="page" v-text="page"></option>
              </select>
            </div>
          </div>
          <ul class="pagination">
            <li class="page-item" :class="{'disabled' : !isOnFirstPage}">
              <a class="page-link bg-dark text-white" :class="{'bg-light' : !isOnFirstPage}" @click="goPages(false)">Previous</a>
            </li>
          </ul>
          <ul class="pagination pagination-middle">
            <li class="page-item" :class="{'active' : pagination.current_page === elm}" v-for="elm in pageCount" :key="elm">
              <a class="page-link clickable bg-dark text-white" v-text="elm" @click="selectPage(elm)"></a>
            </li>
          </ul>
          <ul class="pagination">
            <li class="page-item" :class="{'disabled' : !isOnLastPage}">
              <a class="page-link bg-dark text-white" :class="{'bg-light' : !isOnLastPage}" @click="goPages(true)">Next</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
const { ipcRenderer: ipc } = require('electron')
import { getEnrolledClassesFull } from '@/api/console'
import moment from 'moment'
import { MonthPickerInput } from 'vue-month-picker'
export default {
  name: 'dashboard',
  components: {
    MonthPickerInput,
    Button: () => import('@/components/Button')
  },
  data () {
    return {
      enrolled_classes: [],
      loading: false,
      defaultDate: {
        year: parseInt(moment().format('YYYY')),
        month: parseInt(moment().format('MM'))
      },
      date: null,
      payment_state: 1,
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
      pagination: {
        per_page: 25,
        record_count: 0,
        current_page: 1,
        pages: [25, 50, 100, 200, 300, 400, 500]
      }
    }
  },
  props: {
    account: {
      type: Object
    }
  },
  computed: {
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
  },
  methods: {
    getEnrolledClasses() {
      this.enrolled_classes = []
      const obj = {
        user_id: this.account.id,
        enrollment_month: this.date,
        state: this.payment_state,
        per_page: this.pagination.per_page,
        offset: this.pageOffset
      }
      this.loading = true
      getEnrolledClassesFull(obj, response => {
        this.loading = false
        if (response.data.classes.length > 0) {
          this.enrolled_classes = response.data.classes
          this.pagination.record_count = response.data.record_count
        }
      }, error => {
        this.loading = false
        console.error(error)
      })
    },
    showDate (date) {
      this.date = moment(date.from).format('YYYY-MM-01 00:00:00')
      this.getEnrolledClasses()
    },
    stateChange () {
      this.getEnrolledClasses()
    },
    selectPage(elm) {
      this.pagination.current_page = elm
      this.getEnrolledClasses()
    },
    changePerPage() {
      this.pagination.current_page = 1
      this.getEnrolledClasses()
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
    }
  },
  created () {
    ipc.send('maximize')
  },
  mounted () {
    this.getEnrolledClasses()
  }
}
</script>