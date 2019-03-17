<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    Name
                                </label>
                                <div class="col-md-6">
                                    <input id="name"
                                           type="text"
                                           class="form-control"
                                           :class="errors.name.type !== '' ? 'is-invalid' : '' "
                                           v-model="name"
                                           required autofocus>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ errors.name.message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    E-Mail Address
                                </label>
                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control"
                                           :class="errors.email.type !== '' ? 'is-invalid' : '' "
                                           v-model="email"
                                           required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ errors.email.message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">
                                    Password
                                </label>
                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control"
                                           :class="errors.password.type !== '' ? 'is-invalid' : '' "
                                           v-model="password"
                                           required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ errors.password.message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                    Confirm Password
                                </label>
                                <div class="col-md-6">
                                    <input id="password-confirm"
                                           type="password"
                                           class="form-control"
                                           :class="errors.c_password.type !== '' ? 'is-invalid' : '' "
                                           v-model="password_confirmation"
                                           required>
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ errors.c_password.message }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit"
                                            class="btn btn-primary"
                                            @click="handleSubmit">
                                        Register
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
        props : [],
        data(){
            return {
                name : "",
                email : "",
                password : "",
                password_confirmation : "",
                errors: {
                    name: {
                        type: '',
                        message: ''
                    },
                    email: {
                        type: '',
                        message: ''
                    },
                    password: {
                        type: '',
                        message: ''
                    },
                    c_password: {
                        type: '',
                        message: ''
                    }
                },
                fields: ['name', 'email', 'password', 'c_password']
            }
        },
        methods : {
            initializeError() {
                this.fields.forEach((field, index) => {
                    console.log(field);
                    this.errors[field].type = ""
                    this.errors[field].message = ""
                })
            },
            handleSubmit(e) {
                e.preventDefault()
                this.initializeError()
                var isError = false;
                this.fields.forEach((field, index) => {
                    if(this[field] == "") {
                        this.errors[field].type = "is-danger"
                        this.errors[field].message = field+' should not empty.'
                        isError = true;
                    }
                })
                if (this.password !== this.password_confirmation || this.password.length <= 0) {
                    isError = true;
                    this.errors.c_password.type = "is-danger"
                    this.errors.c_password.message = 'Passwords do not match.'
                }
                if(isError) {
                    return false;
                }
                let name = this.name
                let email = this.email
                let password = this.password
                let c_password = this.password_confirmation
                axios.post('api/register', {name, email, password, c_password})
                .then(response => {
                    let data = response.data
                    localStorage.setItem('bigStore.user', JSON.stringify(data.user))
                    localStorage.setItem('bigStore.jwt', data.token)
                    if (localStorage.getItem('bigStore.jwt') != null) {
                        this.$emit('loggedIn')
                        let nextUrl = this.$route.params.nextUrl
                        this.$router.push((nextUrl != null ? nextUrl : '/products'))
                    }
                }).catch(error => {
                    if (error.response.data.error.name) {
                        this.errors.name.type = 'is-danger'
                        this.errors.name.message = error.response.data.error.name[0]
                    }
                    if (error.response.data.error.email) {
                        this.errors.email.type = 'is-danger'
                        this.errors.email.message = error.response.data.error.email[0]
                    }
                    if (error.response.data.error.password) {
                        this.errors.password.type = 'is-danger'
                        this.errors.password.message = error.response.data.error.password[0]
                    }
                    if (error.response.data.error.c_password) {
                        this.errors.c_password.type = 'is-danger'
                        this.errors.c_password.message = error.response.data.error.c_password[0]
                    }
                })
            }
        }
    }
</script>