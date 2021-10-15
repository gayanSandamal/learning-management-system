<template>
  <div class="video-uploader-wrapper">
    <div class="row">
      <div class="col-md-5" v-if="!lessonVideoUrl">
        <div class="form-group mb-2">
          <label :for="'videoUploader' + index" class="mb-0 w-100">
            <img :src="require('@/assets/images/placeholders/upload-video.png')" alt="..." class="img-thumbnail w-100">
          </label>
          <input type="file" class="form-control-file d-none" :id="'videoUploader' + index" accept=".mp4">
        </div>
      </div>
      <div class="col-md-5" v-if="lessonVideoUrl">
        <div class="form-group mb-0">
          <div class="embed-responsive embed-responsive-16by9">
            <video :key="lessonVideoUrl" controls class="embed-responsive-item lessonVideoPreview" controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture preload>
              <source :src="lessonVideoUrl" type="video/mp4">
              Sorry, your browser doesn't support embedded videos.
            </video>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <input type="text" class="form-control mb-2" id="videoTitle" placeholder="Episode name" v-model="video.episode_name">
        <div class="progress mb-0 mt-0" v-if="video.videoUploadProgress !== 0">
          <div class="progress-bar" :class="{'bg-success': video.videoUploadProgress >= 100, 'progress-bar-striped' : video.videoUploadProgress < 100}" :style="{'width': video.videoUploadProgress + '%'}" v-text="progressText"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Resumable from 'resumablejs'
export default {
  name: 'video-upload',
  data() {
    return {
      r: undefined
    }
  },
  props: {
    eventBus: undefined,
    video: {
      type: Object
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    progressText() {
      let text
      if (this.video.videoUploadProgress < 100) {
        text = this.video.videoUploadProgress + '%'
      } else {
        text = 'Successfully uploaded'
      }
      return text
    },
    lessonVideoUrl () {
      let url
      if (this.video.file_name) {
        url = '/uploads/lessons/videos/' + this.video.file_name
      }
      return url
    }
  },
  methods: {
    initiResumable() {
      this.r = new Resumable({
        target: process.env.VUE_APP_PUBLIC_PATH+'/ks-api/api/objects/upload/video-lesson/',
        chunkSize: 5*1024*1024,
        simultaneousUploads: 1,
        testChunks: false,
        throttleProgressCallbacks: 1,
        fileType: ['mp4']
      })
      /*eslint-disable*/
      this.r.on('fileAdded', (file, event) => {
        this.videoUploadProgress = 0
        setTimeout(() => {
          this.r.upload()
        }, 1000)
      })
      this.r.on('fileSuccess', (file, message) => {
        let message_object = JSON.parse(message)
        this.video.file_name = message_object.object.file_name
        this.video.video_id = message_object.object.id
        this.eventBus.$emit('check-for-add-more-videos')
        // this.clearVideo()
      })
      this.r.on('fileError', (file, message) => {
        console.error('error', message)
        this.clearVideo()
      })
      this.r.on('fileProgress', (file, message) => {
        this.video.videoUploadProgress = Math.floor(this.r.progress()*100)
      })
    }
  },
  mounted() {
    this.initiResumable()
    if (!this.lessonVideoUrl) {
      let isUploader = setInterval(() => {
        let uploader = document.getElementById('videoUploader' + this.index)
        if (uploader) {
          clearInterval(isUploader)
          this.r.assignBrowse(uploader)
        }
      }, 50)
    }
  }
}
</script>

<style lang="scss" scoped>
.lessonVideoPreview,
.video-uploader-wrapper {
  width: 100%;
}
</style>