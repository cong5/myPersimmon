/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import ElementUI from 'element-ui';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

/**
 * axios config
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 * @type {string}
 */
Vue.axios.defaults.headers.common = {
	'X-CSRF-TOKEN': window.Laravel.csrfToken,
	'X-Requested-With': 'XMLHttpRequest'
};
Vue.axios.defaults.baseURL = Laravel.apiUrl;

/**
 * Load custom/other func library
 */
import util from './lib/util';
import marked from 'marked';
import localforage from 'localforage';
Vue.prototype.util = util;
Vue.prototype.marked = marked;
Vue.prototype.localforage = localforage;


import App from './App.vue';
import Login from './components/pager/user/login.vue';
import Menu from './components/pager/main/menu.vue';
import Main from './components/pager/main/main.vue';
import Posts from './components/pager/posts/posts.vue';
import Trash from './components/pager/posts/trash.vue';
import Post from './components/pager/posts/post.vue';
import Tags from './components/pager/posts/tags.vue';
import Categorys from './components/pager/posts/categorys.vue';
import Comments from './components/pager/extends/comments.vue';
import Links from './components/pager/extends/links.vue';
import Navigations from './components/pager/extends/navigations.vue';
import Options from './components/pager/extends/options.vue';
import Setting from './components/pager/other/setting.vue';
import User from './components/pager/user/user.vue';


const routes = [
	{
		path: '/login',
		component: Login,
		hidden: true
	},
	{
		path: '/',
		component: Menu,
		name: '',
		iconCls: 'fa fa-home',
		leaf: true,
		children: [
			{ path: '/dashboard', component: Main, name: '仪表盘' }
		]
	},
	{
		path: '/',
		component: Menu,
		name: '文章',
		iconCls: 'fa fa-file-word-o',//图标样式class
		children: [
			{ path: '/posts', component: Posts, name: '文章管理' },
			{ path: '/posts/add', component: Post, name: '发布文章' },
			{ path: '/categorys', component: Categorys, name: '分类管理' },
			{ path: '/Tags', component: Tags, name: '标签管理' },
			{ path: '/trash', component: Trash, name: '回收站' }
		]
	},
	{
		path: '/',
		component: Menu,
		name: '扩展',
		iconCls: 'fa fa-external-link-square',//图标样式class
		children: [
            { path: '/navigations', component: Navigations, name: '导航管理' },
            { path: '/links', component: Links, name: '友链管理' },
            { path: '/comments', component: Comments, name: '评论管理' },
            { path: '/options', component: Options, name: '配置管理' }
		]
	},
	{
		path: '/',
		component: Menu,
		name: '',
		iconCls: 'fa fa-cog',
		leaf: true,
		children: [
			{ path: '/setting',  component: Setting, name: '设置' }
		]
	},
    {
        path: '/',
        component: Menu,
        name: '',
        iconCls: 'fa fa-home',
        leaf: true,
        hidden: true,
        children: [
            { path: '/posts/edit/:id', component: Post, name: '编辑文章'},
        ]
    },
    {
        path: '/',
        component: Menu,
        name: '',
        iconCls: 'fa fa-home',
        leaf: true,
        hidden: true,
        children: [
            { path: '/user', component: User, name: '用户设置'}
        ]
    }
];

const router = new VueRouter({
	history: true,
	root: 'dashboard',
	routes
});

const app = new Vue({
	el: '#app',
	template: '<App/>',
	router,
	components: { App }
}).$mount('#app');

