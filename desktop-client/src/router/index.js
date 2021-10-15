import Vue from 'vue'
import VueRouter from 'vue-router'
import { getAccount } from '@/api/auth'
import eventBus from './../eventBus'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: {
      requiredAuth: false
    }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'),
    meta: {
      requiredAuth: true,
      name: 'Dashboard'
    }
  },
  {
    path: '/lesson',
    name: 'Lesson',
    component: () => import('../views/Lesson.vue'),
    meta: {
      requiredAuth: true,
      name: 'Lesson'
    }
  }
]

const router = new VueRouter({
  base: process.env.BASE_URL,
  routes
})


router.beforeEach((to, from, next) => {
  const TOKEN = sessionStorage.getItem("token")
  const requiredAuth = to.matched.some(record => record.meta.requiresAuth)
  const isAuthPage = to.matched.some(record => record.meta.name === 'login')
  if (TOKEN) {
    const userObj = () => {
      const USERNAME = sessionStorage.getItem("username")
      getAccount({
        username: USERNAME
      }, response => {
        eventBus.$emit('account', response)
        let user = response
        let allow = user ? to.meta.roles ? to.meta.roles.indexOf(user.roleTypeId) : 0 : 0
        if (!TOKEN && requiredAuth) {
          // not logged in
          next({
            path: '/'
          })
        } else if (TOKEN && isAuthPage) {
          next({
            path: '/'
          })
        } else if (allow > -1) {
          next()
        } else {
          next({
            path: from.path
          })
        }
      }, error => {
        console.error(error)
      })
    }
    userObj()
  } else {
    if (requiredAuth) {
      // if (to && (to.name === 'join-classes')) {
      //   let toObj = JSON.stringify(to)
      // }
      next({
        path: '/login'
      })
    } else {
      next()
    }
  }
  window.scrollTo(0, 0)
})

export default router
