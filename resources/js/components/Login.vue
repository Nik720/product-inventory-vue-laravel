<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-danger" v-if="isLoggedInFail">
                    Invalid email or Password.
                </div>
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Login
                                    </button>
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
    export default {
        data() {
            return {
                email: "",
                password: "",
                isLoggedInFail: false
            }
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault()
                if (this.password.length > 0) {
                    let email = this.email
                    let password = this.password

                    axios.post('api/login', {email, password}).then(response => {
                        let user = response.data.user
                        localStorage.setItem('bigStore.user', JSON.stringify(user))
                        localStorage.setItem('bigStore.jwt', response.data.token)

                        if (localStorage.getItem('bigStore.jwt') != null) {
                            this.$emit('loggedIn')
                            this.$router.push('/products')
                        }
                    }).catch(error => {
                        this.isLoggedInFail = true;
                    });
                }
            }
        }
    }
</script>