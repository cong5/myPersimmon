<template>
    <div class="login-form" v-loading="loading">
        <el-row>
            <el-col :span="24">
                <el-form ref="myForm" :model="myForm" :rules="myRules" label-width="100px">
                    <el-form-item label="用户名：" prop="email">
                        <el-input type="email" v-model="myForm.email" placeholder="请输入用户名"></el-input>
                    </el-form-item>
                    <el-form-item label="密码：" prop="password">
                        <el-input type="password" v-model="myForm.password" placeholder="请输入密码"></el-input>
                    </el-form-item>
                    <!--el-form-item>
                        <el-checkbox-group v-model="myForm.remember">
                            <el-checkbox label="记住我" name="type"></el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>-->
                    <el-form-item>
                        <el-button type="primary" @click="loginSubmit('myForm')">登录</el-button>
                        <el-button @click="resetForm('myForm')">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </div>
</template>
<style type="text/css">
    body {
        background: #324057;
        color: #FFF;
    }

    .login-form {
        width: 350px;
        margin: 10% auto 0 auto;
        padding: 50px 50px 50px 30px;
        background: #FFF;
        border-radius: 2px;
    }

</style>
<script type="text/ecmascript-6">
	export default{
		data(){
			return {
                loading: false,
				myForm: {
					email: '',
					password: '',
					remember: ''
				},
				myRules: {
					email: [
						{required: true, type: "email", message: '请填写用户名', trigger: 'blur'}
					],
					password: [
						{required: true, message: '请填写密码', trigger: 'blur'},
						{min: 6, max: 64, message: '密码长度在 6 到 64 个字符', trigger: 'blur'}
					]
				}
			}
		},
		methods: {
			loginSubmit: function (myForm) {
				var _this = this;
				var _duration = 2 * 1000;
				_this.$refs[myForm].validate((valid) => {
					if (valid) {
					    _this.loading = true;
						_this.axios.post('/auth/login', _this.myForm).then(function (response) {
							let data = response.data;
							if (data.status == 200) {
								sessionStorage.setItem('myPersimmon', JSON.stringify(data.user));
								_this.$message({
									message: data.info,
                                    type:'success',
									duration: _duration
								});
								setTimeout(function () {
									_this.$router.push({path: '/dashboard'})
								}, _duration);
							} else {
								_this.$message.error(data.info);
                                _this.loading = false;
							}
						}).catch(function (error) {
                            _this.loading = false;
							console.log(error);
						});
					} else {
						console.log('myForm valid error.');
						return false;
					}
				});
			},
			resetForm: function (myForm) {
				this.$refs[myForm].resetFields();
			}
		}
	}
</script>






















