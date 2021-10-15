<template>
  <div>
    <div class="modal fade" v-if="editModal.state" :class="{'show': editModal.show, 'd-block': editModal.state}" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @click="closeModal">
      <div class="modal-dialog modal-dialog-scrollable" @click.stop="function(){}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <ul class="nav nav-tabs">
              <li class="nav-item" v-for="service in videoServices" :key="service.id">
                <a class="nav-link text-secondary" role="button" :class="{'active': selectedVideoService === service.id}" v-text="service.label" @click="selectedVideoService = service.id"></a>
              </li>
            </ul>
            <div class="form">
              <div class="row">
                <div class="col-12 mt-3 mb-3">
                  <label for="address" class="form-label">Paste the {{videoService.label}} <strong>embedded</strong> code or just <strong>video url</strong><span class="fst-italic"><small>(Recommended)</small></span> here</label>
                  <textarea class="form-control" id="address" rows="3" v-model="embeddedUrl" autofocus @keyup.enter="setVideo"></textarea>
                  <div v-show="false" ref="reference" v-html="embeddedUrl"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <Button :label="'Close'" class="text-secondary" @click="closeModal"/>
            <Button :label="!searching ? 'Add video' : ''" :labelColor="'color-5'" @click="setVideo">
              <svg v-if="searching" class="loader" enable-background="new 0 0 497 497" viewBox="0 0 497 497" width="24" xmlns="http://www.w3.org/2000/svg"><g><circle cx="98" cy="376" fill="#909ba6" r="53"/><circle cx="439" cy="336" fill="#c8d2dc" r="46"/><circle cx="397" cy="112" fill="#e9edf1" r="38"/><ellipse cx="56.245" cy="244.754" fill="#7e8b96" rx="56.245" ry="54.874"/><ellipse cx="217.821" cy="447.175" fill="#a2abb8" rx="51.132" ry="49.825"/><ellipse cx="349.229" cy="427.873" fill="#b9c3cd" rx="48.575" ry="47.297"/><ellipse cx="117.092" cy="114.794" fill="#5f6c75" rx="58.801" ry="57.397"/><ellipse cx="453.538" cy="216.477" fill="#dce6eb" rx="43.462" ry="42.656"/><circle cx="263" cy="62" fill="#4e5a61" r="62"/></g></svg>
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { videoServices } from '@/assets/scripts/global_variables'
export default {
  name: 'video-service-modal',
  data () {
    return {
      url: null,
      selectedVideoService: 1,
      videoServices: videoServices,
      embeddedUrl: null,
      searching: false
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
    videoService() {
      return this.videoServices.find(o => o.id === this.selectedVideoService)
    },
    lessonId () {
      return this.$route.query.id
    }
  },
  methods: {
    setVideo() {
      this.searching = true
      setTimeout(() => {
        const reference = this.$refs.reference.children[0]
        let src
        let title
        let part2
        if (reference) {
          src = reference.getAttribute('src')
          title = reference.getAttribute('title')
        } else {
          src = this.embeddedUrl.replace('https://vimeo.com/', '')
          src = src.split("/")
          part2 = src.filter((o, i) => {
            if (i > 0) {
              return o
            }
          })
          part2 = part2.join('/')
          src = src[0]
        }
        const obj = {
          title: title ? title : 'Lesson video',
          src: this.setSrc(this.videoService.key, src),
          base: this.videoService.base,
          service: this.videoService.key,
          urlPart2: part2
        }
        this.eventBus.$emit('set-video', obj)
        this.searching = false
        this.closeModal()
      }, 1000)
    },
    setSrc (service, src) {
      let id
      if (service === 'vimeo') {
        if (src.includes('?')) {
          const cleanUrl = src.split('?')
          id = cleanUrl[0].replace('https://player.vimeo.com/video/', '')
        } else {
          id = src.replace('https://player.vimeo.com/video/', '')
        }
      }
      return id
    },
    closeModal() {
      if (this.lessonId) {
        this.$router.push({query: {id: this.lessonId, modal: undefined}})
      } else {
        this.$router.push({query: {id: this.lessonId, modal: undefined}})
      }
      this.eventBus.$emit('fade-out-modal')
    }
  },
  mounted() {
  }
}
</script>