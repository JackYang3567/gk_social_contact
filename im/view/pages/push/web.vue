<template>
	
	<view>
		
        <web-view :src="url" v-if="url"/>
		<text style="text-align: center;color: red;" v-else>无效的地址</text>
	</view>
	
</template>

<script>
	
	import _hook from '../../common/_hook';
	
	export default {
		components: {
			
		},
		data() {
			return {
				url: false,
			}
		},
		onShow(){
			_hook.routeTabBarHook();
		},
		onLoad(option) {
			if('url' in option){
				option.url = decodeURIComponent(option.url);
				if(/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\*\+,;=.]+$/.test(option.url)){
					if(!/^http/.test(option.url)){
						option.url = 'http://' + option.url;
					}
					this.url = option.url;
				}
			}
		},
		computed: {
			
		},
		methods: {
			
		},
		onNavigationBarButtonTap(e) {
			
			// #ifdef APP-PLUS
				let currentWebview = this.$mp.page.$getAppWebview().children()[0]; //获取当前页面的webview对象
			// #endif
			
			switch (e.index){
				case 0:
					uni.switchTab({
						url: '../chat/index'
					});
					break;
				case 1:
				
					// #ifdef APP-PLUS
						currentWebview.reload();
					// #endif
					
					// #ifdef H5
						location.reload();
					// #endif
					
					break;
				case 2:
				
					// #ifdef APP-PLUS
						currentWebview.forward();
					// #endif
					
					// #ifdef H5
						history.forward();
					// #endif
					
					break;
				default:
					break;
			}
		},
	}
</script>

<style>
	
</style>
