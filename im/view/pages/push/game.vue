<template>
	
	<view>
		
        <web-view :src="url"/>
		
	</view>
	
</template>

<script>
	
	import _hook from '../../common/_hook';
	
	export default {
		components: {
			
		},
		data() {
			return {
				url: 'https://m.fmf3.cn/',
			}
		},
		onShow(){
			_hook.routeTabBarHook();
		},
		onLoad() {
			// #ifdef APP-PLUS
				setTimeout(() => {
					let currentWebview = this.$mp.page.$getAppWebview().children()[0]; //获取当前页面的webview对象);
					currentWebview.onloaded = () => {
						uni.setNavigationBarTitle({
						    title: ' '
						});
					}
					currentWebview.onloading = () => {
						uni.setNavigationBarTitle({
						    title: ' '
						});
					}
				}, 1000);
			// #endif
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
				
					// #ifdef APP-PLUS
						currentWebview.reload();
					// #endif
					
					// #ifdef H5
						location.reload();
					// #endif
					
					break;
				case 1:
				
					// #ifdef APP-PLUS
						currentWebview.back();
					// #endif
					
					// #ifdef H5
						history.back();
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
				case 3:
					// #ifdef APP-PLUS
						currentWebview.loadURL(this.url);
					// #endif
					
					// #ifdef H5
						location.reload();
					// #endif
					
					break;
				default:
					break;
			}
		},
	}
</script>

<style>
	.tool_button {
		width: 20px;
		height: 20px;
	}
	.status_bar {  
	    height: var(--status-bar-height);  
	    width: 100%;  
	    background-color: #F8F8F8; 
	}  
	.top_view {  
	    height: var(--status-bar-height);  
	    width: 100%;  
	    position: fixed;  
	    background-color: #F8F8F8;  
	    top: 0;  
	    z-index: 999;  
	}
	.tool_main {
		display: inline-block;
		width: 120upx;
		text-align: center;
	}
</style>
