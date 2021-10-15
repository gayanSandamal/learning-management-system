<template>
  <div class="page-inner">
    <div class="row w-100">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Users</h5>
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <select class="form-select form-select-md" v-model="searchBy">
                    <option value="0">Search by username</option>
                    <option value="1">Search by firstname</option>
                    <option value="2">Search by lastname</option>
                    <option value="3">Search by email</option>
                  </select>
                </div>
              </div>
              <div class="col-9">
                <div class="input-group mb-3">
                  <input type="text" class="form-control me-3" :placeholder="searchByName" v-model="searchKeyword">
                  <Button class="me-3" v-if="searchKeyword && searchKeyword.trim() !== ''" :label="'Clear'" :labelColor="'color-3'" @click="checkClear"></Button>
                  <Button :label="searching ? '' : 'Search'" :labelColor="'color-5'" @click="getUsers">
                    <svg v-if="searching" class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
                  </Button>
                </div>
              </div>
            </div>
            <table class="table w-100 table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Username</th>
                  <th>Firstname</th>
                  <th>Lastname</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Actions</th>
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
                <tr v-for="(user, index) in users" :key="user.id" :class="{'table-warning': user.hover}" @mouseenter="$set(user, 'hover', true)" @mouseleave="$set(user, 'hover', false)">
                  <td v-text="++index"></td>
                  <td v-text="user.username"></td>
                  <td v-text="user.firstname"></td>
                  <td v-text="user.lastname"></td>
                  <td v-text="user.email"></td>
                  <td v-text="user.roleName"></td>
                  <td>
                    <div class="d-flex align-items-center justify-content-between">
                      <Button class="me-3 text-warning" :label="'Edit'" @click="$router.push({query: {modal: 'EditUserModal', userId: user.id}})"></Button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <nav class="d-flex">
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
  </div>
</template>

<script>
import { getUsers } from '@/api/console'
export default {
  name: 'categories',
  data() {
    return {
      users: [],
      searchBy: 0,
      searchKeyword: null,
      searching: false,
      pagination: {
        per_page: 25,
        record_count: 0,
        current_page: 1,
        pages: [25, 50, 100, 200, 300, 400, 500]
      }
    }
  },
  props: {
    eventBus: undefined,
    account: undefined
  },
  components: {
    Button: () => import('@/components/common/Button')
  },
  computed: {
    postType() {
      return parseInt(this.$route.query.post_type)
    },
    searchByName() {
      let label = ''
      if (parseInt(this.searchBy) === 0) {
        label = 'Enter username here'
      } else if (parseInt(this.searchBy) === 1) {
        label = 'Enter first name here'
      } else if (parseInt(this.searchBy) === 2) {
        label = 'Enter last name here'
      } else if (parseInt(this.searchBy) === 3) {
        label = 'Enter email here'
      }
      return label
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
    }
  },
  methods: {
    getUsers() {
      const obj = {
        id: this.account.id,
        per_page: this.pagination.per_page,
        offset: this.pageOffset,
        search_by: this.searchBy,
        search_keyword: this.searchKeyword
      }
      this.searching = true
      getUsers(obj, response => {
        this.searching = false
        if (response.data.status === 'success') {
          this.users = response.data.records
          this.pagination.record_count = response.data.record_count
        }
      }, error => {
        this.searching = false
        this.$toastr.e('', 'Error loading users')
        console.error(error)
      })
    },
    checkClear() {
      this.searchKeyword = null
      this.getUsers()
    },
    selectPage(elm) {
      this.pagination.current_page = elm
      this.getUsers()
    },
    changePerPage() {
      this.pagination.current_page = 1
      this.getUsers()
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
  mounted () {
    this.getUsers()
    this.eventBus.$on('get-users', this.getUsers)
  },
  beforeDestroy () {
    this.eventBus.$off('get-users', this.getUsers)
  }
}
</script>