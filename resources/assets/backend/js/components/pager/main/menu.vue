<template>
    <el-row class="panel">
        <el-col :span="24" class="panel-top">
            <el-col :span="20" style="font-size:26px;">
                <span><img src="/backend/images/logo.png" class="logo" alt=""><i style="font-style:normal">MyPersimmon</i></span>
            </el-col>
            <el-col :span="4" class="rightbar">
                <el-dropdown trigger="click"><span class="el-dropdown-link pit-username"><img :src="this.sysUserAvatar" class="head" onerror="javascript:this.src='/backend/images/logo.png'"> {{sysUserName}}</span>
                    <el-dropdown-menu slot="dropdown" class="pit-user-dropdown">
                        <el-dropdown-item @click.native="gouser">设置</el-dropdown-item>
                        <el-dropdown-item divided @click.native="logout">退出登录</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-col>
        </el-col>
        <el-col :span="24" class="panel-center">
            <aside style="width:230px;">
                <el-menu :default-active="currentPath" class="el-menu-vertical-demo" @open="handleopen"
                         @close="handleclose" @select="handleselect"
                         theme="dark" unique-opened router>
                    <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
                        <el-submenu :index="index+''" v-if="!item.leaf">
                            <template slot="title"><i :class="item.iconCls"></i>{{item.name}}</template>
                            <el-menu-item v-for="child in item.children" :index="child.path">{{child.name}}
                            </el-menu-item>
                        </el-submenu>
                        <el-menu-item v-if="item.leaf&&item.children.length>0" :index="item.children[0].path"><i
                                :class="item.iconCls"></i>{{item.children[0].name}}
                        </el-menu-item>
                    </template>
                </el-menu>
            </aside>
            <section class="panel-c-c">
                <div class="grid-content bg-purple-light">
                    <el-col :span="24" style="margin-bottom:15px;">
                        <span class="pit-current-route">{{currentPathName}}</span>
                        <el-breadcrumb separator="/" style="float:right;">
                            <el-breadcrumb-item :to="{ path: '/dashboard' }">首页</el-breadcrumb-item>
                            <el-breadcrumb-item v-if="currentPathNameParent!=''">{{currentPathNameParent}}
                            </el-breadcrumb-item>
                            <el-breadcrumb-item v-if="currentPathName!=''">{{currentPathName}}</el-breadcrumb-item>
                        </el-breadcrumb>
                    </el-col>
                    <el-col :span="24" class="pit-main">
                        <router-view></router-view>
                    </el-col>
                </div>
            </section>
            <!--</el-col>-->
        </el-col>
    </el-row>
</template>

<script type="text/ecmascript-6">
	export default {
		data() {
			return {
				currentPath: '/dashboard',
				currentPathName: '仪表盘',
				currentPathNameParent: '首页',
				sysUserName: '',
				sysUserAvatar: '',
				form: {
					name: '',
					region: '',
					date1: '',
					date2: '',
					delivery: false,
					type: [],
					resource: '',
					desc: ''
				}
			}
		},
		watch: {
			'$route'(to, from) {//监听路由改变
				this.currentPath = to.path;
				this.currentPathName = to.name;
				this.currentPathNameParent = to.matched[0].name;
			}
		},
		methods: {
			onSubmit() {
				console.log('submit!');
			},
			handleopen() {
				//console.log('handleopen');
			},
			handleclose() {
				//console.log('handleclose');
			},
			handleselect: function (a, b) {
			},
			//退出登录
			logout: function () {
				var _this = this;
				var _duration = 2 * 1000;
				this.$confirm('确认退出吗?', '提示', {
				}).then(() => {
					_this.axios.post('/auth/logout').then(function (response) {
						let data = response.data;
						if (data.status == 200) {
							sessionStorage.removeItem('myPersimmon');
							_this.$message({
								message: data.info,
								type:'success',
								duration: _duration
							});
							setTimeout(function () {
								_this.$router.replace('/login');
							}, _duration);
						} else {
							_this.$message.error("退出失败");
						}
					}).catch(function (error) {
						_this.$message.error("退出失败");
						console.log(error);
					});
				}).catch(() => {
				});
			},
            gouser: function () {
                this.$router.push({path: 'user'});
            }
		},
		mounted() {
			this.currentPath = this.$route.path;
			this.currentPathName = this.$route.name;
			this.currentPathNameParent = this.$route.matched[0].name;

			var user = sessionStorage.getItem('myPersimmon');
			if (user) {
				user = JSON.parse(user);
				this.sysUserName = user.name || '';
				this.sysUserAvatar = user.avatar || '';
			}
		}
	}
</script>

<style type="text/css" scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity .5s
    }

    .fade-enter,
    .fade-leave-active {
        opacity: 0
    }

    .panel {
        position: absolute;
        top: 0px;
        bottom: 0px;
        width: 100%;
    }

    .panel-top {
        height: 60px;
        line-height: 60px;
        background: #1F2D3D;
        color: #c0ccda;
    }

    .panel-top .rightbar {
        text-align: right;
        padding-right: 35px;
    }

    .panel-top .rightbar .head {
        width: 40px;
        height: 40px;
        border-radius: 20px;
        margin: 10px 0px 10px 10px;
        float: right;
    }

    .panel-center {
        background: #324057;
        position: absolute;
        top: 60px;
        bottom: 0px;
        overflow: hidden;
    }

    .panel-c-c {
        background: #f1f2f7;
        position: absolute;
        right: 0px;
        top: 0px;
        bottom: 0px;
        left: 230px;
        overflow-y: scroll;
        padding: 20px;
    }

    .el-menu .fa {
        vertical-align: baseline;
        margin-right: 10px;
        font-size: 16px;
    }

    .logout {
        /*background: url(../assets/logout_36.png);*/
        background-size: contain;
        width: 20px;
        height: 20px;
        float: left;
    }

    .logo {
        width: 64px;
        height: 64px;
        float: left;
        margin-left:10px;
    }

    .tip-logout {
        float: right;
        margin-right: 20px;
        padding-top: 5px;
    }

    .tip-logout i {
        cursor: pointer;
    }

    .admin {
        color: #c0ccda;
        text-align: center;
    }

    .pit-main {
        background-color: #fff;
        box-sizing: border-box;
    }

    .pit-current-route {
        width: 200px;
        float: left;
        color: #475669;
        font-weight: bold;
    }
    .el-menu-item,.el-submenu {
        border-bottom: 1px #2a3952 solid;
    }
    .pit-username {
        color: #FFF;
        cursor: pointer;
    }
    .pit-user-dropdown {
        color: #0f0f0f;
        font-size: 14px;
    }
</style>