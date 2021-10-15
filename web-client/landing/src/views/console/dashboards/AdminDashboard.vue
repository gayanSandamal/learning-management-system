<template>
  <div>
    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Apply filter section</h6>
            <div class="row">
              <div class="col-3">
                <label for="firstname" class="form-label">Start date</label>
                <datepicker placeholder="Select start date" v-model="filters.start" :disabledDates="startMaxDate"></datepicker>
              </div>
              <div class="col-3">
                <label for="firstname" class="form-label">End date</label>
                <datepicker placeholder="Select end date" v-model="filters.end" :disabledDates="endMaxDate"></datepicker>
              </div>
              <div class="col-3">
                <label class="form-label">Select an instructor</label>
                <treeselect v-model="filters.instructor_id" :multiple="false" :options="mappedInstructors" />
                <span><small>Assign a class category for this lesson</small></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="card-link">Card link</a>
            <a href="#" class="card-link">Another link</a>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="card-link">Card link</a>
            <a href="#" class="card-link">Another link</a>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="card-link">Card link</a>
            <a href="#" class="card-link">Another link</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import Treeselect from '@riophae/vue-treeselect'
import moment from 'moment'
export default {
  name: 'admin-dashboard',
  components: {
    Datepicker,
    Treeselect
  },
  data () {
    return {
      filters: {
        start: moment().subtract(1, 'months').format('YYYY, MM, DD'),
        end: moment().format('YYYY, MM, DD'),
        instructor_id: -1
      }
    }
  },
  computed: {
    startMaxDate () {
      const minMonth = moment(this.filters.end).subtract(1, 'months').subtract(1, 'days')
      const year = moment(minMonth).format('YYYY')
      const month = moment(minMonth).format('MM')
      const date = moment(minMonth).format('DD')
      return {
        from: new Date(year, month, date)
      }
    },
    endMaxDate () {
      const minMonth = moment().subtract(1, 'months')
      const year = moment(minMonth).format('YYYY')
      const month = moment(minMonth).format('MM')
      const date = moment(minMonth).format('DD')
      return {
        from: new Date(year, month, date)
      }
    },
    endDate () {
      return this.filters.end
    },
    mappedInstructors () {
      return []
    }
  },
  watch: {
    endDate: {
      handler (val) {
        const start = moment(this.filters.start)
        const end = moment(val)
        const diff = end.diff(start)
        if (diff <= 0) {
          this.filters.start = end.subtract(1, 'days').format('YYYY, MM, DD')
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@riophae/vue-treeselect/dist/vue-treeselect.css';
</style>