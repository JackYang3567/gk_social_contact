<script>
	
	import _action from './common/_action';
	import _get from './common/_get';
	import _data from './common/_data';
	
	export default {
		globalData: {
			/** 代理客户id */
			agent_id: 0,
			/** http 服务端地址 */
			http_url: 'http://http.767717.com:881',
			/** 静态文件存放地址 */
			static_url: 'http://http.767717.com:881',
			/** socket 服务端地址 */
			socket_url: 'ws://socket.767717.com:8383',
			
			/** socket 连接状态 */
			socket_state: 0,
			/** 好友申请通知 */
			new_friend_tips_num: 0,
			/** 群认证通知 */
			new_group_tips_num: 0,
			/** 朋友圈通知 */
			no_reader_circle: 0,
			/** 朋友圈消息未读数 */
			no_reader_circle_chat_num: 0,
			/** 缓存的数据 */
			cache: {
				/** 个人头像缓存数据 */
				local_photo: '',
			},
			/** 用户信息 */
			user_info: {
				id: 0,
				nickname: '',
				username: '',
				photo: 'default_man/70.jpg',
				doodling: '',
				circle_img: 'default_circle_img.jpg?_=3.1415926',
			},
		},
		onLaunch() {
			
			// #ifdef APP-PLUS
			
			/** 锁定屏幕方向 */
			plus.screen.lockOrientation('portrait-primary');
			
			/** 检测升级 */
			let _this = this;
			
			plus.runtime.getProperty(plus.runtime.appid, function(info) {
				_this.$httpSend({
					path: '/im/app/update',
					data: {
						appid: info.appid,
						version: info.version,
					},
					success(res){
						if(res.status) {
							_action.checkFail();
							let wgtWaiting = plus.nativeUI.showWaiting("更新开始下载"),
							update_url = (plus.os.name == 'Android' ? res.update_url.android : res.update_url.ios),
							downloadTask = uni.downloadFile({
							    url: update_url,
							    success: (res) => {
									wgtWaiting.close();
									if (res.statusCode === 200 ) {
										plus.runtime.install(res.tempFilePath,{},() => {
											plus.runtime.restart();
										},(e) => {
											uni.showModal({
												content: "update error [" + e.code + "]：" + e.message,
												showCancel: false,
											});
										});
									} else {
										uni.showModal({
											content: "api error update fail!",
											showCancel: false,
										});
									}
							    }
							});
							downloadTask.onProgressUpdate((res) => {
								wgtWaiting.setTitle('下载中...' + res.progress + '%');
							});
						}
					},
				});
			});
			
			// #endif
			
		},
		onShow() {
			if(!_data.localData('token')){
				return;
			}
			setTimeout(() => {
			   /**
				* 每次app启动都加载最新的会话列表数据，只要是最新的会话列表数据，会话界面数据也会是最新的
				* 这里延时100ms,不然会全局变量没有加载完成，会报错。
				*/
				_get.getChatList();
			},100);
			
			let _this = this;
			
			/**
			 * @param {Object} res
			 * 监听网络变化
			 * 如果有网络变化，断开socket，再重新连接
			 * 重新获取会话列表数据
			 * 如果是在会话界面，再重新获取这个的对话数据
			 */
			uni.onNetworkStatusChange(function (res) {
				/** 断开重新再连接，再获取最新数据 */
				_data.data('socket_state',0);
				uni.closeSocket();
				_this.$socketSend();
				_get.getChatList();
				
				if(_data.localData('message_list_id')){
					_get.getChatData({
						send_data: {
							list_id: _data.localData('message_list_id'),
							time: 0,
							is_up: 1,
						},
						is_action_data: 1,
					});
				}
			});
		},
		onHide() {
			
		}
	}
</script>

<style>
	
	/* #ifndef APP-PLUS-NVUE */
	/** uni.css - 通用组件、模板样式库，可以当作一套ui库应用 */
	@import './static/css/uni.css';
	/** 设置 body 的背景色 */
	page {
		background-color: #F4F5F6;
	}
	/** 导航栏自定义图标样式调整 */
	.uni-page-head .uni-btn-icon {
		min-width: auto !important;
		overflow: inherit !important;
	}
	/* #endif */
	
</style>
