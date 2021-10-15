<template>
  <div class="bg-dark-app">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mb-5 mb-lg-0">
          <div v-if="selectedVideo && currentVideoAttempt > 0" class="w-100">
            <iframe v-show="playerIframe" id="vimeo-player" class="player-iframe" :key="selectedVideo.src" :src="`https://player.vimeo.com/video/${selectedVideo.src}?title=0&byline=1&portrait=0&sidedock=0`" frameborder="0" allowfullscreen></iframe>
            <div v-if="playerIframe" class="d-flex flex-wrap justify-content-between">
              <h3 class="display-6 w-100 text-white fs-5 mt-2" v-text="selectedVideo.title.replace('.mp4', '')"></h3>
              <div class="d-flex align-items-center justify-content-between w-100">
                <p class="text-white fw-light mb-0 d-flex align-items-center">
                  <span class="icon-svg me-2" v-html="tapIcon"></span>
                  <small v-text="`Available attempts ( ${availableAttempts(selectedVideo)} )`"></small>
                </p>
                <p class="text-white fw-light mb-0 d-flex align-items-center">
                  <span class="icon-svg me-2" v-html="clock"></span>
                  <small v-text="`Duration ( ${duration(selectedVideo.duration)} )`" :key="refreshAttemptElement"></small>
                </p>
              </div>
            </div>
          </div>
          <div v-if="currentVideoAttempt === 0" class="card text-white bg-danger mb-3 w-100 py-5">
            <div class="card-body">
              <h5 class="card-title">You have no attempts available</h5>
              <p class="card-text">You do not have attempts for this video</p>
            </div>
          </div>
          <content-placeholders class="mb-5" v-if="!selectedVideo || !playerIframe && currentVideoAttempt > 0">
            <content-placeholders-img class="video-placeholder"/>
            <content-placeholders-text :lines="1"/>
          </content-placeholders>
        </div>
        <!-- <p class="text-white">{{vimeoPlayer.progress}}</p> -->
        <div class="col-lg-4">
          <ul v-if="videos.length > 0" class="list-unstyled">
            <li v-for="(video, index) in videos" :key="video.id" class="mb-3 pb-2" :class="{'border-bottom': index < (videos.length - 1)}">
              <div class="row" @click="selectVideo(video)" role="button">
                <div class="col-4 col-lg-5">
                  <img class="w-100 rounded-3 shadow" :src="lesson.post_thumbnail" :alt="video.title">
                </div>
                <div class="col-8 col-sm-7 ps-0 ps-lg-0">
                  <p class="text-white fw-light mb-1 d-flex align-items-center">
                    <span v-if="video.src === selectedVideo.src" class="icon-svg me-2" v-html="playIcon"></span>
                    <small class="post-title" v-text="video.title.replace('.mp4', '')"></small>
                  </p>
                  <p class="text-white fw-light mb-1 d-flex align-items-center">
                    <span class="icon-svg me-2" v-html="clock"></span>
                    <small v-text="duration(video.duration)"></small>
                  </p>
                  <p class="text-white fw-light mb-0 d-flex align-items-center">
                    <span class="icon-svg me-2" v-html="tapIcon"></span>
                    <small v-text="availableAttempts(video)" :key="refreshAttemptElement"></small>
                  </p>
                </div>
                <!-- <pre>{{video}}</pre> -->
              </div>
            </li>
          </ul>
          <ul v-else class="list-unstyled">
            <li class="mb-3" v-for="item in 3" :key="item">
              <content-placeholders>
                <content-placeholders-heading :img="true" />
              </content-placeholders>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import Player from '@vimeo/player'
import { appAuth } from '@/api/auth'
import { getLesson, initAttempts, setAttempts } from '@/api/postType'
import { clock, play, tap } from '@/assets/scripts/svg'
export default {
  name: 'watch-lessons',
  components: {
  },
  data () {
    return {
      selectedVideo: null,
			options: {
				muted: false,
        autoplay: false,
        controls: true
			},
			playerReady: false,
      vimeoPlayer: {
        progress: {
          duration: 0,
          percent: 0,
          seconds: 0,
          completion: 0
        }
      },
      videos: [],
      attempts: [],
      lesson: null,
      playerIframe: undefined,
      attemptOver: false,
      user_id: null,
      refreshAttemptElement: 0
    }
  },
  computed: {
    clock () {
      return clock
    },
    playIcon () {
      return play
    },
    tapIcon () {
      return tap
    },
    username () {
      return this.$route.query.username
    },
    token () {
      return this.$route.query.token
    },
    itemId () {
      return parseInt(this.$route.query.itemId)
    },
    postId () {
      return parseInt(this.$route.query.postId)
    },
    currentVideoAttempt () {
      let attempts = 1
      if (this.selectedVideo && this.attempts.length > 0 && !this.attemptOver) {
        const item = this.attempts.find(o => o.video_id === this.selectedVideo.id)
        attempts = item.attempts
      }
      return attempts
    }
  },
  methods: {
    availableAttempts (video) {
      const item = this.attempts.find(o => o.video_id === video.id)
      return `${item.attempts} out of ${video.attempts} attempts`
    },
    onReady() {
			this.playerReady = true
		},
		play () {
			this.$refs.player.play()
		},
		pause () {
			this.$refs.player.pause()
		},
    progress () {
      let seconds = this.vimeoPlayer.progress.seconds + 1
      this.vimeoPlayer.progress.seconds = seconds
    },
    ended (val) {
      console.log(val)
    },
    authenticate () {
      const obj = {
        username: this.username,
        token: this.token,
        itemId: this.itemId
      }
      appAuth(obj, response => {
        this.user_id = response.data.user_id
        const obj = {
          user_id: response.data.user_id
        }
        this.getLesson(obj)
      }, error => {
        console.error(error)
      })
    },
    getLesson (object) {
      const obj = {
        user_id: object.user_id,
        itemId: this.itemId,
        postId: this.postId
      }
      getLesson (obj, response => {
        if (response.status === 200 && response.data.status === 'success') {
          this.videos = response.data.videos
          this.lesson = response.data.lesson
          this.selectedVideo = response.data.videos.length > 0 ? response.data.videos[0] : []
          this.initPlayer()
          this.initAttempts(object.user_id, this.lesson.post_id)
        }
      }, error => {
        console.error(error)
      })
    },
    initAttempts (user_id, post_id, successCallback, errorCallback) {
      const obj = {
        user_id: user_id,
        post_id: post_id
      }
      initAttempts (obj, response => {
        if (response.status === 200 && response.data.status === 'success') {
          this.attempts = response.data.attempts
          successCallback(response)
        }
      }, error => {
        console.error(error)
        errorCallback(error)
      })
    },
    setAttempts () {
      const item = this.attempts.find(o => o.video_id === this.selectedVideo.id)
      const obj = {
        user_id: this.user_id,
        post_id: this.lesson.post_id,
        video_id: this.selectedVideo.id,
        current_attempt: item.attempts
      }
      setAttempts (obj, response => {
        if (response.status === 200 && response.data.status === 'success') {
          this.initAttempts(this.user_id, this.lesson.post_id, () => {
            this.refreshAttemptElement++
          })
        }
      }, error => {
        console.error(error)
      })
    },
    duration (secs) {
      return moment.utc(secs*1000).format('HH:mm:ss')
    },
    selectVideo (video) {
      this.selectedVideo = this.videos.find(o => o === video)
      this.attemptOver = false
      this.initPlayer()
    },
    setCompletion (event) {
      this.vimeoPlayer.progress = event
      this.vimeoPlayer.progress.completion = parseInt(this.vimeoPlayer.progress.percent * 100)
      if (this.vimeoPlayer.progress.completion > 20 && !this.attemptOver) {
        this.attemptOver = true
        this.setAttempts()
      }
    },
    initPlayer () {
      this.playerIframe = undefined
      this.vimeoPlayer.progress = {
        duration: 0,
        percent: 0,
        seconds: 0,
        completion: 0
      }
      setTimeout(() => {
        const iframe = document.querySelector('#vimeo-player')
        this.playerIframe = new Player(iframe)
        this.playerIframe.on('play', () => {
          console.log('Start')
        })
        this.playerIframe.on('pause', (event) => {
          this.setCompletion(event)
        })
        this.playerIframe.on('playing', (event) => {
          this.setCompletion(event)
        })
        this.playerIframe.on('progress', (event) => {
          this.setCompletion(event)
        })
        this.playerIframe.on('seeking', (event) => {
          this.setCompletion(event)
        })
      }, 2000)
    }
  },
  mounted () {
    this.authenticate()
  }
}
</script>

<style lang="scss" scoped>
.bg-dark-app {
  background-color: #303030;
}
.post-title {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  line-height: 1.3;
}
.player-iframe {
  width: 100%;
  // height: calc(100vw / 3.2);
  height: 350px;
}
</style>