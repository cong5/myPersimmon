<template>
    <div class="pit-post-form">
        <el-form ref="myForm" :model="myForm" v-loading="editFormLoading" label-width="100px"
                 class="pit-common">
            <el-form-item v-for="option in options" :label="option.option_title">
                <el-input :type="option.data_type" v-model="myForm[option.option_name]"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button @click="closeForm('myForm')">取 消</el-button>
                <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">
	export default{
		data(){
			return {
				editFormLoading: false,
				options: [],
				myForm: {}
			}
		},
		created () {
			this.getData();
		},
		methods: {
			getData: function () {
				let _this = this;
				_this.editFormLoading = true;
				_this.axios.get('/settings').then(function (response) {
					let res = response.data;
					if (res != false) {
						if (res.length > 0) {
							for (var index in res) {
								_this.myForm[res[index].option_name] = res[index].option_value;
							}
                            _this.options = res;
						}
					} else {
						_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					_this.editFormLoading = false;
				}).catch(function (error) {
					console.log(error);
					_this.editFormLoading = false;
				});
			},

			submitMyForm: function (myForm) {
				let _this = this;
				_this.axios.put('/settings/update', _this.myForm).then(function (response) {
					let res = response.data;
					_this.$message({
						message: res.status == 'success' ? '更新成功' : '更新失败',
						type: res.status
					});
				}).catch(function (error) {
					console.log(error);
				});
			},


		},
		watch: {},
		mounted() {
		}
	}
</script>
