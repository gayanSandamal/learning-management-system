<template>
  <div class="home" id="home">
    <div id="canvasWrapper" class="canvas-wrapper position-fixed"></div>
    <section id="section-1" class="mb-5">
      <div class="container-fluid position-relative px-0 home-container shadow">
        <div class="container">
          <div class="row">
            <div class="col-md-12" :class="account && account.id ? 'col-lg-5' : 'col-lg-7'">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="home-logo-wrapper position-relative d-flex align-items-center justify-content-center">
                    <div v-html="heroLiquidIcon" class="hero-liquid"></div>
                    <div v-html="logoFullIcon" class="position-absolute hero-logo"></div>
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-12">
                  <h1 class="display-5 mt-2 mb-0 fw-light text-end mb-5 mb-lg-0">
                    <span class="bg-color-2 text-white px-2 my-2 d-inline-block">Expand</span> your<br>
                    <span class="bg-danger text-white px-2 my-0 d-inline-block">Learning</span> with<br>
                    <span class="bg-color-5 text-white px-2 my-2 d-inline-block">No</span> limits
                  </h1>
                </div>
              </div>
            </div>
            <div class="col-md-12" :class="account && account.id ? 'col-lg-7' : 'col-lg-5'">
              <div class="shadow" :class="account && account.id ? 'radius-1' : 'card px-3 bg-color-2 card-curved'">
                <div v-if="account && account.id" class="d-flex">
                  <iframe class="w-100 how-to-video" src="https://www.youtube.com/embed/eK3jWwZbmm4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div v-else class="card-body my-5">
                  <div class="col-12 mb-2">
                    <div class="d-flex justify-content-center">
                      <h5 class="text-white">Register on our platform</h5>
                    </div>
                  </div>
                  <div class="col-12 mb-4">
                    <div class="d-flex justify-content-end">
                      <Button :label="'Register'" :labelColor="'color-5 w-100'" @click="$router.push({path: '/register'})"/>
                    </div>
                  </div>
                  <div class="col-12 mt-5 mb-2">
                    <div class="d-flex justify-content-center">
                      <h5 class="text-white">Learn how to register on our platform</h5>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex">
                      <Button :label="'How to register'" :labelColor="'color-8 w-100'"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="d-flex flex-wrap justify-content-center go-down-wrapper position-relative">
                <label class="color-5">
                  <strong>VIEW MORE</strong>
                </label>
                <div class="w-100 d-block">
                  <a class="go-down" href="#section-2" role="button"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="section-2" class="mb-5">
      <div class="container">
        <div class="row align-items-start">
          <div class="col-lg-5 mb-3 mb-lg-0 is-sticky" style="top: calc(50vh - 150px);">
            <h3 class="mb-4 color-9">
              Explore <span class="text-light bg-color-5 px-1 fs-1 d-inline-block mb-1">thousands</span> of lessons<br>
              conducting by verified professional instructors<br>
            </h3>
            <div><h3 class="d-inline text-danger">A</h3>kurata.lk provides you thousands of lessons and courses conducting by verified professional instructors where you can easily find under relevant categories</div>
          </div>
          <div class="col-lg-7">
            <ul class="swap-box-ul p-0 row">
              <li v-for="item in classes" :key="item.id" class="col-12 col-sm-6 col-lg-12 mb-4">
                <div class="swap-box-wrapper">
                  <div class="swap-box-left bg-color-4">
                    <div class="swap-box-image d-flex align-items-center justify-content-center">
                      <img class="" :src="require(`@/assets/images/home/classes/${item.img}`)"/>
                    </div>
                  </div>
                  <div class="swap-box-right">
                    <h5 class="swap-box-head mb-3" v-text="item.title"></h5>
                    <p class="swap-box-desc mb-2" v-text="item.desc"></p>
                    <router-link :to="item.url" class="swap-box-link color-5">
                      <small v-text="account && account.id ? 'View classes' : 'Learn more'"></small>
                    </router-link>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section id="section-3" class="bg-color-3">
      <AppDownloadSection/>
    </section>
  </div>
</template>

<script>
import { heroLiquid, logoFull } from '@/assets/scripts/svg'
// import { initialize, clearCanvas } from '@/assets/scripts/animation'
export default {
  name: 'Home',
  data() {
    return {
      elm: undefined,
      classes: [
        {
          id: 1,
          img: '1-to-al.png',
          title: 'Grade 1 to A/L',
          desc: `We have classes starting from grade 1 to G.C.E advanced level where you can select your class and join.`,
          url: '/grade-1-to-al?class=6'
        },
        {
          id: 2,
          img: 'job-seekers.png',
          title: 'Job Seekers',
          desc: `Are you looking to add professional qualifications to yourself to find a better job? Explore our classes now`,
          url: '/job-seekers?class=1'
        },
        {
          id: 3,
          img: 'professionals.png',
          title: 'Professionals',
          desc: `We have variaty of classes specially designed to level up your professional qualitifications to the next level`,
          url: '/professionals?class=2'
        },
        {
          id: 4,
          img: 'language-classes.png',
          title: 'Language Classes',
          desc: `Dreaming an overseas profession and language matters? Learn more and join to our classes right now`,
          url: '/language-classes?class=21'
        }
      ]
    }
  },
  components: {
    Button: () => import('@/components/common/Button'),
    AppDownloadSection: () => import('@/components/common/AppDownloadSection')
  },
  props: {
    eventBus: undefined,
    account: undefined
  },
  computed: {
    heroLiquidIcon() {
      return heroLiquid
    },
    logoFullIcon() {
      return logoFull
    }
  },
  methods: {
    // liquid(event) {
    //   const height = event.target.innerHeight
    //   const width = event.target.innerWidth
    //   initialize('c', width, height)
    // },
    // initializeCanvas() {
    //   const height = window.innerHeight
    //   const width = window.innerWidth
    //   const canvas = document.createElement('canvas')
    //   canvas.setAttribute('id', 'c')
    //   canvas.setAttribute('width', width + 'px')
    //   canvas.setAttribute('height', height + 'px')
    //   const wrapper = document.getElementById('canvasWrapper')
    //   wrapper.appendChild(canvas)
    //   initialize('c', width, height)
    // }
  },
  mounted () {
    this.eventBus.$emit('set-menus', [])
    // this.initializeCanvas()
    // window.addEventListener('load', this.initializeCanvas, { passive: true })
  },
  destroyed () {
    // clearCanvas()
    // window.removeEventListener('load', this.initializeCanvas, { passive: true })
  },
}
</script>

<style lang="scss" scoped>
.canvas-wrapper {
  top: 0;
  left: 0;
}
.hero-logo {
  width: calc(100% - 50px);
  /deep/ svg {
    width: 100%;
  }
}
.hero-liquid {
  width: 100%;
  /deep/ svg {
    width: 100%;
  }
}
</style>