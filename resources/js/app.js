import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from './components/App'
import Login from './components/Login'
import Register from './components/Register'
import Products from './components/Products'
import CreateProduct from './components/CreateProduct'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/products',
            name: 'products',
            component: Products,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/add-products',
            name: 'add-products',
            component: CreateProduct,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/edit-products/:id',
            name: 'edit-products',
            component: CreateProduct,
            meta: {
                requiresAuth: true
            }
        }
    ],
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('bigStore.jwt') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('bigStore.user'))
            window.axios.defaults.headers.common['Authorization'] = 'Bearer '+localStorage.getItem('bigStore.jwt')
            next()
        }
    } else {
        next()
    }
})

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});