<template>
  <div>
    <div class="modal video-modal fade" v-if="editModal.state" :class="{'show': editModal.show, 'd-block': editModal.state}" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @click="closeModal">
      <div class="modal-dialog modal-dialog-scrollable" @click.stop="function(){}">
        <div class="modal-content">
          <div class="modal-body">
            <iframe class="selected-video w-100" height="300" :src="`https://player.vimeo.com/video/${editModal.id}?title=0&byline=1&portrait=0&sidedock=0`" frameborder="0" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-backdrop fade" v-if="editModal.state" :class="{'show': editModal.show}"></div>
  </div>
</template>

<script>
export default {
  name: 'video-service-modal',
  data () {
    return {
    }
  },
  props: {
    eventBus: undefined,
    editModal: undefined
  },
  components: {
  },
  computed: {
  },
  methods: {
    closeModal() {
      this.$emit('modal-state', false)
    }
  },
  mounted() {
  }
}
</script>

<style lang="scss" scoped>
// .video-modal {
//   .modal-dialog {
//     max-width: calc(100% - 100px);
//     iframe {
//       height: calc(100vh - 100px);
//     }
//   }
// }
</style>