<template>
  <div class="w-100">
    <div class="row">
      <div class="col-md-4 mb-4" v-for="cat in orderedCats" :key="cat.display_order">
        <div class="card card-curved px-3 shadow">
          <div class="card-body">
            <h5 class="card-title text-center mb-3" v-text="cat.name"></h5>
            <img class="banner-side-image w-100 d-block" :src="require(`@/assets/images/home/classes/${thumbnail}`)"/>
            <div class="w-100 d-flex justify-content-center">
              <Button class="mt-3" :label="cat.loading ? 'Loading...' : 'View class'" :labelColor="'color-8'" @click="getClasses(cat)"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="account && account.id" class="w-100">
      <div class="row">
        <div class="col-md-3 mb-4" v-for="item in classes" :key="item.id">
          <div class="px-4">
            <img class="banner-side-image w-100 d-block" :src="require(`@/assets/images/placeholders/profile-image-placeholder.png`)"/>
          </div>
          <div class="shadow bg-white rounded-3">
            <div class="card-body mx-2">
              <div class="w-100 mb-3">
                <h4 class="profile-name card-title text-start mb-1"><strong v-text="item.firstname"></strong></h4>
                <h4 class="profile-name card-title text-start mb-0"><strong v-text="item.lastname"></strong></h4>
              </div>
              <div class="w-100 d-flex justify-content-between">
                <p class="username-tag d-flex align-items-center">
                  <svg version="1.1" class="location-marker me-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 35.219 35.219" style="enable-background:new 0 0 35.219 35.219;" xml:space="preserve">
                    <g>
                      <path d="M17.612,0C11.005,0,5.648,5.321,5.648,11.885c0,3.358,3.294,9.374,3.294,9.374l8.229,13.96l8.586-13.797
                        c0,0,3.814-5.74,3.814-9.537C29.572,5.321,24.216,0,17.612,0z M17.556,18.431c-3.784,0-6.849-3.065-6.849-6.853
                        c0-3.783,3.064-6.846,6.849-6.846c3.782,0,6.85,3.063,6.85,6.846C24.406,15.366,21.338,18.431,17.556,18.431z"/>
                    </g>
                  </svg>
                  <small v-text="item.city"></small>
                </p>
                <p class="username-tag"><small v-text="`@${item.username}`"></small></p>
              </div>
              <div v-if="!isEnrolled(item)" class="w-100 d-flex justify-content-center">
                <Button class="mt-3" :label="'Watch Introduction'" :labelColor="'color-3'" @click="setModalState(true, item.src)"/>
              </div>
              <div class="w-100 d-flex justify-content-center mt-1">
                <Button v-if="isEnrolled(item)" class="mt-3" :label="'Watch lessons'" :labelColor="'color-5'" @click="msg"/>
                <Button v-else class="mt-3" :label="'Join class now'" :labelColor="'color-5'" @click="payments(item)"/>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="w-100" v-if="classes.length > 0">
        <AppDownloadSection/>
      </div>
    </div>
    <div v-else class="d-flex w-100 justify-content-center">
      <div class="text-center">
        <p>You are not logged in. Please log in to view our available classes</p>
        <Button :label="'Log in'" @click="$router.push({path: '/login', query: {to: currentRoute}})"/>
      </div>
    </div>
    <VideoModal :editModal="editModal" @modal-state="setModalState(false)"/>
  </div>
</template>

<script>
import { getClassGroups, getAvailableClasses, getEnrolledClasses } from '@/api/console'
export default {
  name: 'class-groups',
  props: {
    account: undefined,
    thumbnail: {
      type: String
    }
  },
  data() {
    return {
      cats: [],
      classes: [],
      enrolled_classes: [],
      editModal: {
        state: false,
        show: false,
        id: null
      },
      loading: false
    }
  },
  components: {
    Button: () => import('@/components/common/Button'),
    VideoModal: () => import ('@/components/modals/VideoModal'),
    AppDownloadSection: () => import('@/components/common/AppDownloadSection')
  },
  computed: {
    catId() {
      return this.$route.query.class
    },
    orderedCats() {
      return this.cats.sort((a,b) => a.display_order - b.display_order) // eslint-disable-line
    },
    currentRoute() {
      return this.$route.fullPath
    }
  },
  methods: {
    getClassGroups() {
      const obj = {
        post_type_id: 1,
        category_id: this.catId
      }
      getClassGroups(obj, response => {
        this.cats = response.data.cats
      }, error => {
        this.$toastr.e('', error.response.data.message)
        console.error(error)
      })
    },
    getAvailableClasses() {
      this.classes = []
      const obj = {
        post_type_id: 1,
        category_id: this.catId
      }
      this.loading = true
      getAvailableClasses(obj, response => {
        this.loading = false
        if (response.data.classes.length > 0) {
          this.classes = response.data.classes
        }
      }, error => {
        this.loading = false
        console.error(error)
      })
    },
    getEnrolledClasses() {
      this.enrolled_classes = []
      const obj = {
        user_id: this.account.id
      }
      this.loading = true
      getEnrolledClasses(obj, response => {
        this.loading = false
        if (response.data.classes.length > 0) {
          this.enrolled_classes = response.data.classes
        }
      }, error => {
        this.loading = false
        console.error(error)
      })
    },
    setModalState(state, id) {
      if (id) {
        this.editModal.id = id
      } else {
        this.editModal.id = null
      }
      if (state) {
        this.editModal.state = state
        setTimeout(() => {
          this.editModal.show = state
        }, 50)
      } else {
        this.editModal.show = state
        setTimeout(() => {
          this.editModal.state = state
        }, 500)
      }
    },
    getClasses(cat) {
      const selectedCat = this.cats.find(o => o.id === cat.id)
      this.$set(selectedCat, 'loading', true)
      this.$router.push({query: {class: cat.id}})
    },
    payments(item) {
      const query = {
        user_id: this.account.id,
        assignee_id: item.user_id,
        post_type_id: item.post_type_id,
        enrollment_mode: 1,
        cat_id: item.category_id,
        item_id: item.id,
      }
      this.$router.push({path: '/payments', query: query})
    },
    isEnrolled(item) {
      const elm = this.enrolled_classes.find(o => o.assignee_id === item.user_id && o.item_id === item.category_id)
      return elm && elm.id ? true : false
    },
    msg() {
      alert('Video centre is under development. Download our app to watch lessons')
    }
  },
  watch: {
    catId: {
      immediate: true,
      deep: true,
      handler() {
        if (this.account && this.account.id) {
          this.getClassGroups()
          this.getAvailableClasses()
          this.getEnrolledClasses()
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.banner-side-image {
}
.profile-name {
  letter-spacing: 2px;
  text-transform: uppercase; 
}
.location-marker {
  width: 16px;
}
.username-tag {
  text-transform: lowercase;
}
</style>