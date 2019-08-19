<template>
	<view class="page">
		<view class="header" v-if="0">
			<view class="input-view">
				<uni-icon type="search" size="22" color="#666666"></uni-icon>
				<input class="input" type="text" placeholder="输入搜索关键词" :disabled="true" />
			</view>
		</view>
		<view class="uni-list" v-if="list.length">
			
				<view class="uni-list-cell" v-for="(value,key) of list" :key="key">
					<uni-swipe-action :options="swipeData(value.no_reader_num)" @click="swipeAction" @opened="openedAction(key)">
						<view class="uni-media-list" @tap="goMessage(value.list_id,key,value.no_reader_num)">
							<view class="uni-media-list-logo">
								<image :src="staticPhoto + value.photo_path + '?_=' + imgRan" :lazy-load="true" style="border-radius: 12upx;" />
							</view>
							
							<view class="red_num">
								<uni-badge :text="value.no_reader_num" type="error" />
							</view>
							
							<view class="uni-media-list-body">
								<view class="uni-media-list-text-top">
									{{value.show_name}}
									<view style="float: right;color: #8f8f94;font-size: 23upx;">
										{{timestampFormat(value.time)}}
									</view>
								</view>
								<view class="uni-media-list-text-bottom uni-ellipsis">{{msgHandle(value.last_msg+'')}}</view>
							</view>
							
						</view>
					</uni-swipe-action>
				</view>
		</view>
		
		<view :class="['action_main animated',(action_menu ? 'bounceInDown' : 'bounceOutUp')]" v-show="showActionMenu" @tap="actionMain">
			<view class="action_base" >
				
				<view class="sj"></view>
				
				<view class="action_item" @tap="goAction(0)">
					<view class="uni-media-list-logo action_icon">
						<image :src="'../../static/theme/default/chat/chat.png'" :lazy-load="true"/>
					</view>
					<text class="action_item_text">发起群聊</text>
				</view>
				
				<view class="action_item" @tap="goAction(1)">
					<view class="uni-media-list-logo action_icon">
						<image :src="'../../static/theme/default/chat/add_friend.png'" :lazy-load="true"/>
					</view>
					<text class="action_item_text">添加好友</text>
				</view>
				
				<!-- #ifdef APP-PLUS -->
				<view class="action_item" @tap="goScanCode">
					<view class="uni-media-list-logo action_icon">
						<image :src="'../../static/theme/default/chat/scan_code.png'" :lazy-load="true"/>
					</view>
					<text class="action_item_text">扫一扫</text>
				</view>
				<!-- #endif -->
				
				<view class="action_item" v-if="0">
					<view class="uni-media-list-logo action_icon">
						<image :src="'../../static/theme/default/chat/in_out.png'" :lazy-load="true"/>
					</view>
					<text class="action_item_text">收付款</text>
				</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	
	import uniIcon from '@dcloudio/uni-ui/lib/uni-icon/uni-icon.vue';
	import uniBadge from '@dcloudio/uni-ui/lib/uni-badge/uni-badge.vue';
	import uniSwipeAction from '@dcloudio/uni-ui/lib/uni-swipe-action/uni-swipe-action.vue'
	import _get from '../../common/_get';
	import _hook from '../../common/_hook';
	import _action from '../../common/_action';
	import _data from '../../common/_data';
	import animate from 'animate.css';
	
	export default {
		components: {
			uniIcon,
			uniBadge,
			uniSwipeAction,
		},
		data() {
			return {
				list: [],
				list_index: '',
				action_menu: false,
				action_menu_num: 0,
			}
		},
		onShow(){
			_hook.routeTabBarHook();
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
			
			_this.action_menu = false;
			_this.action_menu_num = 0;
		},
		onLoad() {
			
		},
		onHide(){
			//uni.$off('data_chat_list');
		},
		computed: {
			showActionMenu(){
				return this.action_menu_num > 0;
			},
			staticPhoto(){
				return _data.staticPhoto();
			},
			imgRan(){
				return Math.random();
			},
		},
		methods: {
			msgHandle(msg){
				return msg.replace(/&lt;/g,'<');
			},
			timestampFormat(time){
				return _action.timestampFormat(time);
			},
			goAction(type){
				let path = '';
				switch (type){
					case 0:
						path = '../friend/index_list?list_id=0';
						break;
					case 1:
						path = '../friend/add';
						break;
					default:
						return;
						break;
				}
				uni.navigateTo({
					url: path,
					animationType: 'slide-in-bottom',
				});
			},
			actionMain(){
				this.action_menu = false;
				this.action_menu_num ++;
			},
			openedAction(key){
				this.list_index = key;
			},
			swipeAction(e){				
				let _this = this,
				list_id = _this.list[_this.list_index].list_id;
				if(!list_id){
					return;
				}
				switch (e.index){
					case 0:
						/** 标为未/已读 */
						_this.$httpSend({
							path: '/im/chat/sign ',
							data: { list_id,no_reader_num: _this.list[_this.list_index].no_reader_num },
							success: () => {
								_get.getChatList();
							}
						});
						break;
					case 1:
						/** 删除这条对话 */
						_this.$httpSend({
							path: '/im/chat/deleteChat',
							data: { list_id, },
							success: (data) => {
								_get.getChatList();
							}
						});
						break;
					default:
						break;
				}
			},
			swipeData(no_reader_num){
				return [
					{
						text: (no_reader_num ? '标为已读' : '标为未读'),
						style: {
							
						},
					}, 
					{
						text: '删除',
						style: {
							backgroundColor: 'rgb(255,58,49)',
						}
					}
				];
			},
			goMessage(list_id,key,no_reader_num){
				if(no_reader_num > 0) {
					_get.getChatData({
						send_data: {
							list_id: list_id,
							time: 0,
					is_up: 1,
						},
						is_action_data: 1,
					});
					this.list[key].no_reader_num = 0;
					_data.localData('chat_list',this.list);
					_action.updataNoReader(list_id);
					_action.setStatusTips();
				}
				_data.localData('message_list_id',list_id);
				uni.navigateTo({
					url: './message?list_id=' + list_id,
				});
			},
			goScanCode(){
				let _this = this;
				uni.scanCode({
					success: function (res) {
						/** 验证必须是一个地址 */
						if(/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\*\+,;=.]+$/.test(res.result)){
							/** 本应用页面 */							
							if(new RegExp(_data.data('http_url')).test(res.result)){								
								if(res.result.match(/\/([a-z]+_[a-z]+)\/(.+)$/) && RegExp.$1 && RegExp.$2){
									switch (RegExp.$1){
										case 'chat_add':
											uni.navigateTo({
												url: '../details/index?user_id=' + RegExp.$2 + '&is_type=3',
											});
											break;
										case 'group_add':
											let option = (RegExp.$2 + '').split('&');
											_this.$httpSend({
												path: '/im/message/addChat',
												data: { users: option[1],list_id: option[0],type: 1, },
												success_action: true,
												success(res) {
													let tips = res.err ? res.msg : '已经申请加入群聊,请耐心等待群管理审核';													
													uni.showModal({
														content: tips,
														showCancel: false,
													});
												}
											});
											break;
										default:
											uni.showModal({
												content: '二维码内容：' + res.result,
												showCancel: false,
											});
											break;
									}
								}
								else {
									uni.showModal({
										content: '二维码内容：' + res.result,
										showCancel: false,
									});
								}
							}
							/** 打开新地址 */
							else {
								uni.navigateTo({
									url: '../push/web?url=' + encodeURIComponent(res.result),
								});
							}
						}
						else {
							uni.showModal({
								content: '二维码内容：' + res.result,
								showCancel: false,
							});
						}
					},
					fail(e){
						return;
						uni.showToast({
							title: '扫码错误：' + JSON.stringify(e),
							duration: 2000,
						});
					},
				});
			},
		},
		watch: {
			
		},
		onNavigationBarButtonTap(e) {
			this.action_menu = !this.action_menu;
			this.action_menu_num ++;
		},
	}
</script>

<style>
	
	.uni-media-list-logo {
		height: 85upx;
		width: 85upx;
	}
	
	.header {
		display: flex;
		flex-direction: row;
		padding: 10px 15px;
		align-items: center;
	}

	.input-view {
		display: flex;
		align-items: center;
		flex-direction: row;
		background-color: #e7e7e7;
		height: 30px;
		border-radius: 5px;
		padding: 0 10px;
		flex: 1;
	}

	.input {
		flex: 1;
		padding: 0 5px;
		height: 24px;
		line-height: 24px;
		font-size: 16px;
	}
	
	.red_num {
		position: absolute;
		height: 34upx;
		top: 7upx;
		left: 95upx;
		font-size: 23upx !important;
	}
	
	/**
	.uni-media-list-body {
		height: auto;
	}
	*/
   
   .uni-swipe-action {
		width: 750upx !important;
	}
   
   .action_main {
	   position: fixed;
	   
	   top: 55px;
	   
	   /* #ifndef H5 */
	   top: 15px;
	   /* #endif */
	   
	   width: 750upx;
	   height: 1080upx;
	   z-index: 100;
   }
   
   .action_base {
	   position: absolute;
	   right: 15upx;
	   width: 300upx;
	   background: #36363d;
	   border-radius: 10upx;
   }
   
   .sj {
	   position: fixed;
	   top: -15upx;
	   width: 0;
	   height: 0;
	   right: 35upx;
	   border-left: 20upx solid transparent;
	   border-right: 20upx solid transparent;
	   border-bottom: 20upx solid #36363d;
   }
   
   .action_item {
	   color: #e5e5e5;
	   height: 100upx;
	   line-height: 100upx;
	   margin-left: 45upx;
	   border-bottom: 1px solid #3e3e3e;
   }
   
   .action_icon {
	   width: 45upx;
	   height: 45upx;
	   display: inline-block;
	   vertical-align: middle;
   }
   
   .action_item:last-child{
   	   border: none;
   }
   
   .action_item_text {
	   font-size: 30upx;
   }
   
</style>