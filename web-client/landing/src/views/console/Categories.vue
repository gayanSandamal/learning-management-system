<template>
  <div class="page-inner">
    <div class="row w-100">
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4">Add New Class Category</h5>
            <form class="row">
              <div class="col-12 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" v-model="form.name">
                <span><small>The name is how it appears on your site.</small></span>
              </div>
              <div class="col-12 mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" v-model="form.slug">
                <span><small>The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></span>
              </div>
              <div class="col-12 mb-3">
                <label for="parent" class="form-label">Parent category</label>
                <treeselect v-model="form.parent_category_id" :multiple="false" :options="mappedCatsClean" />
                <!-- <select class="form-select" id="parent" v-model="form.parent_category_id">
                  <option  :value="null">None</option>
                  <option v-for="category in catsClean" :key="category.id" v-text="`${category.name} (${category.slug})`" :value="category.id"></option>
                </select> -->
                <span><small>Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.</small></span>
              </div>
              <div class="col-12 mb-3" v-if="postType === 1">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_email_verified" v-model="form.is_show">
                    <label class="form-check-label" for="is_email_verified">Show on registration menu</label>
                  </div>
              </div>
              <div class="col-12 mb-3">
                <Button :label="'Add new category'" :labelColor="'color-5'" @click="addCategory($event)" :disabled="disabledAdd"/>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Categories</h5>
            <div class="row">
              <div class="col-12">
                <div class="input-group mb-3">
                  <input type="text" class="form-control me-3" :placeholder="'Search by category name'" v-model="searchKeyword">
                  <Button class="me-3" v-if="searchKeyword && searchKeyword.trim() !== ''" :label="'Clear'" :labelColor="'color-3'" @click="checkClear"></Button>
                  <Button :label="searching ? '' : 'Search'" :labelColor="'color-5'" @click="getCatsByPostType">
                    <svg v-if="searching" class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
                  </Button>
                </div>
              </div>
            </div>
            <table class="table w-100">
              <tbody>
                <tr>
                  <td class="w-100">
                    <TableNoHeader :categories="mappedCats"/>
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
import { getCatsByPostType, addCatsByPostType } from '@/api/postType'
import Treeselect from '@riophae/vue-treeselect'
export default {
  name: 'categories',
  data() {
    return {
      cats: [],
      catsClean: [],
      form: {
        name: null,
        slug: null,
        parent_category_id: null,
        post_type_id: this.postType,
        // is_show: this.$route.query.post_type == '1' ? true : false
        is_show: false
      },
      searchKeyword: null,
      searching: false,
      pagination: {
        per_page: 50,
        record_count: 0,
        current_page: 1,
        pages: [50, 100, 200, 300, 400, 500]
      }
    }
  },
  props: {
    eventBus: undefined
  },
  components: {
    Button: () => import('@/components/common/Button'),
    TableNoHeader: () => import('@/components/common/TableNoHeader'),
    Treeselect
  },
  computed: {
    postType() {
      return parseInt(this.$route.params.post_type)
    },
    mappedCats() {
      let mappedCats = []
      this.cats.forEach(o => {
        if (o.parent_category_id) {
          this.cats.forEach(p => {
            if (!p.categories) {
              this.$set(p, 'categories', [])
            }
            if (o.parent_category_id === p.id) {
              p.categories.push(o)
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
          addDepth(obj.categories, depth + 1)
        })
      }
      addDepth(mappedCats)
      return mappedCats
    },
    formName() {
      return this.form.name
    },
    disabledAdd() {
      let state = true
      if (this.form.name && this.form.name.trim() !== '') {
        state = false
      }
      return state
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
      this.catsClean.map(o => {
        this.$set(o, 'label', `${o.name} (${o.slug})`)
        arr.push(o)
      })
      return arr
    }
  },
  methods: {
    getCatsByPostType() {
      const obj = {
        post_type_id: this.postType,
        order_by: 'display_order',
        order: 'ASC',
        per_page: this.pagination.per_page,
        offset: this.pageOffset,
        search_by: 0,
        search_keyword: this.searchKeyword
      }
      getCatsByPostType(obj, response => {
        if (response.data.status === 'success') {
          this.cats = response.data.cats
          this.catsClean = JSON.parse(JSON.stringify(response.data.cats))
          this.pagination.record_count = response.data.record_count
        }
      }, error => {
        this.$toastr.e('', 'Error loading cats')
        console.error(error)
      })
    },
    addCategory(event) {
      event.preventDefault()
      if (!this.disabledAdd) {
        const obj = {
          post_type_id: this.postType,
          parent_id: this.form.parent_category_id,
          name: this.form.name,
          slug: this.form.slug,
          is_show: this.postType ? this.form.is_show ? 1 : 0 : undefined
        }
        addCatsByPostType(obj, () => {
          this.clearForm()
          this.getCatsByPostType()
        }, error => {
          console.error(error)
        })
      }
    },
    clearForm() {
      this.form = {
        name: null,
        slug: null,
        parent_category_id: null,
        post_type_id: this.postType
      }
    },
    checkClear() {
      this.searchKeyword = null
      this.getCatsByPostType()
    },
    selectPage(elm) {
      this.pagination.current_page = elm
      this.getCatsByPostType()
    },
    changePerPage() {
      this.pagination.current_page = 1
      this.getCatsByPostType()
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
  watch: {
    formName: {
      handler(val) {
        if (val) {
          this.form.slug = null
          const SPECIALCHARACTERREGEX = /[`~!@#$%^&*()_+{}[\]\\|,.//?;':"]/g
          this.form.slug = val.replace(SPECIALCHARACTERREGEX, '')
          const SPACEREGEX = /\s/g
          this.form.slug = this.form.slug.replace(SPACEREGEX, '-')
          this.form.slug = this.form.slug.toLowerCase()
        } else {
          this.form.slug = null
        }
      }
    }
  },
  mounted () {
    this.getCatsByPostType()
    this.eventBus.$on('get-cats', this.getCatsByPostType)
  },
  beforeDestroy () {
    this.eventBus.$off('get-cats', this.getCatsByPostType)
  }
}
</script>

<style lang="scss" scoped>
@import '~@riophae/vue-treeselect/dist/vue-treeselect.css';
</style>