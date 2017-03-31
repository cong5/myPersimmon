<template>
    <div class="dashboard">
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-collapse v-model="statisticalNames">
                        <el-collapse-item name="1">
                            <template slot="title">元数据</template>
                            <div class="collapse-content" v-loading="statisticalLoading">
                                <ul>
                                    <li><i class="fa fa-calendar-check-o" aria-hidden="true"></i> 正常文章：{{ statistical.posts }}
                                        篇</li>
                                    <li><i class="fa fa-trash-o" aria-hidden="true"></i> 回收站文章：{{ statistical.post_trash }} 篇</li>
                                    <li><i class="fa fa-comments-o" aria-hidden="true"></i> 评论：{{ statistical.comments }} 条</li>
                                </ul>
                            </div>
                        </el-collapse-item>
                        <el-collapse-item name="2">
                            <template slot="title">Todo</template>
                            <div class="collapse-content" v-loading="todoLoading">
                                <div class="wunderlist-auth" v-if="shanbayAuth">
                                    <el-button type="info" @click="authWunderlist">wunderlist 登录授权</el-button>
                                </div>
                                <div class="todo-list">
                                    <ul>
                                        <li v-for="todo in wunderlists" :data-id="todo.id">
                                            <el-checkbox> {{ todo.title }}</el-checkbox>
                                            <span class="todo-date">{{ todo.due_date }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                </div>
            </el-col>
            <el-col :span="12">
                <div class="grid-content bg-purple">
                    <el-collapse v-model="recentNames">
                        <el-collapse-item name="1">
                            <template slot="title">最近发布</template>
                            <div class="collapse-content" v-loading="recentLoading">
                                <div class="posts-list">
                                    <ul>
                                        <li v-for="post in statistical.recent_posts">
                                            <span class="posts-date">{{ post.created_at }}</span>
                                            <router-link :to="{ path: '/posts/edit/'+ post.id}">
                                                {{ post.title }}
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </el-collapse-item>
                    </el-collapse>
                </div>
            </el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="12">
                <div class="grid-content bg-purple"></div>
            </el-col>
            <el-col :span="12">
                <div class="grid-content bg-purple"></div>
            </el-col>
        </el-row>
    </div>
</template>
<style type="text/css">
    .dashboard {
        padding: 1em;
    }
    .dashboard ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .collapse-content {
        display: block;
        overflow: hidden;
    }
    .posts-list .posts-date {
        float: right;
    }
    .posts-list li a {
        text-decoration: none;
        color: #1f2d3d;
    }
    .posts-list li {
        padding: 5px 0;
        border-bottom: 1px #eee dashed;
    }
</style>
<script type="text/ecmascript-6">
	export default{
		data(){
			return {
				statisticalLoading: false,
                recentLoading: false,
                statisticalNames: ['1','2'],
                recentNames: ['1'],
                shanbayAuth: false,
                shanbay:[],
                wunderlistAuth: false,
				wunderlistAuthUrl: '',
				wunderlists: [],
				statistical: {
					posts: 0,
					comments: 0,
					post_trash: 0,
                    recent_posts:[]
				}
			}
		},
		created () {
			this.getMeta();
			this.getWunderlist();
			this.getShanbay();
		},
		methods: {
			getMeta: function () {
				let _this = this;
				_this.statisticalLoading = true;
				_this.axios.get('/dashboard/meta').then(function (response) {
					let res = response.data;
					if (res != false) {
						_this.statistical = res;
					} else {
						_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					_this.statisticalLoading = false;
				}).catch(function (error) {
					console.log(error);
					_this.statisticalLoading = false;
				});
			},
			getWunderlist: function () {
				let _this = this;
				_this.todoLoading = true;
				_this.axios.get('/wunderlist').then(function (response) {
					let res = response.data;
					if (res == false) {
						_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					if (res.auth == 'Unauthenticated') {
						_this.wunderlistAuth = true;
						_this.wunderlistAuthUrl = res.authUrl;
					}

					if (res.status) {
						_this.wunderlists = res.todo;
					}

					_this.todoLoading = false;
				}).catch(function (error) {
					console.log(error);
					_this.todoLoading = false;
				});
			},
			authWunderlist: function () {
				let winObj = this.openWin(this.wunderlistAuthUrl, 'Wunderlist 登录授权', 850, 450);
				let loop = setInterval(function () {
					if (winObj.closed) {
						clearInterval(loop);
						this.getWunderlist();
					}
				}, 1000);
			},
            getShanbay:function () {
            },
			openWin: function (url, name, iWidth, iHeight) {
				//获得窗口的垂直位置
				let iTop = (window.screen.availHeight - 30 - iHeight) / 2;
				//获得窗口的水平位置
				let iLeft = (window.screen.availWidth - 10 - iWidth) / 2;
				return window.open(url, name, 'height=' + iHeight + ',innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no');
			}
		},
	}
</script>
