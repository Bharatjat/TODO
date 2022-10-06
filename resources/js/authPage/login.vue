<template>
<div class="container p-md-5">
    <div class="row jutify-content-center">
        <div class="col-md-8">
            <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{error}}</strong>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h5>Login</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="email">
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="password (min:6)">
                            </div>
                        </div>

                        <div class="form-group row mt-1 mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success" @click="handleSubmit">
                                    Login
                                </button>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-8 offset-md-4">
                                <small class="text-muted">
                                    Don't have any account yet? Please
                                    <router-link to="/register">Register</router-link>
                                </small>
                            </div>
                        </div>

                    </form>
                    <div v-html="isLogin"></div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>
import $axios from 'axios';
export default {
    name: 'Login',
    data() {
        return {
            email: "",
            password: "",
            error: null,
        }
    },
    beforeCreate() {
        let ref = this;
        if (window.Laravel.isLogin) {
            window.location.href = "/dashbord";
            // ref.$router.push({ name: 'Dashbord' });
            ref.$emit('changeLogin', true);
        }
    },
    methods: {
        handleSubmit(e) {
            let ref = this;
            e.preventDefault()
            var successFlag = 1;
            successFlag = ref.email.length == 0 ? 0 : 1;
            successFlag = ref.password.length == 0 ? 0 : 1;
            if (successFlag) {
                $axios.get('/sanctum/csrf-cookie').then(response => {
                    $axios.post('api/login', {
                            email: ref.email,
                            password: ref.password
                        })
                        .then(r => {
                            if (r.data.status == 200) {
                                // ref.$router.push({ name: 'Dashbord' });
                                window.location.href = "/dashboard";
                                ref.$emit('changeLogin', true);
                            } else {
                                ref.error = r.data.message;
                                ref.$emit('changeLogin', false);
                            }
                        })
                        .catch(function (e) {
                            ref.error = e.error;
                            ref.$emit('changeLogin', false);
                        });
                })
            } else {
                ref.error = "!Please complete all nacessary requirment before submit.";
            }
        }
    },
}
</script>
