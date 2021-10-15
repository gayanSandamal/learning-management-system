<template>
  <div class="px-3 px-lg-3 register-page">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-sm-6 col-md-7 col-lg-5 col-xxl-3">
        <Subscription :eventBus="eventBus"/>
      </div>
    </div>
  </div>
</template>

<script>
import Subscription from '@/components/forms/Subscription'
export default {
  name: 'register',
  data() {
    return {}
  },
  props: {
    eventBus: undefined
  },
  components: {
    Subscription
  }
}
</script>