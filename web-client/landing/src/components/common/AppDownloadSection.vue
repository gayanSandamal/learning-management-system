<template>
  <div class="alert-secondary shadow-inset-1 py-5 w-100">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3 class="color-9 mb-3">Download <span class="text-warning bg-dark px-1">"Student Portal"</span> and start watching your lessons</h3>
          <p>Download our standalone (desktop) application or mobile application called AKA (also known as) "Student Portal" to watch the videos of enrolled lessons and classes where you can easily find and manage your course lessons</p>
        </div>
        <div class="col-12">
          <ul class="list-unstyled">
            <li class="mb-3" v-for="download in downloads" :key="download.id">
              <div class="row app-wrapper">
                <div class="col-3 col-sm-2 col-lg-1 app-icon" v-html="typeIcon(download.app_type)"></div>
                <div class="col-9 col-sm-10 col-lg-11">
                  <a :href="download.url" download target="_blank" v-text="download.line1"></a>
                  <p class="mb-0"><small v-text="download.line2"></small></p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getFeatureDownloads } from '@/api/downloads'
import { windows, playstore, appstore } from '@/assets/scripts/svg'
export default {
  name: 'app-download-section',
  data () {
    return {
      downloads: []
    }
  },
  computed: {
    windowsIcon () {
      return windows
    },
    playstoreIcon () {
      return playstore
    },
    appstoreIcon () {
      return appstore
    }
  },
  methods: {
    typeIcon (type) {
      let icon
      switch (type) {
        case 1:
          icon = this.windowsIcon
          break
        case 2:
          icon = 'mac'
          break
        case 3:
          icon = this.playstoreIcon
          break
        case 4:
          icon = this.appstoreIcon
      }
      return icon
    },
    getFeatureDownloads() {
      getFeatureDownloads(response => {
        if (response.data.status === 'success') {
          this.downloads = response.data.downloads
        }
      }, error => {
        this.$toastr.e('', 'Error loading cats')
        console.error(error)
      })
    }
  },
  mounted() {
    this.getFeatureDownloads()
  }
}
</script>
