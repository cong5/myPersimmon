<template>
    <div class="comments-area">
        <div id="respond" class="comment-respond">
            <h3 id="reply-title" class="comment-reply-title">发表评论</h3>
            <div class="comment-form">
                <form :model="myForm" id="commentform" class="comment-form" novalidate>
                    <p class="comment-notes">
                        <span id="email-notes">电子邮件地址不会被公开。</span> 必填项已用<span class="required">*</span>标注
                    </p>
                    <p class="comment-form-author">
                        <label for="name">姓名 <span class="required">*</span></label>
                        <input id="name" v-model="myForm.name" ref="name" type="text" value="" size="30" maxlength="245"
                               aria-required='true' required='required'/>
                    </p>
                    <p class="comment-form-email">
                        <label for="email">电子邮件 <span class="required">*</span></label>
                        <input id="email" v-model="myForm.email" ref="email" type="text" value="" size="30"
                               maxlength="100" aria-describedby="email-notes" aria-required='true' required='required'/>
                    </p>
                    <p class="comment-form-url">
                        <label for="url">站点</label>
                        <input id="url" v-model="myForm.url" ref="url" type="text" value="" size="30" maxlength="200"/>
                    </p>
                    <p class="mk-tips">Tips：支持markdown 语法 <a name="comment"></a></p>
                    <p class="comment-form-comment">
                        <label for="markdown">评论</label>
                        <textarea id="markdown" ref="markdown" v-model="myForm.markdown" cols="45" rows="8"
                                  maxlength="65525" aria-required="true" required="required"></textarea>
                    </p>
                    <p class="form-submit">
                        <button @click="comment" type="button" id="submit" class="submit" v-html="sublimtText"></button>
                    </p>
                </form>
                <div class="commentPreview" v-html="commentPreview"></div>
            </div>
        </div><!-- #respond -->
        <div class="commentshow">
            <!-- .comment-list -->
            <ol class="commentlist">

                <li class="comment" v-for="comment in comments" itemprop="reviews" itemscope
                    itemtype="https://schema.org/Review">
                    <article class="comment-body comment-body-parent">
                        <div class="comment-author">
                            <a :name="comment.md5"></a>
                            <img :src="'https://cn.gravatar.com/avatar/'+comment.md5+'?d=identicon&s=60'"
                                 class="avatar avatar-96" height="96" width="96">
                        </div>
                        <div class="comment-content">
                            <div class="comment-entry">
                                <span class="name author" itemprop="author">
                                    <a :href="comment.url" target="_blank">{{  comment.name }}：</a>
                                </span>
                                <section itemprop="reviewBody" v-html="comment.content"></section>
                            </div>
                            <div class="comment-head">
                                <span class="date"><time :datetime="comment.created_at" itemprop="datePublished">{{ comment.created_at }}</time></span>
                                <a rel='nofollow' class='comment-reply-link' href="#comment"
                                   :aria-label="'回复'+comment.name" @click="reply(comment.name)">回复</a>
                                <!--<a class="comment-edit-link" href="javascript:void(0)">删除</a>-->
                            </div>
                        </div>
                    </article>
                </li><!-- #comment-## -->

            </ol>
        </div>
    </div>
</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">
    export default{
        data: function () {
            return {
                loading: false,
                sublimtText: '发表评论',
                sublimtLoading: false,
                comments: [],
                myForm: {
                    post_id: this.post,
                    name: '',
                    email: '',
                    url: '',
                    markdown: ''
                }
            }
        },
        props: ['post'],
        computed: {
            commentPreview: function () {
                return marked(this.myForm.markdown, {sanitize: true});
            }
        },
        methods: {
            comment: function (event) {
                var _this = this;
                for (var key in _this.myForm) {
                    if (_this.myForm[key] == '') {
                        _this.$refs[key].focus();
                        return false;
                        break;
                    }
                }
                _this.sublimtText = '<img class="sublimt-loading" src="/assets/images/loading.svg" alt=""> 提交中...';
                axios.post('/comment', _this.myForm).then(function (response) {
                    if (response.data.status == 'success') {
                        _this.comments.splice(-1, 0, _this.myForm);
                        _this.myForm = {
                            post_id: this.post,
                            name: '',
                            email: '',
                            url: '',
                            markdown: ''
                        };
                    }
                    _this.sublimtText = '发表评论';
                }).catch(function (error) {
                    console.log(error);
                    _this.sublimtText = '发表评论';
                });
                console.log('end');
                return false;
            },
            getData: function () {
                var _this = this;
                axios.get('/comment/' + _this.post).then(function (response) {
                    _this.comments = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            reply: function (name) {
                var at = '@' + name + ' ';
                this.myForm.markdown += at;
                return false;
            }
        },
        mounted: function () {
            this.getData();
        }
    }
</script>
