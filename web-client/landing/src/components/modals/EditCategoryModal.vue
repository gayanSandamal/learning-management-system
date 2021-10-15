<template>
  <div>
    <div class="modal fade" v-if="editModal.state" :class="{'show': editModal.show, 'd-block': editModal.state}" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @click="closeModal">
      <div class="modal-dialog modal-dialog-scrollable" @click.stop="function(){}">
        <div v-if="category" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="username" class="form-label">Name</label>
                  <input type="text" class="form-control" id="username" v-model="category.name" @input="setSlugName(category.name)">
                  <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="col-6 mb-3">
                  <label for="email" class="form-label">Slug</label>
                  <input type="email" class="form-control" id="email" v-model="category.slug">
                </div>
              </div>
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="firstname" class="form-label">Parent category</label>
                  <select class="form-select form-select-md" id="parent" v-model="category.parent_category_id">
                    <option  :value="null">None</option>
                    <option v-for="cat in cats" :key="cat.id" v-text="`${cat.name} (${cat.slug})`" :value="cat.id"></option>
                  </select>
                </div>
              </div>
              <div class="col-12 mb-3" v-if="postType === 1">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="is_email_verified" v-model="category.is_show">
                  <label class="form-check-label" for="is_email_verified">Show on registration menu</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <Button :label="'Delete permanently'" class="text-white bg-danger" @click="deleteCategoryPermanently"/>
            <div>
              <Button :label="'Close'" class="text-secondary" @click="closeModal"/>
              <Button class="ms-2" :label="'Save changes'" :labelColor="'color-5'" @click="updateCategory"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getCategory } from '@/api/console'
import { getCatsByPostType, updateCategory, deleteCategoryPermanently } from '@/api/postType'
export default {
  name: 'edit-category-modal',
  data () {
    return {
      category: undefined,
      cats: []
    }
  },
  props: {
    eventBus: undefined,
    account: undefined,
    editModal: undefined
  },
  components: {
    Button: () => import('@/components/common/Button')
  },
  computed: {
    categoryId() {
      return this.$route.query.category_id
    },
    postType() {
      return parseInt(this.$route.query.post_type)
    },
    formName() {
      return this.category.name
    }
  },
  methods: {
    getCategory() {
      const obj = {
        id: this.account.id,
        category_id: this.categoryId,
        post_type_id: this.postType
      }
      getCategory(obj, response => {
        if (response.data.status === 'success') {
          this.category = response.data.category
          this.setSlugName()
        }
      }, error => {
        this.$toastr.e('', 'Error loading category')
        console.error(error)
      })
    },
    updateCategory() {
      const obj = {
        id: this.account.id,
        category_id: this.categoryId,
        post_type_id: this.postType,
        name: this.category.name,
        slug: this.category.slug,
        parent_id: this.category.parent_category_id,
        is_show: this.category.is_show
      }
      updateCategory(obj, response => {
        if (response.data.status === 'success') {
          this.$toastr.s('', 'Successfully updated')
          this.eventBus.$emit('get-cats')
          this.closeModal()
        }
      }, error => {
        this.$toastr.e('', error.response.data)
        console.error(error)
      })
    },
    deleteCategoryPermanently() {
      const obj = {
        id: this.account.id,
        category_id: this.categoryId
      }
      deleteCategoryPermanently(obj, response => {
        if (response.data.status === 'success') {
          this.$toastr.s('', 'Successfully deleted')
          this.closeModal()
        }
      }, error => {
        this.$toastr.e('', error.response.data)
        console.error(error)
      })
    },
    closeModal() {
      this.$emit('modal-state', false)
    },
    getCatsByPostType() {
      const obj = {
        post_type_id: this.postType,
        order_by: 'display_order',
        order: 'ASC'
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
    setSlugName(val) {
      this.category.slug = null
      const SPECIALCHARACTERREGEX = /[`~!@#$%^&*()_+{}[\]\\|,.//?;':"]/g
      this.category.slug = val ? val.replace(SPECIALCHARACTERREGEX, '') : this.category.name.replace(SPECIALCHARACTERREGEX, '')
      const SPACEREGEX = /\s/g
      this.category.slug = this.category.slug.replace(SPACEREGEX, '-')
      this.category.slug = this.category.slug.toLowerCase()
    }
  },
  watch: {
    formName: {
      handler(val) {
        if (val) {
          this.setSlugName(val)
        } else {
          this.category.slug = null
        }
      }
    }
  },
  mounted() {
    this.getCatsByPostType()
    this.getCategory()
  }
}
</script>