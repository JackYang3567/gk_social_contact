<template>
	<view class="page">
		
		<view class="uni-list my_padding">
			 <navigator url="/pages/my/details" hover-class="navigator-hover">
				<view class="uni-list-cell">
					<view class="uni-media-list uni-list-cell-navigate uni-navigate-right">
						<view class="uni-media-list-logo phto">
							<image :src="myPhoto" :lazy-load="true"/>
						</view>
						<view class="uni-media-list-body">
							<view class="uni-media-list-text-top">{{my_data.nickname}}</view>
							<view class="uni-media-list-text-bottom uni-ellipsis">{{my_data.doodling}}</view>
						</view>
					</view>
				</view>
			</navigator>
		</view>
		
		<uni-list>
			<uni-list-item title="支付" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/pay.png"
						   @click="goPath()"
						   />
		</uni-list>
		
		<uni-list>
			<uni-list-item title="小程序" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/push/program.png"
						   />
						   
			<uni-list-item title="相册" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/images.png"
						   @click="goPath()"
						   />
			<uni-list-item title="实名绑定" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/real.png"
						   @click="goPath()"
						   />
			<uni-list-item title="表情" 
						   :show-arrow="true"
						   thumb="../../static/theme/default/my/emoji.png"
						   @click="goPath()"
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
				my_data: { id: 0 },
			}
		},
		onShow(){
			_hook.routeTabBarHook();
		},
		onLoad() {
			this.my_data = this.$store.state.user_info;
		},
		computed: {
			myPhoto(){
				return this.$store.getters.staticPhoto + this.$store.state.user_info.photo;
			},
			userInfo(){
				return this.$store.state.user_info;
			}
		},
		methods: {
			goPath(path) {
				if(path){
					uni.navigateTo({
						url: path
					});
				}
			},
		},
		watch: {
			userInfo(new_val,old_val){
				this.my_data = new_val;
			}
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
