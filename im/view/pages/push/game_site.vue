<template>
	
		
		<iframe id="iframe" src="http://6988z.com/"></iframe>
		
	
</template>

<script>
	
	import uniList from '../../components/uni-ui/uni-list/uni-list.vue';
	import uniListItem from '../../components/uni-ui/uni-list-item/uni-list-item.vue';
	import _get from '../../common/_get';
	import _hook from '../../common/_hook';
	
	export default {
		components: {
			uniList,
			uniListItem
		},
		data() {
			return {
			}
		},
		onShow(){
			_hook.routeTabBarHook();
		},
		onLoad() {
			
		},
		computed: {
			showTips(){
				if(this.$store.state.no_reader_circle_chat_num){
					return this.$store.state.no_reader_circle_chat_num;
				}
				if(this.$store.state.no_reader_circle){
					return ' ';
				}
				return '';
			},
		},
		methods: {
			goPath(path){
				if(path){
					uni.navigateTo({
						url: path
					});
				}
			},
		},
	}
</script>

<style scoped>
	html,body,#iframe{
            padding:0;
            margin:0;
            width:100%;
            height:100%
    }
	uni-page-body, uni-page-refresh {
    display: block;
    box-sizing: border-box;
    width: 100%;
    height: 100%;
   }
	#iframe{
	        border:0;
	}
	.uni-list {
		margin-bottom: 30upx;
	}
	
</style>
