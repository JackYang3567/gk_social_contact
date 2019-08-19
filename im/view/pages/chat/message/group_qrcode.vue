<template>
	<view class="page">
		<view class="bode_main">
			
			<view class="qrimg">
				<tki-qrcode
				ref="qrcode"
				:val="qrData"
				:onval="true"
				:size="500"
				:icon="group_photo"
				:iconSize="65"
				unit="upx"
				background="#ffffff"
				foreground="#000000"
				pdground="#000000"
				:loadMake="true"
				:showLoading="true"
				loadingText="加载中..."
				@result="qrR" />
				<text class="text_font">扫一扫上面的二维码,申请进群</text>
			</view>
			
		</view>
	</view>
</template>

<script>
	
	import tkiQrcode from "tki-qrcode";
	import _data from '../../../common/_data';
	import _hook from '../../../common/_hook';
	
	export default {
		components: {
			tkiQrcode,
		},
		data() {
			return {
				list_id: '',
				my_data: { id: 0 },
				group_photo: '',
			}
		},
		onShow(){
			_hook.routeTabBarHook();
			let _this = this;
			/** 监听新的个人数据 */
			uni.$on('data_user_info',function(data){
				_this.my_data = data;
			});			
			_this.my_data = _data.data('user_info');
			this.group_photo = _data.data('cache')['group_photo_' + _this.list_id];
		},
		onLoad(option) {
			this.list_id = option.list_id;
		},
		onUnload(){
			uni.$off('data_user_info');
		},
		computed: {
			qrData(){
				return _data.data('http_url') + '/group_add/' + this.list_id + '&' + this.my_data.id;
			},
		},
		methods: {
			qrR(ref){
				this.img_path = ref;
			},
		},
		watch: {
			
		},
	}
</script>

<style scoped>
	
	.bode_main {
		margin: 150upx 0;
		border-radius: 50upx;
		text-align: center;
	}
	
</style>