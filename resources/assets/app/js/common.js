window.Vue = require('vue');
window.axios = require('axios');
window.marked = require('marked');
window.Prism = require('./prism.min');
Prism.highlightAll();

Vue.component('myp-comment', require('./components/comment.vue'));
var myPersimmon = new Vue({
	el: '#comment',
	data: function () {
		return {}
	},
	methods: {},
    mounted: function() {
    },
	watch: {}
});