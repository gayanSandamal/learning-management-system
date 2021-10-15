<template>
  <div class="page-inner">
    <div class="row">
      <div class="col-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4" v-text="lessonId ? 'Update Lesson' : 'Add New Lesson'"></h5>
            <form class="row">
              <div class="col-12 mb-3">
                <label for="name" class="form-label">Lesson title<small class="text-danger">* (required)</small></label>
                <input type="text" class="form-control" id="name" v-model="upload_object.name">
                <span><small>The lesson name is how it appears on your site.</small></span>
              </div>
              <div class="col-12 mb-3">
                <label for="description" class="form-label">Lesson short description</label>
                <textarea class="form-control" id="description" rows="3" v-model="upload_object.desc"></textarea>
                <span><small>Give a short description about the lesson and it will appear on the lesson</small></span>
              </div>
              <div class="col-12 mb-3">
                <div class="row">
                  <div class="col-12">
                    <label for="description" class="form-label">Lesson videos<small class="text-danger">* (required)</small></label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <div class="w-100" v-if="selectedVideo">
                      <iframe class="selected-video" :src="`${selectedVideo.base}${selectedVideo.src}`" frameborder="0" :title="selectedVideo.title"></iframe>
                      <h4 class="" v-text="selectedVideo.title"></h4>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="list-group video-list">
                      <div v-show="!video.deleted" class="list-group-item list-group-item-action vid-list" v-for="(video, index) in upload_object.videos" :key="index">
                        <div class="d-flex w-100 justify-content-between">
                          <input type="text" class="no-form-control" v-model="video.title">
                        </div>
                        <div class="video-frame-wrapper" @click="selectVideo(video)">
                          <iframe class="w-100" :src="`${videoService.base}${video.src}`" frameborder="0" :title="video.title"></iframe>
                          <div class="video-frame-overlay"></div>
                        </div>
                        <button type="button" class="btn-close vid-remove" data-bs-dismiss="modal" aria-label="Close" @click="removeVideo(video)"></button>
                      </div>
                    </div>
                    <div class="list-group-item list-group-item-action d-flex justify-content-center p-0" role="button" @click="lessonId ? $router.push({query: {id: lessonId, modal: 'SelectVideoServiceModal'}}) : $router.push({query: {modal: 'SelectVideoServiceModal'}})">
                      <img :src="require('@/assets/images/placeholders/upload-video.png')" class="img-thumbnail w-100">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-3">
                <label for="start" class="form-label">Publish date</label>
                <datepicker placeholder="Select publish date" v-model="upload_object.start"></datepicker>
                <span><small>Select pubish date to schedule or leave empty</small></span>
              </div>
              <div class="col-12 mb-3">
                <label for="end" class="form-label">Expire date</label>
                <datepicker placeholder="Select expire date" v-model="upload_object.end"></datepicker>
                <span><small>Select expire date to hide after specific date</small></span>
              </div>
              <div class="col-12 mb-3">
                <label for="end" class="form-label">Assign to a date<small class="text-danger">* (required)</small></label>
                <datepicker placeholder="Select expire date" v-model="upload_object.assigned_date"></datepicker>
                <span><small>Pick a date to assign</small></span>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label">Select class<small class="text-danger">* (required)</small></label>
                <treeselect v-model="upload_object.category_id" :multiple="false" :options="mappedCats" />
                <span><small>Assign a class category for this lesson</small></span>
              </div>
            </div>
            <div class="cutsom-fields-wrapper">
              <CustomField :fields="mappedCustomFields"/>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label class="form-label">Video attempts</label>
                <div class="input-group mb-3 d-flex justify-content-between">
                  <input type="number" class="form-control me-3" v-model="upload_object.attempts">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <label for="parent" class="form-label">Thumbnail</label>
                <img :src="upload_object.thumbnail ? upload_object.thumbnail : require('@/assets/images/placeholders/placeholder-image.png')" class="img-thumbnail w-100">
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-3">
                <Button :label="lessonId ? publishing ? 'Updating... ' : 'Update' : publishing ? 'Publishing... ' : 'Publish'" :labelColor="'color-5'" @click="publishOrUpdate">
                  <svg v-if="publishing" class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getUsersByRole, getThumbnailUrl, addPosts, getCustomFields, getPost, getVideos, updatePost } from '@/api/console'
import { getCatsByPostType } from '@/api/postType'
import Treeselect from '@riophae/vue-treeselect'
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
import { videoServices } from '@/assets/scripts/global_variables'
export default {
  name: 'lesson',
  components: {
    Button: () => import('@/components/common/Button'),
    CustomField: () => import('@/components/common/CustomField'),
    Treeselect,
    Datepicker
  },
  data() {
    return {
      upload_object: {
        user_id: this.account.id,
        post_type_id: 1,
        name: null,
        slug: null,
        desc: null,
        assignee_id: null,
        thumbnail: null,
        fee: null,
        attempts: 2,
        videos: [],
        tutes: [
          {
            tute_id: null,
            tuteUploadProgress: 0,
            tute_name: null,
            tute_title: 'Tute 1'
          }
        ],
        start: null,
        end: null,
        assigned_date: null,
        category_id: null
      },
      selectedVideoService: 1,
      videoServices: videoServices,
      selectedVideo: null,
      isSelectingVideo: false,
      instructors: [],
      cats: [],
      publishing: false,
      customFields: []
    }
  },
  props: {
    eventBus: undefined,
    account: undefined,
    baseUrl: undefined
  },
  computed: {
    checkIsLastVideoEmpty() {
      let state = false
      let videos = this.upload_object.videos
      let last_video = videos[videos.length > 0 ? videos.length - 1 : 0]
      if (last_video.file_name) {
        state = true
      }
      return state
    },
    videoService() {
      return this.videoServices.find(o => o.id === this.selectedVideoService)
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
    bodyCustomFields() {
      return this.mappedCustomFields.filter(o => o.position === 1)
    },
    sidebarCustomFields() {
      return this.mappedCustomFields.filter(o => o.position === 2)
    },
    assigneeId () {
      return this.mappedCustomFields.find(o => o.slug === 'assign-instructor').value
    },
    lessonFee () {
      return this.mappedCustomFields.find(o => o.slug === 'lesson-fee').value
    },
    title() {
      return this.upload_object.name
    },
    lessonId () {
      return this.$route.query.id
    },
    isReadyAssignee () {
      return {
        mappedCustomFields: this.mappedCustomFields.length,
        assignee_id: this.upload_object.assignee_id,
        fee: this.upload_object.fee,
      } 
    }
  },
  methods: {
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
    setVideo (obj) {
      if (obj) {
        this.upload_object.videos.push(obj)
        this.getThumbnailUrl()
        if (this.upload_object.videos.length === 1) {
          this.selectVideo (this.upload_object.videos[0])
        }
      }
    },
    selectVideo (video) {
      this.selectedVideo = video
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
    getThumbnailUrl () {
      let id
      if (this.upload_object.videos.length > 0) {
        const video = this.upload_object.videos[this.upload_object.videos.length - 1]
        id = video.src
        if (video.urlPart2) {
          id = `${id}/${video.urlPart2}`
        }
      }
      getThumbnailUrl(id, response => {
        if (response.status === 200) {
          if (this.upload_object.thumbnail === null) {
            this.upload_object.thumbnail = response.data.thumbnail_url
          }
          const video = this.upload_object.videos[this.upload_object.videos.length - 1]
          this.$set(video, 'title', response.data.title)
          this.$set(video, 'duration', response.data.duration)
        }
      }, error => {
        this.$toastr.e('', 'Error loading video details')
        console.error(error)
      })
    },
    removeVideo (video) {
      const vid = this.upload_object.videos.find(o => o.id === video.id)
      const index = this.upload_object.videos.indexOf(vid)
      if (index > -1) {
        this.$set(this.upload_object.videos[index], 'deleted', true)
      }
      const activeVideos = this.upload_object.videos.filter(o => !o.deleted)
      if (this.upload_object.videos.length > 0 && activeVideos.length > 0) {
        this.selectVideo(activeVideos[0])
      } else {
        this.selectVideo(null)
        this.upload_object.thumbnail = null
      }
    },
    getCustomFields () {
      const obj = {
        post_type_id: 1,
        location: 'posts'
      }
      getCustomFields(obj, response => {
        this.customFields = response.data.fields
      }, error => {
        this.$toastr.e('', 'Error loading fields')
        console.error(error)
      })
    },
    publish () {
      if (!this.upload_object.name) {
        this.$toastr.e('', 'Please provide lesson title')
      } else if (!this.upload_object.category_id) {
        this.$toastr.e('', 'Please select a class')
      } else if (!this.upload_object.assigned_date) {
        this.$toastr.e('', 'Please select a date to assign the lesson')
      } else if (!this.assigneeId) {
        this.$toastr.e('', 'Please assign an instructor')
      // } else if (this.lessonFee && this.lessonFee.trim() === '') {
      //   this.$toastr.e('', 'Please provide fee')
      } else if (!this.lessonFee) {
        this.$toastr.e('', 'Please provide lesson fee')
      } else if (this.upload_object.videos.length === 0) {
        this.$toastr.e('', 'Please add lesson videos')
      } else {
        if (this.lessonFee) {
          this.$set(this.upload_object, 'fee', this.lessonFee)
        }
        this.$set(this.upload_object, 'assignee_id', this.assigneeId)
        const start = this.upload_object.start ? moment(this.upload_object.start).format('YYYY-MM-DD 00:00:00') : null
        const end = this.upload_object.end ? moment(this.upload_object.end).format('YYYY-MM-DD 00:00:00') : null
        const assigned_date = this.upload_object.assigned_date ? moment(this.upload_object.assigned_date).format('YYYY-MM-DD 00:00:00') : null
        this.$set(this.upload_object, 'start', start)
        this.$set(this.upload_object, 'end', end)
        this.$set(this.upload_object, 'assigned_date', assigned_date)
        this.upload_object.videos.map((o, i) => {
          this.$set(o, 'display_order', i)
        })
        this.publishing = true
        addPosts(this.upload_object, response => {
          this.publishing = false
          if (response.status === 200) {
            if (this.upload_object.start) {
              this.$toastr.s('', 'Lesson successfully scheduled to publish')
            } else {
              this.$toastr.s('', 'Lesson successfully published')
            }
            this.clear()
          } else {
            this.$toastr.e('', 'Something went wrong')
          }
        }, error => {
          this.publishing = false
          this.$toastr.e('', 'Error while publishing')
          console.error(error)
        })
      }
    },
    clear() {
      this.upload_object = {
        user_id: this.account.id,
        post_type_id: 1,
        name: null,
        slug: null,
        desc: null,
        assignee_id: null,
        thumbnail: null,
        fee: null,
        videos: [],
        tutes: [
          {
            tute_id: null,
            tuteUploadProgress: 0,
            tute_name: null,
            tute_title: 'Tute 1'
          }
        ],
        start: null,
        end: null,
        category_id: null
      }
      this.selectVideo(null)
      this.mappedCustomFields.find(o => o.slug === 'lesson-fee').value = null
      this.mappedCustomFields.find(o => o.slug === 'assign-instructor').value = null
    },
    getPost () {
      const obj = {
        lesson_id: this.lessonId
      }
      getPost(obj, response => {
        this.upload_object.id = response.data.post.id
        this.upload_object.post_type_id = response.data.post.post_type_id
        this.upload_object.name = response.data.post.name
        this.upload_object.slug = response.data.post.slug
        this.upload_object.desc = response.data.post.desc
        this.upload_object.assignee_id = response.data.post.assignee_id
        this.upload_object.thumbnail = response.data.post.thumbnail
        this.upload_object.fee = response.data.post.fee
        this.upload_object.start = response.data.post.start
        this.upload_object.end = response.data.post.end
        this.upload_object.assigned_date = response.data.post.assigned_date
        this.upload_object.category_id = response.data.post.category_id
      }, error => {
        console.error(error)
      })
    },
    getVideos () {
      const obj = {
        lesson_id: this.lessonId
      }
      getVideos(obj, response => {
        this.upload_object.videos = []
        response.data.videos.forEach(o => {
          this.upload_object.videos.push({
            id: o.id,
            title: o.title,
            src: this.setSrc(this.videoService.key, o.src),
            duration: o.duration,
            post_id: o.post_id,
            display_order: o.display_order,
            attempts: o.given_attempts,
            base: this.videoService.base,
            service: this.videoService.key
          })
          this.upload_object.attempts = o.given_attempts
        })
        this.selectVideo (this.upload_object.videos[0])
      }, error => {
        console.error(error)
      })
    },
    setSrc (service, src) {
      let id
      if (service === 'vimeo') {
        id = src.replace('https://player.vimeo.com/video/', '')
      }
      return id
    },
    update () {
      if (!this.upload_object.name) {
        this.$toastr.e('', 'Please provide lesson title')
      } else if (!this.upload_object.category_id) {
        this.$toastr.e('', 'Please select a class')
      } else if (!this.upload_object.assigned_date) {
        this.$toastr.e('', 'Please select a date to assign the lesson')
      } else if (!this.assigneeId) {
        this.$toastr.e('', 'Please assign an instructor')
      // } else if (this.lessonFee && this.lessonFee.trim() === '') {
      //   this.$toastr.e('', 'Please provide fee')
      } else if (!this.lessonFee) {
        this.$toastr.e('', 'Please provide lesson fee')
      } else if (this.upload_object.videos.length === 0) {
        this.$toastr.e('', 'Please add lesson videos')
      } else {
        if (this.lessonFee) {
          this.$set(this.upload_object, 'fee', this.lessonFee)
        }
        this.$set(this.upload_object, 'assignee_id', this.assigneeId)
        const start = this.upload_object.start ? moment(this.upload_object.start).format('YYYY-MM-DD 00:00:00') : null
        const end = this.upload_object.end ? moment(this.upload_object.end).format('YYYY-MM-DD 00:00:00') : null
        const assigned_date = this.upload_object.assigned_date ? moment(this.upload_object.assigned_date).format('YYYY-MM-DD 00:00:00') : null
        this.$set(this.upload_object, 'start', start)
        this.$set(this.upload_object, 'end', end)
        this.$set(this.upload_object, 'assigned_date', assigned_date)
        this.upload_object.videos.map((o, i) => {
          this.$set(o, 'display_order', i)
        })
        this.publishing = true
        updatePost(this.upload_object, response => {
          this.publishing = false
          if (response.status === 200) {
            this.$toastr.s('', 'Lesson successfully updated')
            this.clear()
            this.$router.push({path: '/console/lms/lessons'})
          } else {
            this.$toastr.e('', 'Something went wrong')
          }
        }, error => {
          this.publishing = false
          this.$toastr.e('', 'Error while publishing')
          console.error(error)
        })
      }
    },
    publishOrUpdate () {
      if (this.lessonId) {
        this.update()
      } else {
        this.publish()
      }
    }
  },
  watch: {
    title: {
      handler(val) {
        if (val) {
          this.upload_object.slug = null
          const SPECIALCHARACTERREGEX = /[`~!@#$%^&*()_+{}[\]\\|,.//?;':"]/g
          this.upload_object.slug = val.replace(SPECIALCHARACTERREGEX, '')
          const SPACEREGEX = /\s/g
          this.upload_object.slug = this.upload_object.slug.replace(SPACEREGEX, '-')
          this.upload_object.slug = this.upload_object.slug.toLowerCase()
          this.upload_object.slug = this.upload_object.slug + '-' + moment().format('YYYY-MM-DD-HH-mm')
        } else {
          this.upload_object.slug = null
        }
      }
    },
    isReadyAssignee: {
      immediate: true,
      deep: true,
      handler(val) {
        if (val.fee && val.assignee_id && val.mappedCustomFields > 0) {
          this.mappedCustomFields.find(o => o.slug === 'assign-instructor').value = this.upload_object.assignee_id
          this.mappedCustomFields.find(o => o.slug === 'lesson-fee').value = this.upload_object.fee
        }
      }
    }
  },
  mounted() {
    if (this.lessonId) {
      this.getPost()
      this.getVideos()
    }
    this.getUsersByRole()
    this.getCatsByPostType()
    this.getCustomFields()
    this.eventBus.$on('set-video', this.setVideo)
  },
  beforeDestroy() {
    this.eventBus.$off('set-video', this.setVideo)
  }
}
</script>

<style lang="scss" scoped>
@import '~@riophae/vue-treeselect/dist/vue-treeselect.css';
.remove-icon {
  cursor: pointer;
  margin: 0px 0px 0px 20px;
  transform: scale(1);
  translate: 0.2s;
  svg {
    width: 24px;
    height: 24px;
  }
  &:hover {
    translate: 0.2s;
    transform: scale(1.1);
  }
}

.video-frame-wrapper {
  position: relative;
  .video-frame-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
}
.selected-video {
  width: 100%;
  height: 300px;
}
.video-list {
  max-height: calc(100vh - 300px);
  overflow-y: auto;
}
.cat-check {
  max-height: 300px;
  overflow-y: auto;
}
.vid-list {
  position: relative;
  .vid-remove {
    position: absolute;
    bottom: 0;
    right: 0;
  }
}
</style>