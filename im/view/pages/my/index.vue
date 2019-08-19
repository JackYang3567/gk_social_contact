<template>
	<view class="page">
		
		<view class="uni-list my_padding">
			 <navigator url="/pages/my/details" hover-class="navigator-hover">
				<view class="uni-list-cell">
					<view class="uni-media-list uni-list-cell-navigate uni-navigate-right">
						<view class="uni-media-list-logo phto">
							<image :src="myPhoto" :lazy-load="true" style="border-radius: 10upx;" />
						</view>
						<view class="uni-media-list-body">
							<view class="uni-media-list-text-top">{{my_data.nickname}}</view>
							<view class="uni-media-list-text-bottom uni-ellipsis">帐号: {{my_data.username}}</view>
						</view>
						<view class="uni-media-list-logo qrcode">
							<image src="/static/theme/default/my/qrcode.png" :lazy-load="true"/>
						</view>
					</view>
				</view>
			</navigator>
		</view>
		
		<uni-list>
			<uni-list-item title="朋友圈" 
						   :show-arrow="true"
						   :show-badge="true"
						   badge-type="error"
						   :badge-text="show_tips"
						   thumb="../../static/theme/default/push/circle.png"
						   @click="goPath('../push/circle')"
						   />
						   
		</uni-list>
		
		<uni-list v-if="0">
			<uni-list-item title="支付"
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/pay.png"
						   @click="goPath()"
						   v-if="0"
						   />
		</uni-list>
		
		<uni-list v-if="0">
			<uni-list-item title="小程序" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/push/program.png"
						   v-if="0"
						   />
						   
			<uni-list-item title="相册" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/images.png"
						   @click="goPath()"
						   v-if="0"
						   />
			<uni-list-item title="实名绑定" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/real.png"
						   @click="goPath()"
						   v-if="0"
						   />
			<uni-list-item title="表情" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/emoji.png"
						   @click="goPath()"
						   v-if="0"
						   />
		</uni-list>
		
		<uni-list>
			<uni-list-item title="我的收款二维码" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/qrcode.png"
						   @click="goPath()"
						   v-if="0"
						   />
			<uni-list-item title="设置" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/set.png"
						   @click="goPath('../set/index')"
						   />
		</uni-list>
		
	</view>
</template>

<script>
	
	import uniList from '@dcloudio/uni-ui/lib/uni-list/uni-list.vue';
	import uniListItem from '@dcloudio/uni-ui/lib/uni-list-item/uni-list-item.vue';
	import _get from '../../common/_get';
	import _hook from '../../common/_hook';
	import _data from '../../common/_data';
	
	export default {
		components: {
			uniList,
			uniListItem
		},
		data() {
			return {
				my_data: { id: 0 },
				show_tips: '',
			}
		},
		onShow(){
			_hook.routeTabBarHook();
			let num = _data.data('no_reader_circle_chat_num'),
			_this = this;
			_this.my_data = _data.data('user_info');
			
			/** 监听新的个人数据 */
			uni.$on('data_user_info',function(data){
				_this.my_data = data;
			});
			
			/** 监听朋友圈动态提示 */
			uni.$on('data_circle_tips',function(data){
				_this.show_tips = data;
			});
			
			if(num){
				this.show_tips = num;
			}
			else if(_data.data('no_reader_circle')){
				this.show_tips = '好友动态';
			}
			else {
				this.show_tips = '';
			}
		},
		onLoad() {
			
		},
		onHide(){
			//uni.$off('data_user_info');
			uni.$off('data_circle_tips');
		},
		computed: {
			myPhoto(){
				return _data.staticPhoto() + this.my_data.photo;
			},
		},
		methods: {
			goPath(path) {
				if(path){
					uni.navigateTo({
						url: path,
					});
				}
			},
		},
		watch: {
			
		},
	}
</script>

<style>
	
	.phto {
		width: 106upx;
		height: 106upx;
		margin-left: 30upx;
		margin-right: 30upx;
	}
	
	.qrcode {
		width: 50upx;
		height: 50upx;
		margin-right: -20upx;
	}
	
	.my_padding {
		padding-bottom: 25px;
	}
	
	.my_padding:before {
		background-color:white;
	}
	
	.uni-list {
		margin-bottom: 30upx;
	}
	
</style>
