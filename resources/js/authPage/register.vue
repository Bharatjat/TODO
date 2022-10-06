<template>
    <div class="container p-md-5">
        <div class="row jutify-content-center">
            <div class="col-md-8">
                <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong v-html="error"></strong>
                </div>

                <div v-if="success !== null" class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong v-html="success"></strong>
                </div>

                <div class="card card-default">
                    <div class="card-header"><h5>Register New User</h5></div>
                    <div class="card-body">
                        <form>

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" v-model="name" required
                                           autofocus placeholder="name">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" v-model="email" required
                                           autofocus placeholder="email">
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" v-model="password"
                                           required placeholder="password (min:6)">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="cPassword" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-8">
                                    <input id="cPassword" type="password" class="form-control" v-model="cPassword"
                                           required placeholder="confirm password">
                                </div>
                            </div>

                            <div class="form-group row mt-1 mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-success" @click="handleSubmit">
                                        Register
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-8 offset-md-4">
                                    <small class="text-muted">
                                        Have an account? Please
                                        <router-link to="/login" >login</router-link>
                                    </small>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import $axios from 'axios';
    export default {
        name: 'Register',    
        data() {
            return {
                name: "",
                email: "",
                password: "",
                cPassword: "",
                error: null,
                success: null
            }
        },

        created() {
            if (window.Laravel.isLogin) {
                window.location.href = "/dashbord"; 
            }
        },

        methods: {
            handleSubmit(e) {
                let ref = this;
                e.preventDefault()
                var successFlag = 1;
                successFlag = ref.name.length == 0 ? 0 : 1;
                successFlag = ref.email.length == 0 ? 0 : 1;
                successFlag = ref.password.length == 0 ? 0 : 1;
                successFlag = ref.cPassword != this.password ? 0 : 1;
                successFlag = ref.cPassword.length == 0 ? 0 : 1;
                if(successFlag) {
                    $axios.get('/sanctum/csrf-cookie').then(response => {
                    $axios.post('api/register', {
                        name: ref.name,
                        email: ref.email,
                        password: ref.password,
                        cPassword: ref.cPassword
                    })
                        .then(r => {
                            if(r.data.status == 200){
                                ref.success = r.data.message;
                                setTimeout(() => {
                                    ref.success = null;
                                    ref.$router.push({ name: 'Login' }); 
                                }, 5000);
                            } else {
                                console.log(r.data.error);
                                var errors = '';
                                r.data.error.forEach(element => {
                                    errors += element + '<br/>';
                                });
                                ref.error = errors;
                            }
                        })
                        .catch(function (e) {
                            ref.error = e.error;
                        });
                    })
                } else {
                    ref.error = "!Please complete all nacessary requirment before submit.";
                }
            }
        },

        // beforeRouteEnter(to, from, next) {
        //     if (window.Laravel.isLoggedin) {
        //         return next('dashboard');
        //     }
        //     next();
        // }
    }
</script>
