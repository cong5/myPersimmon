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
        },
    }
</script>
