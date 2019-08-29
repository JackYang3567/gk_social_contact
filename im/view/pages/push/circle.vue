<template>
	<view id="moments" class="page">
			
		<view class="home-pic">
			<avatar 
				selWidth="600upx" 
				selHeight="600upx" 
				@upload="upload"
				:avatarSrc="circleImg"
				avatarStyle="width: 750upx; height: 500upx; border-radius: 0upx;"
				/>
			</avatar>
			<view class="home-pic-base">
				<view class="top-pic">
					<image class="header" :src="myPhoto" @tap.stop="goDetails(my_data.id)" :lazy-load="true" />
				</view>
				<view class="top-name">{{ my_data.nickname }}</view>
			</view>
		</view>
		
		<view class="new_msg" @tap="goCircleChat" v-if="no_reader_msg">你有新的消息</view>

		<view class="moments__post" v-for="(post,index) in posts" :key="index" :id="'post-'+index">
			<view class="post-left">
				<image class="post_header" :src="staticPhoto + post.header_image + '?_=' + imgRan" @tap="goDetails(post.uid)" :lazy-load="true"/>
			</view>

			<view class="post_right">
				<text class="post-username" @tap="goDetails(post.uid)">{{post.username}}</text>
				<view id="paragraph" class="paragraph" @tap="init_input()">{{post.content.text}}</view>
				
				<!-- 相册 -->
				<view class="thumbnails" v-if="post.type == 0" @tap="init_input()">
					<view :class="post.content.value.length === 1 ? 'my-gallery' : 'thumbnail' " 
					v-for="(image, index_images) in post.content.value" 
					:key="index_images">
						<image class="gallery_img"
						:lazy-load="true"
						mode="aspectFill"
						:src="staticPath + post.post_id +'/' + image"
						:data-src="image"
						@tap="previewImage(index,index_images)"
						/>
					</view>
				</view>
				
				<!-- 资料条 -->
				<view class="toolbar">
					<view class="timestamp">{{ post.timestamp }}</view>
					<view class="like" @tap="like(index)">
						<image :src="post.islike === 0 ? '../../static/theme/default/push/circle/islike.png' : '../../static/theme/default/push/circle/like.png'" :lazy-load="true"/>
					</view>
					<view class="comment" @tap="comment(index)">
						<image src="../../static/theme/default/push/circle/comment.png" :lazy-load="true"></image>
					</view>
				</view>
				<!-- 赞／评论区 -->
				<view class="post-footer">
					<view class="footer_content" v-if="post.like.length">
						<image class="liked" src="../../static/theme/default/push/circle/liked.png" :lazy-load="true"></image>
						<text class="nickname" 
						v-for="(user,index_like) in post.like" 
						:key="index_like"
						@tap="goDetails(user.uid)"
						>
							{{(index_like ? ',' : '' ) + user.username}}
						</text>
					</view>
					<view class="footer_content" 
						v-for="(comment,comment_index) in post.comments" 
						:key="comment_index" 
						@tap="reply(index,comment_index)">
						
						<text class="comment-nickname" style="word-break:break-all;">
							{{comment.username + comment.reply + ': '}}
							<text class="comment-content">
								{{comment.content}}
							</text>
						</text>
						
					</view>
				</view>
			</view>
			<!-- 结束 post -->
		</view>

		<view class="foot" v-show="showInput">
			<chat-input @send-message="send_comment" @blur="blur" :focus="focus" :placeholder="input_placeholder"></chat-input>
			<!-- <chat-input @send-message="send_comment" @blur="blur" :placeholder="input_placeholder"></chat-input> -->
		</view>
		<view class="uni-loadmore" v-if="showLoadMore">{{loadMoreText}}</view>
	</view>

</template>

<script>
	import chatInput from './circle/chat_input.vue'; //输入消息框
	import _get from '../../common/_get';
	import _hook from '../../common/_hook';
	import avatar from "../../components/yq-avatar/yq-avatar.vue";
	import _data from '../../common/_data';
	
	export default {
		components: {
			chatInput,
			avatar,
		},
		data() {
			return {
				my_data: {
					id: 0,
					photo: '',
					nickname: '',
					photo: 'default_man/90.jpg',
					circle_img: 'default_circle_img.jpg',
				},
				user_id: 0,
				
				posts: [],// 模拟数据
				index: '',
				comment_index: '',
				input_placeholder: '评论', //占位内容
				focus: false, //是否自动聚焦输入框
				is_reply: false, //回复还是评论
				showInput: false, //评论输入框
				screenHeight: '', //屏幕高度(系统)
				platform: '',
				windowHeight: '' ,//可用窗口高度(不计入软键盘)
				loadMoreText: "加载中...",
				showLoadMore: false,
				is_more: false,
				no_reader_msg: 0,
				like_status: false,
			}
		},
		computed:{
			myPhoto(){
				return _data.staticPhoto() + this.my_data.photo + '?_=' + this.imgRan;
			},
			circleImg(){
				return _data.staticPhoto() + this.my_data.circle_img;
			},
			staticPath(){
				return _data.staticCircle();
			},
			staticPhoto(){
				return _data.staticPhoto();
			},
			imgRan(){
				return Math.random();
			},
		},
		onLoad(option) {
			let _this = this;
			
			uni.getSystemInfo({ //获取设备信息
				success: (res) => {
					_this.screenHeight = res.screenHeight;
					_this.platform = res.platform;
				}
			});
			
		},
		onShow() {
			_hook.routeSonHook();
			
			let _this = this,
			circle_data = _data.localData('circle_data');
			/** 加载本地缓存数据，让页面秒速渲染出来 */
			if(circle_data){
				_this.posts = circle_data;
				if(_data.data('no_reader_circle') || _data.data('no_reader_circle_chat_num')){
					this.posts = [];
					_data.data('circle_data',[]);
					this.pullDownRefresh();
				}
			}
			else{
				_this.pullDownRefresh();
			}
			
			this.no_reader_msg = _data.data('no_reader_circle_chat_num') ? 1 : 0;
			
			_data.data('no_reader_circle',0);
			
			// 监听窗口尺寸变化,窗口尺寸不包括底部导航栏
			uni.onWindowResize((res) => { 
				if(this.platform === 'ios'){
					this.windowHeight = res.size.windowHeight;
					this.adjust();
				}else{
					if (this.screenHeight - res.size.windowHeight > 60 && this.windowHeight <= res.size.windowHeight) {
						this.windowHeight = res.size.windowHeight;
						this.adjust();
					}
				}
			});			
			/** 监听最新数据 */
			uni.$on('data_circle_data',function(data){
				_this.posts = data;
			});		
			/** 监听新的个人数据 */
			uni.$on('data_user_info',function(data){
				_this.my_data = data;
			});
			
			/** 监听朋友圈动态提示 */
			uni.$on('data_circle_tips',function(data){
				if(!isNaN(data)){
					_this.no_reader_msg = 1;
				};
			});
			
			_this.my_data = _data.data('user_info');
		},
		onUnload() {
			uni.$off('data_circle_tips');
			uni.$off('data_user_info');
			uni.$off('data_circle_data');
			uni.offWindowResize(); //取消监听窗口尺寸变化
			this.max = 0,
			this.data = [],
			this.loadMoreText = "加载更多",
			this.showLoadMore = false;
		},
		onReachBottom() { //监听上拉触底事件
			let _this =this;
			_this.showLoadMore = true;
			if(_this.is_more){
				return;
			}
			let time = 0,
			is_length = _this.posts.length;
			if(is_length){
				time = _this.posts[is_length - 1].time;
			}
			_get.getCircleList({
				time: time,
				type: 1,
				user_id: 0
			},(data) => {
				_this.my_data = data.user_info;
				if(data.data.length < 10){
					this.loadMoreText = '没有更多数据了';
					this.is_more = true;
				}
			});
		},
		onPullDownRefresh() { //监听下拉刷新动作
			if(_data.data('no_reader_circle') || _data.data('no_reader_circle_chat_num')){
				this.posts = [];
				_data.data('circle_data',[]);
			}
			this.pullDownRefresh();
		},
		onNavigationBarButtonTap(e) {//监听标题栏点击事件
			if (e.index == 0) {
				this.navigateTo('./circle/send');
			}
		},
		methods: {
			upload(e){
				let _this = this;
				_this.$httpSendFile({
					local_url: e.path,
					type: 3,
					success(data){
						_this.$httpSend({
							path: '/im/action/upCircleImg',
							success(data) {
								_get.base();
								uni.showToast({
									title: '更换成功',
									duration: 2000
								});
							}
						});
					},
				});
			},
			goCircleChat(){
				_data.data('no_reader_circle_chat_num',0);
				this.navigateTo('./circle_chat_details');
			},
			pullDownRefresh() {
				let _this = this;
				//初始化数据
				let time = 0;
				if(this.posts.length){
					time = this.posts[0].time;
				}
				_get.getCircleList({
					time: time,
					type: 0,
					user_id: 0,
				},(data) => {
					_this.my_data = data.user_info;
				});
				uni.stopPullDownRefresh(); //停止下拉刷新
				/** 取消好友动态提示 */
				_data.data('no_reader_circle',0);
			},
			goDetails(user_id){
				this.navigateTo('../details/index?user_id=' + user_id);
			},
			navigateTo(url) {
				uni.navigateTo({
					url: url
				});
			},
			like(index) {
				let _this = this;
				if(this.like_status){
					return;
				}
				this.like_status = true;
				_this.$httpSend({
					path: '/im/circle/likeAction',
					data: {
						id: _this.posts[index].post_id,
					},
					success(data) {
						_this.posts[index].islike = data.action;
						if (data.action) {
							_this.posts[index].like.push({
								"uid": _this.my_data.id,
								"username": _this.my_data.nickname,
							});
						} 
						else {
							let likes = [];
							for(let i = 0,j = _this.posts[index].like.length;i < j; i ++){
								if(_this.posts[index].like[i].uid == _this.my_data.id){
									_this.posts[index].like.splice(i, 1);
									_data.data('circle_data',_this.posts);
									break;
								}
							}
						}
						_this.like_status = false;
					}
				});
			},
			comment(index) {
				if(this.showInput){
					this.showInput = false;
					this.focus = false;
					this.index = '';
				}else{
					this.showInput = true; //调起input框
					this.focus = true;
					this.index = index;
				}
			},
			adjust() { //当弹出软键盘发生评论动作时,调整页面位置pageScrollTo

				uni.createSelectorQuery().selectViewport().scrollOffset(res => {
					var scrollTop = res.scrollTop;
					let view = uni.createSelectorQuery().select("#post-" + this.index);
					view.boundingClientRect(data => {
						
						// console.log("data:" + JSON.stringify(data));
						// console.log("手机屏幕高度:" + this.screenHeight);
						// console.log("竖直滚动位置" + scrollTop);
						// console.log("节点离页面顶部的距离为" + data.top);
						// console.log("节点高度为" + data.height);
						// console.log("窗口高度为" + this.windowHeight);

						uni.pageScrollTo({
							scrollTop: scrollTop - (this.windowHeight - (data.height + data.top + 45)), //一顿乱算
							// scrollTop: 50, 
							duration: 300
						});
					}).exec();
				}).exec();
			},
			reply(index, comment_index) {
				this.is_reply = true; //回复中
				this.showInput = true; //调起input框
				let replyTo = this.posts[index].comments[comment_index].username;
				this.input_placeholder = '回复' + replyTo;
				this.index = index; //post索引
				this.comment_index = comment_index; //评论索引
				this.focus = true;
			},
			blur: function() {
				//this.init_input();
			},
			send_comment: function(message) {
				let _this = this,
				is_posts_obj = this.posts[this.index],
				chat_user_id = is_posts_obj.uid,
				reply = '';
				if (this.is_reply) {
					let is_reply_obj = is_posts_obj.comments[this.comment_index];
					chat_user_id = is_reply_obj.uid;
					if(is_posts_obj.uid != chat_user_id){
						reply = '回复' + is_reply_obj.username;
					}
				}
				_this.$httpSend({
					path: '/im/circle/comment',
					data: {
						message,
						id: is_posts_obj.post_id,
						chat_user_id,
					},
					success(data) {
						is_posts_obj.comments.push({
							"uid": _this.my_data.id,
							'reply': reply,
							"username": _this.my_data.nickname,
							"content": message
						});
						_this.init_input();
					}
				});
			},
			init_input() {
				this.showInput = false;
				this.focus = false;
				this.input_placeholder = '评论';
				this.is_reply = false;
			},
			previewImage(index, image_index) {
				let data = this.posts[index],
				images_all = [];
				for(let i = 0,j = data.content.value.length;i<j;i++){
					images_all.push(this.staticPath + data.post_id + '/' + data.content.value[i].replace('_thumb',''));
				}
				var current = images_all[image_index];
				uni.previewImage({
					current: current,
					urls: images_all
				});
			},
			goPublish() {
				uni.navigateTo({
					url: './circle/send',
					success: res => {},
					fail: () => {},
					complete: () => {}
				});
			}
		},
		watch: {
			
		},
	}
</script>

<style scoped>
	@import url("../../static/css/push/circle.css");
	.new_msg {
		text-align: center;
		color: #36648B;
		font-weight: bold;
	}
</style>