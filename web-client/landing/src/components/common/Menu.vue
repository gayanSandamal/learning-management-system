<template>
  <div class="menu-container">
    <ul class="menu-wrapper d-flex m-0 p-0">
      <li v-for="menu in menus" :key="menu.id" :class="{'open': menu.open}" @mouseenter="expand(menu, true)" @mouseleave="expand(menu, false)">
        <div class="url-wrapper d-flex align-items-center pe-3">
          <router-link class="d-flex" v-if="menu.url" :to="menu.url" v-text="menu.label"></router-link>
          <span class="link" v-else v-text="menu.label" @click="expand(menu, true)"></span>
          <svg v-if="menu.menus && menu.menus.length" @click="expand(menu, true)" class="clickable ms-2" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0)">
              <path d="M10.491 12.9359L17.7935 5.64855C18.0684 5.37419 18.0689 4.92901 17.7946 4.65419C17.5203 4.37931 17.0751 4.37885 16.8002 4.65314L9.49717 11.941C9.22302 12.2151 8.77695 12.2151 8.50228 11.9405L1.19976 4.65314C0.924877 4.37885 0.479692 4.3793 0.205404 4.65419C0.0684357 4.79141 -1.33743e-05 4.97116 -1.33822e-05 5.15084C-1.33901e-05 5.33102 0.0688209 5.5112 0.206459 5.64855L7.50845 12.9354C8.33089 13.7578 9.66912 13.7578 10.491 12.9359Z" fill="#454B69"/>
            </g>
            <defs>
              <clipPath id="clip0">
                <rect width="18" height="18" fill="white" transform="translate(18) rotate(90)"/>
              </clipPath>
            </defs>
          </svg>
        </div>
        <div v-if="menu.menus && menu.menus.length && menu.open" class="row">
          <div class="child-menu-contianer col-lg-8 col-md-9 shadow">
            <ul class="row px-4">
              <li class="col-md-3 col-xs-6 pt-3" v-for="child in menu.menus" :key="child.id">
                <div class="w-100 url-wrapper d-flex align-center">
                  <router-link class="w-100 d-inline-block no-wrap py-3" v-if="child.url" :to="child.url">
                    <span class="d-flex mb-3" v-text="child.label"></span>
                    <div class="menu-img d-flex mb-3" :style="'background-image: url('+ require('@/assets/images/menu/'+child.img)+')'">
                    </div>
                    <p class="wrap menu-desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</p>
                  </router-link>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'menu-comp',
  data () {
    return {
      menus: [
        {
          id: 1,
          label: 'All classes',
          menus: [
            {
              id: 2,
              label: '3, 4, 5 classes',
              url: '/primary',
              img: 'kapila-siriwardhana-leccturing-01-small.jpg'
            },
            {
              id: 3,
              label: '6, 7, 8 classes',
              url: '/primary',
              img: 'kapila-siriwardhana-leccturing-02-small.jpg'
            },
            {
              id: 4,
              label: '9, 10, 11 classes',
              url: '/primary',
              img: 'kapila-siriwardhana-leccturing-03-small.jpg'
            },
            {
              id: 5,
              label: '12 & 13 classes',
              url: '/primary',
              img: 'kapila-siriwardhana-leccturing-04-small.jpg'
            }
          ]
        },
        {
          id: 6,
          label: 'Study materials',
          url: '/asasda',
        },
        {
          id: 7,
          label: 'No child'
        }
      ]
    }
  },
  methods: {
    expand (menu, state) {
      this.$set(menu, 'open', state)
    }
  }
}
</script>