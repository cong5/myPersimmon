<template>
    <div v-show="show" class="emojies-list fixed" :style="style" transition="slide">
        <ul class="content">
            <li v-for="item in options"><a><img :src="parseImg(item)" alt="">{{item}}</a></li>
        </ul>
    </div>
    <!--<emojies :options.sync="emojiesData" :show.sync="showEmojies" :style.sync="emojiesStyle"></emojies>-->
</template>
<style type="text/css">
    .emojies-list {
        position: absolute;
        z-index: 100;
        /*top: 160px;*/
        bottom: auto;
        border: 1px #eee solid;
        box-shadow: 2px 2px 2px #eee;
        background: #FFF;
        max-height: 200px;
        overflow-y: scroll;
    }

    .emojies-list li {
        list-style: none;
    }

    .emojies-list li:hover {
        background: #428bca;
        color: #FFF;
    }

    .emojies-list li a:hover {
        color: #FFF;
    }

    .emojies-list li a {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: 400;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
        text-decoration: none;
    }

    .emojies-list img {
        height: 18px;
        width: 18px;
        margin-right: 5px;
    }
</style>
<script type="text/ecmascript-6">

	export default{
		data(){
			return {}
		},
		props: ['options', 'show', 'style'],
		methods: {
			parseImg(name){
				return '/assets/emojis/' + name + '.png';
			},
			dispathEventAndClose(event) {
				this.$dispatch(event);
			}
		}
	}
</script>
