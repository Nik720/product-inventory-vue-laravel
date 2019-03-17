<template>
    <div>
        <div v-show="isProcessing" class="loader">Loading...</div>
        <export @reloadData="loadProducts" v-if="products.data.length > 0"></export>
        <br>
        <template>
            <div class="table-responsive"  v-if="products.data.length > 0">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>SKU</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(product, index) of products.data" :key="index">
                        <td>{{ product.id }}</td>
                        <td>{{ product.name}}</td>
                        <td>{{ product.description}}</td>
                        <td>{{ product.stock}}</td>
                        <td>{{ product.price}}</td>
                        <td>{{ product.sku}}</td>
                        <td>
                            <img v-bind:src="'/images/' + product.image" alt="" width="80" v-if="product.image != ''">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" type="button" @click="editProduct(product.id)">Edit</button>
                            <button class="btn btn-danger btn-sm" type="button" @click="deleteProduct(product.id)">Delete</button>
                        </td>
                    </tr>
                </table>
            </div>

            <pagination :data="products" @pagination-change-page="loadProducts"></pagination>

            <div v-if="productsNotAvailable">
                <H4>You Don't have Product Yet, Click on <router-link :to="{ name: 'add-products' }">Add product
                        </router-link> Link to add new Product</H4>
            </div>
        </template>
    </div>
</template>

<style scoped>
.loader{
    width: 100%;
    height: 100%;
    display: block;
    position: fixed;
    background: #0000000a;
    top: 0;
    left: 0;
    text-align: center;
    font-size: 15px;
    z-index: 100;
}
</style>

<script>
    import Export from './Export'
    import Pagination from 'laravel-vue-pagination'
    export default {
        components: {
            Export,
            Pagination
        },
        data(){
            return {
                products : {},
                isProcessing: false,
                productsNotAvailable: false
            }
        },
        created(){
            this.loadProducts()
        },
        methods: {
            loadProducts(page){
                if (typeof page === 'undefined') {
                    page = 1;
                }

                this.isProcessing = true;
                axios.get("api/products/?page=" + page).then(response => {
                    this.products = response.data
                    if(response.data.total <= 0) {
                        this.productsNotAvailable = true;
                    }
                    this.isProcessing = false
                })
            },
            editProduct(id) {
                this.$router.push({ name: 'edit-products', params: {id}, props: true})
            },
            deleteProduct(id) {
                if(confirm("Are you sure! you want to delete this record?")) {
                    this.isProcessing = true;
                    axios.get("api/products/"+id).then(response => {
                        this.loadProducts()
                        this.isProcessing = false
                    })
                }
            }

        }
    }
</script>