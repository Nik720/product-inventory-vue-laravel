<template>
    <div>
        <div>
            <label>Export Option</label>
            <input type="radio" name="exportType" value="csv" v-model="exportType"> CSV
            <input type="radio" name="exportType" value="xlsx" v-model="exportType"> Excel
        </div>
    	<button @click="exportProducts()">Export</button>
    	<button  v-if="!showImportBox" @click="showImportBox = true">Import</button>
    	<div v-if="showImportBox">
    		<input type="file" name="sampleFile" @change="attachFile" id="sampleFile">
    		<button @click="uploadData()">submit</button>
    		<button @click="showImportBox = false">Cancel</button>
    	</div>
    </div>
</template>

<script>
    export default {
        name: "Export",
        data() {
        	return {
        		showImportBox: false,
        		attachment: null,
                exportType: 'csv'
        	}
        },
        methods: {
        	attachFile(event) {
				this.attachment = event.target.files[0];
			},	        
        	exportProducts() {
                axios({
                  url: 'api/exportFile/'+this.exportType,
                  method: 'GET',
                  responseType: 'blob',
                }).then((response) => {
                  const url = window.URL.createObjectURL(new Blob([response.data]));
                  const link = document.createElement('a');
                  link.href = url;
                  link.setAttribute('download', 'products.'+this.exportType);
                  document.body.appendChild(link);
                  link.click();
                });
        	},
        	uploadData() {
        		const formData = new FormData();
        		formData.append('sample_file', this.attachment);
				axios.post('api/importFile',formData).then(response => {
                    if(response.status) {
                        this.showImportBox = false
                        this.attachment = null
                        document.getElementById("sampleFile").value = "";
                        this.$emit('reloadData');
                    }
                })
        	}
        }
    }
</script>

<style scoped>

</style>