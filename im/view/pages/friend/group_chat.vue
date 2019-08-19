<template>
	<view class="page">
		
		<uni-list v-if="list.length">
			<!-- <uni-list-item :title="value.show_name" 
						   :show-arrow="false"
						   :note="msgHandle(value.last_msg + '')"
						   :show-badge="false"
						   :badge-type="'default'"
						   :thumb="staticPhoto + value.photo_path"
						   v-for="(value,key) in list"
						   :key="key"
						   @tap="goPath(value.list_id)"
						   v-if="value.type == 1"
						   />
	-->
					   
			<view class="uni-media-list btm_border" v-for="(value,key) in list" v-if="value.type == 1" @tap="goPath(value.list_id)">
				<view class="uni-media-list-logo">
			    	<image :src="staticPhoto + value.photo_path" class="img-icon" />
			    	
			    </view>
			    <view >
			    	<view class="">{{value.show_name}}</view>
					<view class=" content">{{msgHandle(value.last_msg + '')}}</view>
			    </view>
				
			</view>	   			   
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
			uniListItem,
		},
		data() {
			return {
				list: [],
			}
		},
		onShow(){
			_hook.routeSonHook();
			
			let _this = this,
				chat_list = _data.localData('chat_list');
				
			/** 监听最新数据 */
			uni.$on('data_chat_list',function(data){
				_this.list = data;
			});
			
			/** 加载本地缓存数据，让页面秒速渲染出来 */
			if(chat_list){
				_this.list = chat_list;
			}else{
				_get.getChatList();
			}
		},
		onLoad() {
			
		},
		onUnload(){
			uni.$off('data_chat_list');
		},
		computed: {
			staticPhoto(){
				return _data.staticPhoto();
			},
		},
		methods: {
			goPath(list_id){
				uni.navigateTo({
					url: '../chat/message?list_id=' + list_id,
				});
			},
			msgHandle(msg){
				return msg.replace(/&lt;/g,'<');
			},
		},
		watch: {
			
		},
	}
</script>

<style scoped>
 .img-icon{
		width:85upx;
		height: 85upx;
		border-radius: 10upx;
	}
	.uni-list-cell-navigate {
		padding: 0;
	}
	.content{
		color:#7D7D72;
		font-size: 28upx;
	}
	.badge-text{
		background-color: #EEEEEE;
		padding: 3upx  10upx 0 10upx;
		border-radius: 15upx;
		font-size: 22upx;
	}
	
	
	.btm_border{
		position: relative;
		display: flex;
		flex-direction: row;
		justify-content:flex-start;
		align-items: flex-start;	
	}
	.btm_border::after {
		position: absolute;
	     z-index: 3;
		right: 0;
		bottom: 0;
		left: 115upx;
		height: 1px;
		content: '';
		-webkit-transform: scaleY(.5);
		transform: scaleY(.5);
		background-color: #c8c7cc;
	}
</style>