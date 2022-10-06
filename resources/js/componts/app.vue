<template>
     <div class="container">
        <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">
            <router-link to="/" class="navbar-brand">Laravel Vue 3</router-link>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation"></button>
            <div class="navbar-nav" v-if="isLoggedIn">
                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                <router-link to="/posts" class="nav-item nav-link">Posts</router-link>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
            </div>
            <div class="navbar-nav" v-else>
                <router-link to="/" class="nav-item nav-link">Home</router-link>
                <router-link to="/login" class="nav-item nav-link">Login</router-link>
                <router-link to="/register" class="nav-item nav-link">Register</router-link>
            </div>
        </nav>
    </div>
    <router-view v-on:changeLogin='updateLogin($event)'/>
</template>
<script>
    import $axios from 'axios';
    import Login from '../authPage/login.vue';
    export default {
        name: "App",
        data() {
            return {
                isLoggedIn: false,
            }
        },
        components: {
          Login  
        },
        
        created() {
            if (window.Laravel.isLogin) {
                this.isLoggedIn = true
            }
        },
        methods: {
            logout(e) {
                let ref = this;
                e.preventDefault()
                $axios.get('/sanctum/csrf-cookie').then(response => {
                    $axios.get('/api/logout')
                    .then(r => {
                        if(r.data.status == 200) {
                            ref.$router.push({ name: 'Home' });
                            // window.location.href = "/";
                            ref.isLoggedIn = false;
                        } else {
                            console.log(r);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                })
            },
            updateLogin(data) {
                this.isLoggedIn = data;
            },
        },
    }
</script>