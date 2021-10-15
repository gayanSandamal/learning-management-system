import Cookies from 'js-cookie'
import { getAccount } from '@/api/auth'
import Vue from 'vue'
import VueRouter from 'vue-router'
// import Home from '../views/Home.vue'
import eventBus from './../eventBus'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    // component: Home,
    component: () => import('../views/Home.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: {
      requiresAuth: false,
      name: 'login'
    }
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
    meta: {
      requiresAuth: false,
      name: 'register'
    }
  },
  {
    path: '/reset-password',
    name: 'Reset',
    component: () => import('../views/Reset.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/verify',
    name: 'Verify',
    component: () => import('../views/Verify.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/grade-1-to-al',
    name: 'Grade-1-to-A/L',
    component: () => import('../views/Grade1TtoAL.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/job-seekers',
    name: 'Job-Seekers',
    component: () => import('../views/JobSeekers.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/professionals',
    name: 'Professionals',
    component: () => import('../views/Professionals.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/language-classes',
    name: 'Language-Classes',
    component: () => import('../views/LanguageClasses.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/payments',
    name: 'Payments',
    component: () => import('../views/Payments.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/watch-lessons',
    name: 'WatchLessons',
    component: () => import('../views/WatchLessons.vue'),
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/console/',
    name: 'console',
    component: () => import('../views/Console.vue'),
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'dashboard',
        component: () => import('../views/console/Dashboard.vue'),
        meta: {
          requiresAuth: true,
          roles: [1023, 1024, 1025, 1026],
          icon: 'dashboard'
        }
      },
      {
        path: 'lms/categories/:post_type',
        component: () => import('../views/console/Categories.vue'),
        meta: {
          requiresAuth: true,
          roles: [1025, 1026],
          icon: 'category'
        }
      },
      {
        path: 'lms/lessons',
        component: () => import('../views/console/Lessons.vue'),
        meta: {
          requiresAuth: true,
          roles: [1023, 1024, 1025, 1026],
          icon: 'menu_book'
        }
      },
      {
        path: 'lms/lesson',
        component: () => import('../views/console/AddLessons.vue'),
        meta: {
          requiresAuth: true,
          roles: [1025, 1026],
          icon: 'menu_book'
        }
      },
      {
        path: 'users',
        component: () => import('../views/Users.vue'),
        meta: {
          requiresAuth: true,
          roles: [1025, 1026],
          icon: 'supervisor_account'
        }
      },
      {
        path: 'manage-payments',
        component: () => import('../views/console/ManagePayments.vue'),
        meta: {
          requiresAuth: true,
          roles: [1025, 1026],
          icon: 'payment'
        }
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  const TOKEN = Cookies.get('token')
  const requiredAuth = to.matched.some(record => record.meta.requiresAuth)
  const isAuthPage = to.matched.some(record => record.meta.name === 'login')
  if (TOKEN) {
    const userObj = () => {
      const USERNAME = Cookies.get('username')
      getAccount({
        username: USERNAME
      }, response => {
        eventBus.$emit('account', response)
        let fromPage = Cookies.get('from_page')
        fromPage = fromPage ? JSON.parse(fromPage) : undefined
        let user = response
        let allow = user ? to.meta.roles ? to.meta.roles.indexOf(user.roleTypeId) : 0 : 0
        if (!TOKEN && requiredAuth) {
          next({
            path: '/login'
          })
        } else if (TOKEN && isAuthPage) {
          next({
            path: '/'
          })
        } else if (allow > -1) {
          if (to.name === 'profile-completion') {
            next()
          } else {
            // join-classes
            if (fromPage && fromPage.name === 'join-classes') {
              next(fromPage.path)
              Cookies.remove('from_page')
            } else {
              next()
            }
          }
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
      if (to && (to.name === 'join-classes')) {
        let toObj = JSON.stringify(to)
        Cookies.set('from_page', toObj)
      }
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
