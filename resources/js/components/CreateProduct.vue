<template>
    <div class="row justify-content-center">
        <alert :alert-message="alertMessage" :alert-type="alertType" v-if="isAlertActive"></alert>
        <div class="col-md-10">
            <div class="addProductForm">
                <form action="#" id="productForm" method="POST" @submit="createProduct">
                    <div class="row form-group">
                        <label class="col-md-3">Name:</label>
                        <div class="col-md-9">
                            <input type="text" :class="'form-control ' + errors.productName.type" name="productName" id="productName" v-model="productName">
                            <span class="text-danger">{{  errors.productName.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">Description:</label>
                        <div class="col-md-9">
                            <textarea :class="'form-control ' + errors.description.type" name="description" id="description" v-model="description"></textarea>
                            <span class="text-danger">{{  errors.description.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">Stock:</label>
                        <div class="col-md-9">
                            <input type="number" :class="'form-control ' + errors.stock.type" name="stock" id="stock" v-model="stock">
                            <span class="text-danger">{{  errors.stock.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">Price:</label>
                        <div class="col-md-9">
                            <input type="text" :class="'form-control ' + errors.price.type" name="price" id="price" v-model="price">
                            <span class="text-danger">{{  errors.price.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">Image:</label>
                        <div class="col-md-9">
                            <input type="file" id="image" @change="attachFile" >
                            <img v-bind:src="'/images/' + image" alt="" width="150" >
                            <span class="text-danger">{{  errors.image.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">SKU:</label>
                        <div class="col-md-9">
                            <input type="text" :class="'form-control ' + errors.sku.type" name="sku" id="sku" v-model="sku">
                            <span class="text-danger">{{  errors.sku.message }}</span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-md-3">New Arrival:</label>
                        <div class="col-md-9">
                            <label for="new_arrival" class="inline-radio">
                                <input type="radio" name="new_arrival" value="0" v-model="new_arrival">
                                <span>No</span>
                            </label>
                            <label for="new_arrival" class="inline-radio">
                                <input type="radio" name="new_arrival" value="1" v-model="new_arrival">
                                <span>Yes</span>
                            </label>
                        </div>
                    </div>


                    <div class="row form-group">
                        <label class="col-md-3"></label>
                        <div class="col-md-9">
                            <input type="hidden" name="productid" v-model="productid">
                            <button class="btn btn-success" type="submit" >{{ isEditMode ? 'Update' : 'Save' }}</button>
                            <button class="btn btn-danger" type="button" @click="cancelProductForm">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Alert from './Alert'
    export default {
        components: {
            Alert
        },
        data () {
            return {
                isEditMode: false,
                errors: {
                    productName: {
                        type: '',
                        message: ''
                    },
                    description: {
                        type: '',
                        message: ''
                    },
                    stock: {
                        type: '',
                        message: ''
                    },
                    price: {
                        type: '',
                        message: ''
                    },
                    sku: {
                        type: '',
                        message: ''
                    },
                    image: {
                        type: '',
                        message: ''
                    }
                },
                fieldList: ['productName', 'description', 'stock', 'price', 'image', 'sku', 'new_arrival'],
                productName: '',
                new_arrival: '0',
                description: '',
                stock: '',
                price: '',
                image: false,
                sku: '',
                productid: '',
                attachment: null,
                alertType: '',
                alertMessage: '',
                isAlertActive: false
            }
        },
        props: ['id'],
        mounted () {
          if(this.$route.params.id) {
              this.editProductDetails(this.$route.params.id);
          }
        },
        methods: {
            attachFile(event) {
                this.attachment = event.target.files[0];
            },
            createProduct: function(e) {
                e.preventDefault();
                let validate = this.validateCreateProductForm();
                if(validate) {
                    let srvUrl = (this.isEditMode) ? 'api/products/update/'+this.productid : 'api/products' ;

                    const formData = new FormData()
                    formData.append('image', this.attachment)
                    formData.append('productName', this.productName)
                    formData.append('new_arrival', this.new_arrival)
                    formData.append('description', this.description)
                    formData.append('stock', this.stock)
                    formData.append('price', this.price)
                    formData.append('sku', this.sku)

                    let headers = {'Content-Type': 'multipart/form-data'}
                    axios({
                        method: 'post',
                        url: srvUrl,
                        data: formData,
                        headers: headers
                    }).then(res => {
                            if(res.data.status == 'error') {
                                let errorMsg = res.data.message
                                for (let key in errorMsg) {
                                    if (errorMsg.hasOwnProperty(key)) {
                                        this.errors[key].type = 'is-invalid'
                                        this.errors[key].message = errorMsg[key][0]
                                    }
                                }
                                return false;
                            }

                            if(res.status) {
                                let successMsg = (this.isEditMode) ? 'Product Details Updated Successfully' : 'Product Created!'
                                this.alertType = 'success';
                                this.alertMessage = successMsg;
                                this.isAlertActive = true;
                                this.resetForm();
                            }
                            
                        })
                }
            },
            validateCreateProductForm() {
                let that = this;
                let isError = false;
                that.fieldList.map(field => {
                    if((!['new_arrival'].includes(field))) {
                        if(that[field] == "" && field != 'image') {
                            that.errors[field].type = 'is-invalid'
                            that.errors[field].message = 'Field should not be blank.'
                            isError = true;
                        } else {
                            that.errors[field].type = ''
                            that.errors[field].message = ''
                        }
                    }
                });
                return (isError) ? false : true;
            },
            resetForm() {
                this.productName = "",
                this.new_arrival = "0",
                this.description = "",
                this.stock = "",
                this.sku = "",
                this.price = "",
                this.image = "",
                document.getElementById("image").value = "";
                this.attachment = null,
                this.isEditMode = false
            },
            editProductDetails(id) {
                axios.get("api/products/"+id).then(response => {
                    if(response.data.status == 'success') {
                        const data = response.data.data;
                        this.productName = data.name,
                        this.new_arrival = data.new_arrival,
                        this.description = data.description,
                        this.stock = data.stock,
                        this.sku = data.sku,
                        this.image = data.image,
                        this.price = data.price,
                        this.productid = data.id,
                        this.isEditMode = true
                    }
                })

            },
            cancelProductForm() {
                this.$router.push('/products')
            }
        }
    }
</script>

<style scoped>

</style>