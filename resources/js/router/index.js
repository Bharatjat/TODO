import { createRouter, createWebHistory } from "vue-router";

//home page
// import homePage from '../componts/home.vue';

//page not found
// import pageNotFound from '../componts/notfound.vue';

const routes = [
    //home page
    {
        path: '/',
        name: 'Home',
        component: import('../componts/home.vue')
    },

    //login page
    {
        path: '/login',
        name: 'Login',
        component: import('../authPage/login.vue')
    },

    //register page
    {
        path: '/register',
        name: 'Register',
        component: import('../authPage/register.vue')
    },

    //dashbord page
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: import('../pages/dashboard.vue')
    },

    //logout page
    {
        path: '/logout',
        name: 'Logout',
    },
        
    // not found page
    {
        path: '/:pathMatch(.*)*',
        component: import('../componts/notfound.vue')

    }
]

const router = createRouter({
    routes,
    history: createWebHistory(),
})

export default router;