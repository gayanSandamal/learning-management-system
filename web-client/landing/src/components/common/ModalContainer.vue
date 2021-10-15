<template>
  <div>
    <component :eventBus="eventBus" :account="account" :is="modal" :editModal="editModal" @modal-state="setModalState(false)"/>
    <div class="modal-backdrop fade" v-if="editModal.state" :class="{'show': editModal.show}"></div>
  </div>
</template>

<script>
export default {
  name: 'modal-container',
  data() {
    return {
      editModal: {
        state: false,
        show: false
      },
      temp: {
        query: undefined,
        path: undefined
      }
    }
  },
  props: {
    eventBus: undefined,
    account: undefined
  },
  components: {
    EditUserModal: () => import ('@/components/modals/EditUserModal'),
    EditCategoryModal: () => import ('@/components/modals/EditCategoryModal'),
    SelectVideoServiceModal: () => import ('@/components/modals/SelectVideoServiceModal')
  },
  computed: {
    modal() {
      return this.$route.query.modal
    }
  },
  methods: {
    setModalState(state) {
      if (state) {
        this.temp.path = JSON.parse(JSON.stringify(this.$route.path))
        this.temp.query = JSON.parse(JSON.stringify(this.$route.query))
        this.editModal.state = state
        setTimeout(() => {
          this.editModal.show = state
        }, 50)
      } else {
        this.editModal.show = state
        setTimeout(() => {
          this.editModal.state = state
          for (var key in this.temp.query) {
            if (Object.prototype.hasOwnProperty.call(this.temp.query, key)) {
              this.temp.query[key] = undefined
            }
          }
          this.$router.push({path: this.temp.path, query: this.temp.query})
        }, 500)
      }
    },
    fadeOutModal () {
      this.editModal.show = false
      setTimeout(() => {
        this.editModal.state = false
      }, 500)
    }
  },
  watch: {
    modal: {
      handler(val) {
        if (val) {
          this.setModalState(true)
        }
      }
    }
  },
  mounted () {
    this.eventBus.$on('fade-out-modal', this.fadeOutModal)
  },
  beforeDestroy () {
    this.eventBus.$off('fade-out-modal', this.fadeOutModal)
  }
}
</script>