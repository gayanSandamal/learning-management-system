<template>
  <div>
    <div class="modal fade" v-if="editModal.state" :class="{'show': editModal.show, 'd-block': editModal.state}" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @click="closeModal">
      <div class="modal-dialog modal-dialog-scrollable" @click.stop="function(){}">
        <div v-if="user" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit user</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" disabled v-model="user.username">
                  <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="col-6 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" disabled v-model="user.email">
                </div>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="firstname" class="form-label">First name</label>
                  <input type="text" class="form-control" id="firstname" v-model="user.firstname" :disabled="user.roleId === 1026">
                </div>
                <div class="col-6 mb-3">
                  <label for="lastname" class="form-label">Last name</label>
                  <input type="text" class="form-control" id="lastname" v-model="user.lastname" :disabled="user.roleId === 1026">
                </div>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="firstname" class="form-label">Role</label>
                  <select class="form-select form-select-md" id="parent" v-model="user.roleId" :disabled="user.roleId === 1026">
                    <option v-for="role in roles" :key="role.id" v-text="role.roleName" :value="role.roleId" :disabled="role.roleId === 1026"></option>
                  </select>
                </div>
                <div class="col-6 mb-3">
                  <div class="form-check mt-4 pt-3">
                    <input class="form-check-input" type="checkbox" id="is_email_verified" v-model="user.is_email_verified" :disabled="user.roleId === 1026">
                    <label class="form-check-label" for="is_email_verified" v-text="user.is_email_verified ? 'email is verified' : 'email is not verified'"></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="parent_phone" class="form-label">Parent contact number</label>
                  <input type="tel" class="form-control" id="parent_phone" v-model="phones.find(o => o.is_parent).user_phone" disabled>
                </div>
                <div class="col-6 mb-3">
                  <label for="student_phone" class="form-label">Student contact number</label>
                  <input type="tel" class="form-control" id="student_phone" v-model="phones.find(o => !o.is_parent).user_phone" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-4 mb-3">
                  <label for="country" class="form-label">Country</label>
                  <input type="text" class="form-control" id="country" v-model="location.country" disabled>
                </div>
                <div class="col-4 mb-3">
                  <label for="district" class="form-label">District</label>
                  <input type="text" class="form-control" id="district" v-model="location.district" disabled>
                </div>
                <div class="col-4 mb-3">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" id="city" v-model="location.city" disabled>
                </div>
                <div class="col-12 mb-3">
                  <label for="address" class="form-label">Address</label>
                  <textarea class="form-control" id="address" rows="4" v-model="location.address" disabled></textarea>
                </div>
              </div>
              <div v-if="user.roleId === 1024" class="row">
                <div class="col-12 mb-3">
                  <label class="form-label">Select class<small class="text-danger">* (required)</small></label>
                  <treeselect v-model="selectedCategoryIds" :multiple="true" :options="mappedCats" />
                  <span><small>Assign a class category for this lesson</small></span>
                </div>
              </div>
              <div v-if="user.roleId === 1024" class="row">
                <div class="col-12 mb-3">
                  <table class="table table-stripped w-100">
                    <thead>
                      <tr>
                        <th>Class</th>
                        <th>Fee (Rs.)</th>
                        <th>Demo video</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="selectedClass in selectedClasses" :key="selectedClass.id">
                        <td v-text="selectedClass.name">
                          <label :for="`fee-${selectedClass.id}`"></label>
                        </td>
                        <td>
                          <input type="text" class="form-control" :id="`fee-${selectedClass.id}`" v-model="selectedClass.fee" placeholder="Example: 500">
                        </td>
                        <td>
                          <input v-if="selectedClass.src" type="text" class="form-control" :id="`fee-${selectedClass.id}`" v-model="selectedClass.src" placeholder="Example: 500" @input="setSrc(selectedClass.id, selectedClass.src)">
                          <textarea v-else class="form-control" rows="1" v-model="selectedClass.src" @input="setSrc(selectedClass.id)" placeholder="Paste demo video embed url"></textarea>
                          <div v-show="false" :id="`reference-${selectedClass.id}`" v-html="selectedClass.src"></div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="cutsom-fields-wrapper">
                <CustomField :fields="mappedCustomFields"/>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <Button :label="'Close'" class="text-secondary" @click="closeModal"/>
            <Button :label="'Save changes'" :labelColor="'color-5'" @click="updateUser"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getUser, updateUser, getCustomFields, getAssignedClasses } from '@/api/console'
import { getCatsByPostType } from '@/api/postType'
import Treeselect from '@riophae/vue-treeselect'
export default {
  name: 'edit-user-modal',
  data () {
    return {
      user: undefined,
      roles: [],
      phones: [],
      location: undefined,
      cats: [],
      selectedCategoryIds: [],
      selectedCategories: [],
      customFields: [],
      selectedClasses: []
    }
  },
  props: {
    eventBus: undefined,
    account: undefined,
    editModal: undefined
  },
  components: {
    Button: () => import('@/components/common/Button'),
    CustomField: () => import('@/components/common/CustomField'),
    Treeselect
  },
  computed: {
    userId() {
      return this.$route.query.userId
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
    mappedCustomFields() {
      const arr = []
      this.customFields.map(o => {
        if (o.type === 2) {
          this.$set(o, 'list', this.mappedInstructors)
        }
        arr.push(o)
      })
      return arr
    },
    selectedCategoriesLength() {
      return {
        ids: this.selectedCategoryIds.length,
        catLength: this.selectedCategories.length,
        catsLength: this.cats.length
      }
    }
  },
  methods: {
    getUser() {
      const obj = {
        id: this.account.id,
        user_id: this.userId
      }
      getUser(obj, response => {
        if (response.data.status === 'success') {
          this.user = response.data.user
          this.roles = response.data.roles
          this.phones = response.data.phones
          this.location = response.data.locations
        }
      }, error => {
        this.$toastr.e('', 'Error loading user')
        console.error(error)
      })
    },
    updateUser() {
      if (!this.user.firstname) {
        this.$toastr.e('', 'Please provide first name')
      } else if (!this.user.lastname) {
        this.$toastr.e('', 'Please provide last name')
      } else if (this.selectedClasses.length > 0 && this.selectedClasses.filter(o => !o.fee).length > 0) {
        this.$toastr.e('', 'Please provide class fee for selected classes')
      } else {
        const obj = {
          id: this.account.id,
          user_id: this.user.id,
          firstname: this.user.firstname,
          lastname: this.user.lastname,
          roleId: this.user.roleId,
          is_email_verified: this.user.is_email_verified,
          classes: this.selectedClasses
        }
        updateUser(obj, response => {
          if (response.data.status === 'success') {
            this.$toastr.s('', 'Successfully updated')
            this.eventBus.$emit('get-users')
            this.closeModal()
          }
        }, error => {
          this.$toastr.e('', 'Error updating')
          console.error(error)
        })
      }
    },
    closeModal() {
      this.$emit('modal-state', false)
    },
    getCustomFields () {
      const obj = {
        post_type_id: 1,
        location: 'user-edit'
      }
      getCustomFields(obj, response => {
        this.customFields = response.data.fields
      }, error => {
        this.$toastr.e('', 'Error loading fields')
        console.error(error)
      })
    },
    getCatsByPostType() {
      const obj = {
        post_type_id: 1,
        order_by: 'display_order',
        order: 'ASC',
        per_page: 1000,
        offset: 0,
        search_by: 0,
        search_keyword: ''
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
    getAssignedClasses() {
      const obj = {
        post_type_id: 1,
        user_id: this.userId
      }
      getAssignedClasses(obj, response => {
        if (response.data.cats.length > 0) {
          this.selectedCategoryIds = response.data.cats.map(o => o.category_id)
          this.selectedCategories = JSON.parse(JSON.stringify(response.data.cats))
        }
      }, error => {
        console.error(error)
      })
    },
    mapSelectedClasses() {
      this.selectedClasses = []
      const clone = JSON.parse(JSON.stringify(this.cats))
      clone.filter(o => {
        if (this.selectedCategoryIds.indexOf(o.id) > -1) {
          if (this.selectedCategories.length > 0) {
            this.selectedCategories.map((p, i) => {
              if (p.category_id === o.id) {
                this.$set(o, 'fee', p.fee)
                this.$set(o, 'src', p.src)
              }
              if (i === this.selectedCategories.length - 1) {
                this.selectedClasses.push(o)
              }
            })
          } else {
            this.selectedClasses.push(o)
          }
        }
      })
    },
    setSrc(class_id) {
      setTimeout(() => {
        const reference = document.querySelector(`#reference-${class_id}`)
        if (reference) {
          const iframe = reference.children[0]
          const src = iframe.getAttribute('src')
          let id
          if (src.includes('?')) {
            const cleanUrl = src.split('?')
            id = cleanUrl[0].replace('https://player.vimeo.com/video/', '')
          } else {
            id = src.replace('https://player.vimeo.com/video/', '')
          }
          const item = this.selectedClasses.find(o => o.id === class_id)
          this.$set(item, 'src', id)
        }
      }, 500)
    }
  },
  watch: {
    selectedCategoriesLength: {
      immediate: true,
      deep: true,
      handler() {
        // if (val.length > 0) {
          this.mapSelectedClasses()
        // }
      }
    }
  },
  mounted() {
    this.getUser()
    this.getCatsByPostType()
    this.getAssignedClasses()
    // this.getCustomFields()
  }
}
</script>

<style lang="scss" scoped>
@import '~@riophae/vue-treeselect/dist/vue-treeselect.css';
</style>