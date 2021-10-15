<template>
  <div class="watch-iframe-container">
    <iframe class="watch-iframe w-100 fh-head" :src="src" frameborder="0"></iframe>
  </div>
</template>

<script>
export default {
  name: 'lesson',
  components: {
  },
  data () {
    return {}
  },
  computed: {
    itemId () {
      return parseInt(this.$route.query.item_id)
    },
    postId () {
      return parseInt(this.$route.query.post_id)
    },
    token () {
      return sessionStorage.getItem("token")
    },
    username () {
      return sessionStorage.getItem("username")
    },
    src () {
      return `${process.env.VUE_APP_WEB_URL}watch-lessons?username=${this.username}&token=${this.token}&itemId=${this.itemId}&postId=${this.postId}`
    }
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
.watch-iframe-container {
  padding-top: 0px !important;
  height: calc(100vh - 42px);
  .watch-iframe {
    padding-top: 15px;
  }
}
</style>