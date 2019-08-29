<template>
	
	<view class="page" style="overflow: hidden;">
		<view class="content" @touchstart="hideDrawer">
			
			<scroll-view 
			class="msg-list" 
			scroll-y="true" 
			:scroll-with-animation="scrollAnimation"
			:scroll-into-view="scrollToView" 
			@scrolltoupper="loadHistory"
			:upper-threshold="30">
				
				<!-- 加载历史数据waitingUI -->
				<view class="loading" v-if="isHistoryLoading">
					<view class="spinner">
						<view class="rect1"></view>
						<view class="rect2"></view>
						<view class="rect3"></view>
						<view class="rect4"></view>
						<view class="rect5"></view>
					</view>
				</view>
				
				<uni-load-more status="noMore" v-if="noMore && !(isHistoryLoading)"/>
				
				<view class="row" 
				v-for="(row,index) in msgList.list" 
				:key="index" 
				:id="'msg' + row.msg.id"
				>
					<!-- 系统消息 -->
					<block v-if="row.type==1" >
						<view class="system">
							<!-- 文字消息 -->
							<view v-if="row.msg.type==0" class="text">
								{{row.msg.content.text}}
							</view>
							<!-- 领取红包消息 -->
							<view v-if="row.msg.type==5" class="red-envelope">
								<image src="/static/img/red-envelope-chat.png" :lazy-load="true"/>
								{{row.msg.content.text}}
							</view>
						</view>
					</block>
					
					<block v-if="row.type == 0 && (index == 0 || (row.msg.time - msgList.list[index - 1].msg.time > 300))">
						<view class="system" style="margin-bottom: 10upx;">
							<!-- 文字消息 -->
							<view class="text" style="background: #F4F5F6;color: #8f8f94;">
								{{ timestampFormat(row.msg.time + '') }}
							</view>
						</view>
					</block>
				
					<!-- 用户消息 -->
					<block v-if="row.type==0">
						<!-- 自己发出的消息 -->
						<view class="my" v-if="row.msg.user_info.uid == my_data.id">
							<!-- 左-消息 -->
							<view class="left" @longtap="msgAction(row.msg.id +'',row.msg.user_info.uid + '')">
								<!-- 文字消息 -->
								<view v-if="row.msg.type==0" class="bubble">
									<rich-text :nodes="row.msg.content.text" />
								</view>
								<!-- 语言消息 -->
								<view v-if="row.msg.type==1" class="bubble voice" @tap="playVoice(row.msg)" :class="playMsgid == row.msg.id?'play':''">
									<view class="length">{{row.msg.content.length}} </view>
									<view class="icon my-voice"></view>
								</view>
								<!-- 图片消息 -->
								<view v-if="row.msg.type==2" class="bubble img" @tap="showPic(row.msg)">
									<image 
									:src="(staticPath + row.msg.content.url)" 
									:style="{'width': row.msg.content.w+'px','height': row.msg.content.h+'px'}"
									:lazy-load="true"
									/>
								</view>
								<!-- 视频消息 -->
								<view v-if="row.msg.type==3" class="bubble img">
									
									<!-- #ifndef H5 -->
									<!-- #endif -->
									
									<!-- #ifdef H5 -->
									<video
									:src="(staticPath + row.msg.content.url)"
									objectFit="cover"
									style="max-height: 200px;"
									/>
									<!-- #endif -->
										
								</view>
								<!-- 红包 -->
								<view v-if="row.msg.type==5" class="bubble red-envelope" @tap="openRedEnvelope(row.msg,index)">
									<image src="/static/img/red-envelope.png" :lazy-load="true"></image>
									<view class="tis">id
										<!-- 点击开红包 -->
									</view>
									<view class="blessing">
										{{row.msg.content.blessing}}
									</view>
								</view>
								
							</view>
							<!-- 右-头像 -->
							<view class="right" @tap="goDetails(row.msg.user_info.uid)">
								<image :src="myPhoto" :lazy-load="true"/>
							</view>
						</view>
						
						<!-- 别人发出的消息 -->
						<view class="other" v-if="row.msg.user_info.uid != my_data.id">
							<!-- 左-头像 -->
							<view class="left" @tap="goDetails(row.msg.user_info.uid)">
								<image :src="staticPhoto + row.msg.user_info.face + '?_=' + imgRan" :lazy-load="true" />
							</view>
							<!-- 右-用户名称-时间-消息 -->
							<view class="right" @longtap="msgAction(row.msg.id +'',row.msg.user_info.uid + '')">
								<!-- <view class="username">
									<view class="name">{{row.msg.user_info.username}}</view> <view class="time">{{row.msg.time}}</view>
								</view> -->
								<!-- 文字消息 -->
								<view v-if="row.msg.type==0" class="bubble">
									<rich-text :nodes="row.msg.content.text" />
								</view>
								<!-- 语音消息 -->
								<view v-if="row.msg.type==1" class="bubble voice" @tap="playVoice(row.msg)" :class="playMsgid == row.msg.id?'play':''">
									<view class="icon other-voice"></view>
									<view class="length">{{row.msg.content.length}}</view>
								</view>
								<!-- 图片消息 -->
								<view v-if="row.msg.type==2" class="bubble img" @tap="showPic(row.msg)">
									<image 
									:src="(staticPath + row.msg.content.url)"
									:style="{'width': row.msg.content.w+'px','height': row.msg.content.h+'px'}"
									:lazy-load="true"
									/>
								</view>
								<!-- 视频消息 -->
								<view v-if="row.msg.type==3" class="bubble img">
									
									<!-- #ifndef H5 -->
									<!-- #endif -->
									
									<!-- #ifdef H5 -->
									<video 
									:src="(staticPath + row.msg.content.url)"
									objectFit="cover"
									style="max-height: 200px;"
									/>
									<!-- #endif -->
									
								</view>
								<!-- 红包 -->
								<view v-if="row.msg.type==5" class="bubble red-envelope" @tap="openRedEnvelope(row.msg,index)">
									<image src="/static/img/red-envelope.png" :lazy-load="true"></image>
									<view class="tis">
										<!-- 点击开红包 -->
									</view>
									<view class="blessing">
										{{row.msg.content.blessing}}
									</view>
								</view>
							</view>
						</view>
					</block>
				</view>
			</scroll-view>
		</view>
		
		<!-- 抽屉栏 -->
		<view class="popup-layer" :class="popupLayerClass" @touchmove.stop.prevent="discard">
			<!-- 表情 --> 
			<swiper class="emoji-swiper" :class="{hidden:hideEmoji}" indicator-dots="true" duration="150">
				<swiper-item v-for="(page,pid) in emojiList" :key="pid">
					<view v-for="(em,eid) in page" :key="eid" @tap="addEmoji(em)">
						<image :src="'/static/img/emoji/' + em.url" :lazy-load="true" />
					</view>
				</swiper-item>
			</swiper>
			<!-- 更多功能 相册-拍照-红包 -->
			<view class="more-layer" :class="{hidden:hideMore}">
				<view class="list">
					
					<view class="box" @tap="chooseImage">
						<view class="icon tupian2"></view>
						<view class="tool_text">相册</view>
					</view>		
					
					<view class="box" @tap="camera">
						<view class="icon paizhao"></view>
						<view class="tool_text">拍摄</view>
					</view>
					
					<view class="box" @tap="video" v-if="0">
						<image :src="'/static/theme/default/chat/video.png'" :lazy-load="true"
							style="width:60upx;height:55upx;"
						 />
						 <view class="tool_text">视频</view>
					</view>
					
					<!-- <view class="box" @tap="handRedEnvelopes">
						<view class="icon hongbao"></view>
						 <view class="tool_text">红包</view>
					</view> -->
					
				</view>
				
			</view>
		</view>
		
		<view class="input-box" v-if="msgList.is_msg">
			<view style="text-align: center !important;width:750upx;line-height: 100upx;border-top: #c9c9c9 solid 2upx;">
				禁 言 中 ...
			</view>
		</view>
		
		<!-- @touchmove.stop.prevent="discard" -->
		
		<!-- 底部输入栏 -->
		<view class="input-box" :class="popupLayerClass" v-else>
			
			<!-- H5下不能录音，输入栏布局改动一下 -->
			<!-- #ifndef H5 -->
			<view class="voice">
				<view class="icon" :class="isVoice?'jianpan':'yuyin'" @tap="switchVoice"></view>
			</view>
			<!-- #endif -->
			
			<!-- #ifdef H5 -->
			<view class="more" @tap="showMore">
				<view class="icon add"></view>
			</view>
			<!-- #endif -->
							
			<view class="textbox">
				<view class="voice-mode" 
				:class="[isVoice?'':'hidden',recording?'recording':'']" 
				@touchstart="voiceBegin" 
				@touchmove.stop.prevent="voiceIng" 
				@touchend="voiceEnd" 
				@touchcancel="voiceCancel">
					{{voiceTis}}
				</view>
				<view class="text-mode"  :class="isVoice?'hidden':''">
					
					<view class="box">
						<textarea
						auto-height="true"
						v-model="textMsg"
						@focus="textareaFocus"
						:maxlength="-1"
						:show-confirm-bar="false"
						style="max-height: 190upx;overflow:auto"
						/>
					</view>
					
					<view class="em" @tap="chooseEmoji">
						<view class="icon biaoqing"></view>
					</view>
				</view>
			</view>
			
			<!-- #ifndef H5 -->
			<view class="more" @tap="showMore">
				<view class="icon add"></view>
			</view>
			<!-- #endif -->
			
			<view class="send" :class="isVoice?'hidden':''" @tap="sendText">
				<view class="btn">发送</view>
			</view>
		</view>
		
		<!-- #ifndef H5 -->
		<!-- 录音UI效果 -->
		<view class="record" :class="recording?'':'hidden'">
			<view class="ing" :class="willStop?'hidden':''"><view class="icon luyin2" ></view></view>
			<view class="cancel" :class="willStop?'':'hidden'"><view class="icon chehui" ></view></view>
			<view class="tis" :class="willStop?'change':''">{{recordTis}}</view>
		</view>
		<!-- #endif -->
		
		<!-- 红包弹窗 -->
		<view class="windows" :class="windowsState" v-if="0">
			<!-- 遮罩层 -->
			<view class="mask" @touchmove.stop.prevent="discard" @tap="closeRedEnvelope"></view>
			<view class="layer" @touchmove.stop.prevent="discard">
				<view class="open-redenvelope">
					<view class="top">
						<view class="close-btn">
							<view class="icon close" @tap="closeRedEnvelope"></view>
						</view>
						<image src="/static/img/im/face/face_1.jpg" :lazy-load="true"></image>
					</view>
					<view class="from">来自{{redenvelopeData.from}}</view>
					<view class="blessing">{{redenvelopeData.blessing}}</view>
					<view class="money">{{redenvelopeData.money}}</view>
					<view class="showDetails" @tap="toDetails(redenvelopeData.rid)">
						查看领取详情 <view class="icon to"></view>
					</view>
				</view>
			</view>
		</view>
		
	</view>
</template>
<script>
	
	import uniLoadMore from "@dcloudio/uni-ui/lib/uni-load-more/uni-load-more.vue";
	import emoj_data from '../../static/js/message/emoji_data.js';
	import _get from '../../common/_get';
	import _hook from '../../common/_hook';
	import _action from '../../common/_action';
	import _data from '../../common/_data';
	
	export default {
		components: {
			uniLoadMore,
		},
		data() {
			return {
				//文字消息
				textMsg: '',
				//消息列表
				isHistoryLoading: false,
				scrollAnimation: false,
				scrollToView: '',
				msgList: {
					show_name: '',
					list: [],
					type: 0,
					is_msg: 0,
					is_action: 0,
				},
				msgImgList: [],
				noMore: 0,
				
				//录音相关参数
				// #ifndef H5
				//H5不能录音
				RECORDER: uni.getRecorderManager(),
				// #endif
				
				isVoice: false,
				voiceTis: '按住 说话',
				recordTis: "手指上滑 取消发送",
				recording: false,
				willStop: false,
				initPoint: { identifier:0, Y:0 },
				recordTimer: null,
				recordLength: 0,
				
				//播放语音相关参数
				AUDIO: uni.createInnerAudioContext(),
				playMsgid: null,
				VoiceTimer: null,
				// 抽屉参数
				popupLayerClass: '',
				// more参数
				hideMore: true,
				//表情定义
				hideEmoji: true,
				emojiList: emoj_data.emoji_list,
				
				//表情图片图床名称 ，由于我上传的第三方图床名称会有改变，所以有此数据来做对应，您实际应用中应该不需要
				//onlineEmoji: emoj_data.online_emoji,
				
				//红包相关参数
				windowsState: '',
				redenvelopeData: {
					rid: null,	//红包ID
					from: null,
					face: null,
					blessing: null,
					money: null
				},
				list_id: 0,
				my_data: {},
			};
		},
		onLoad(option) {
			this.list_id = option.list_id;
			
			//语音自然播放结束
			this.AUDIO.onEnded((res)=>{
				this.playMsgid = null;
			});
			
			// #ifndef H5
			//录音开始事件
			this.RECORDER.onStart((e)=>{
				this.recordBegin(e);
			})
			//录音结束事件
			this.RECORDER.onStop((e)=>{
				this.recordEnd(e);
			})
			// #endif
		},
		onUnload(){
			/** 这里只保留最新的15条会话记录，以保证初次加载性能 */
			let chat_data = this.msgList;
			chat_data.list = chat_data.list.slice(-15);
			_data.localData(this.list_id,chat_data);
			/** 去除当前会话的list_id */
			_data.localData('message_list_id','');
			/** 暂停语音播放 */
			this.AUDIO.pause();
			/** 移除监听事件 */
			uni.$off('data_chat_data_unshift');
			uni.$off('data_chat_data_push');
			uni.$off('data_chat_data');
			uni.$off('data_user_info');
			uni.$off('data_chat_data_delete');
		},
		onShow(){
			_hook.routeSonHook();
			
			this.AUDIO.obeyMuteSwitch = false;
			
			/** 先移除监听事件（避免重复触发消息） */
			uni.$off('data_chat_data_unshift');
			uni.$off('data_chat_data_push');
			uni.$off('data_chat_data');
			uni.$off('data_user_info');
			uni.$off('data_chat_data_delete');
			
			let _this = this,		
			chat_data = _data.localData(_this.list_id);
			
			_this.my_data = _data.data('user_info');
			
			if(chat_data && chat_data.list.length){
				_this.scrollAnimation = false;
				uni.setNavigationBarTitle({
					title: chat_data.show_name,
				});
				_this.addMsgImgList(chat_data.list);
				chat_data.list = _this.msgDataHandle(chat_data.list);
				_this.msgList = chat_data;
				/** 滚动到指定位置 */
				_this.$nextTick(function() {
					_this.scrollToView = 'msg' + chat_data.list[chat_data.list.length - 1].msg.id;
				});
				/** 如果是群聊要实时检测群有无禁言 */
				if(chat_data.type == 1){
					_this.$httpSend({
						path: '/im/action/groupState',
						data: { list_id: _this.list_id },
						success(res) {							
							_this.msgList.is_msg = res.is_msg;
							_this.msgList.is_action = res.is_action;
						}
					});
				}
			}
			else {
				_get.getChatData({
					send_data: {
						list_id: _this.list_id,
						time: 0,
						is_up: 1,
					},
					is_action_data: 1,
				});
			}
			
			/** 添加当前的会话list_id */
			_data.localData('message_list_id',this.list_id);
			
			/** 监听会话数据变化 */
			uni.$on('data_chat_data_unshift',function(data){
				data = _this.msgDataHandle(data);
				_this.scrollAnimation = false;
				let position_id = _this.msgList.list[0].msg.id;
				_this.msgList.list.unshift(...data);
				/** 滚动到指定位置 */
				_this.$nextTick(function() {
					_this.scrollToView = 'msg' + position_id;
				});
			});
			/** 监听会话数据变化 */
			uni.$on('data_chat_data_push',function(data){
				/** 保持页面15条数据，提升性能 */
				_this.noMore = 0;
				data = _this.msgDataHandle(data);
				_this.scrollAnimation = true;
				_this.msgList.list = data;
				/** 滚动到指定位置 */
				_this.$nextTick(function() {
					_this.scrollToView = 'msg' + data[data.length - 1].msg.id;
				});
			});
			/** 监听撤回消息 */
			uni.$on('data_chat_data_delete',function(list){
				_this.msgList.list = list;
			});
			/** 监听会话数据变化 */
			uni.$on('data_chat_data',function(data){
				uni.setNavigationBarTitle({
					title: data.show_name,
				});
				data.list = _this.msgDataHandle(data.list);
				_this.scrollAnimation = false;
				_this.msgList = data;
				/** 滚动到指定位置 */
				_this.$nextTick(function() {
					_this.scrollToView = 'msg' + data.list[data.list.length - 1].msg.id;
				});
			});
			/** 监听新的个人数据 */
			uni.$on('data_user_info',function(data){
				_this.my_data = data;
			});
			
			return;
			//模板借由本地缓存实现发红包效果，实际应用中请不要使用此方法。
			uni.getStorage({
				key: 'redEnvelopeData',
				success:  (res)=>{
					console.log(res.data);
					let nowDate = new Date();
					let lastid = this.msgList.list[this.msgList.list.length-1].msg.id;
					lastid++;
					let row = {type:"user",msg:{id:lastid,type:"redEnvelope",time:nowDate.getHours()+":"+nowDate.getMinutes(),user_info:{uid:0,username:"大黑哥",face:"/static/img/face.jpg"},content:{blessing:res.data.blessing,rid:Math.floor(Math.random()*1000+1),isReceived:false}}};
					this.screenMsg(row);
					uni.removeStorage({key: 'redEnvelopeData'});
				}
			});
		},
		computed: {
			myPhoto(){
				return _data.data('cache').local_photo;
			},
			imgRan(){
				return Math.random();
			},
			staticPath(){
				return _data.staticChat() + this.list_id + '/';
			},
			staticPhoto(){
				return _data.staticPhoto();
			},
		},
		methods: {
			msgDataHandle(data,type){
				for(let i = 0,j = data.length,msg_img;i < j;i++){
					if(data[i].type == 0 && data[i].msg.type == 2){
						msg_img = this.staticPath + data[i].msg.content.url.replace('_thumb.','.');
						if(type){
							this.msgImgList.unshift(msg_img);
						}else{
							this.msgImgList.push(msg_img);
						}
						data[i].msg.content = this.setPicSize(data[i].msg.content);
					}
					else if(data[i].type == 0 && data[i].msg.type == 0){
						data[i].msg.content.text = this.replaceEmoji(data[i].msg.content.text);
					}
				}
				return data;
			},
			addMsgImgList(data){
				for(let i = 0,j = data.length,msg_img;i < j;i++){
					if(data[i].type == 0 && data[i].msg.type == 2){
						this.msgImgList.push(this.staticPath + data[i].msg.content.url.replace('_thumb.','.'));
					}
				}
			},
			timestampFormat(time){
				return _action.timestampFormat(time);
			},
			msgAction(id,user_id){
				let _this = this;
				switch (_this.msgList.type){
					case 0:
						if(user_id != _this.my_data.id){
							return;
						}
						break;
					case 1:
						if(_this.msgList.is_action == 0 && user_id != _this.my_data.id){
							return;
						}
						break;
					default:
						return;
						break;
				}
				uni.showActionSheet({
					itemList: [ '撤回消息' ],
					success: function (res) {
						switch (res.tapIndex){
							case 0:
								_this.$httpSend({
									path: '/im/message/withdraw',
									data: { list_id: _this.list_id,type: _this.msgList.type,msg_id: id, },
									success_action: true,
									success(res) {
										uni.showToast({
											title: res.msg,
											icon: 'none',
											duration: 1500
										});
									}
								});
								break;
							case 1:
								break;
							default:
								break;
						}
					},
					fail: function (res) {
						//console.log(res.errMsg);
					}
				});
			},
			goDetails(user_id){
				/** 如果是群聊，没有权限的话，查看不了其他会员信息 */
				if(this.msgList.type == 1 && this.msgList.is_action == 0){
					uni.showToast({
						title: '没有权限查看',
						icon: 'none',
						duration: 1000
					});
					return;
				}
				uni.navigateTo({
					url: ('../details/index?user_id=' + user_id),
				})
			},
			// 接受消息(筛选处理)
			screenMsg(msg){
				//从长连接处转发给这个方法，进行筛选处理
				if(msg.type==1){
					// 系统消息
					switch (msg.msg.type){
						case 0:
							this.addSystemTextMsg(msg);
							break;
						case 5:
							this.addSystemRedEnvelopeMsg(msg);
							break;
					}
				}else if(msg.type==0){
					// 用户消息
					switch (msg.msg.type){
						case 0:
							this.addTextMsg(msg);
							break;
						case 1:
							this.addVoiceMsg(msg);
							break;
						case 2:
							this.addImgMsg(msg);
							break;
						case 5:
							this.addRedEnvelopeMsg(msg);
							break;
					}
					//console.log('用户消息');
					//非自己的消息震动
					if(msg.msg.user_info.uid != this.my_data.id){
						//console.log('振动');
						uni.vibrateLong();
					}
				}
				this.$nextTick(function() {
					// 滚动到底
					this.scrollToView = 'msg' + msg.msg.id
				});
			},
			
			//触发滑动到顶部(加载历史信息记录)
			loadHistory(e){
				if(this.isHistoryLoading || this.noMore){
					return;
				}				
				let _this = this;
				this.isHistoryLoading = true; //参数作为进入请求标识，防止重复请求
				
				_get.getChatData({
					send_data: {
						list_id: _this.list_id,
						time: _this.msgList.list[0].msg.time,
						is_up: 0,
					},
					callback(data){
						if(data.list.length < 15){
							_this.noMore = 1;
						}
						_this.isHistoryLoading = false;
					},
					is_action_data: 0,
				});
			},
			
			//处理图片尺寸，如果不处理宽高，新进入页面加载图片时候会闪
			setPicSize(content){
				// 让图片最长边等于设置的最大长度，短边等比例缩小，图片控件真实改变，区别于aspectFit方式。
				let maxW = uni.upx2px(350);//350是定义消息图片最大宽度
				let maxH = uni.upx2px(350);//350是定义消息图片最大高度
				if(content.w > maxW || content.h > maxH){
					let scale = content.w/content.h;
					content.w = scale > 1 ? maxW:maxH * scale;
					content.h = scale > 1 ? maxW / scale: maxH;
					let url_array = content.url.split('.');
					if(content.url.indexOf('_thumb.') == -1){
						content.url = url_array[0] + '_thumb.' + url_array[1];
					}
				}
				return content;
			},
			
			//更多功能(点击+弹出) 
			showMore(){
				this.isVoice = false;
				this.hideEmoji = true;
				if(this.hideMore){
					this.hideMore = false;
					this.openDrawer();
				}else{
					this.hideDrawer();
				}
			},
			// 打开抽屉
			openDrawer(){
				this.popupLayerClass = 'showLayer';
			},
			// 隐藏抽屉
			hideDrawer(){
				this.popupLayerClass = '';
				setTimeout(()=>{
					this.hideMore = true;
					this.hideEmoji = true;
				},150);
			},
			// 选择图片发送
			chooseImage(){
				this.getImage('album');
			},
			//拍照发送
			camera(){
				this.getImage('camera');
			},
			//拍摄视频发送
			video(){
				uni.chooseVideo({
					success: (res) => {
						let min = parseInt(res.size / 60),
						sec = res.size % 60;
						min = min < 10 ? '0' + min:min;
						sec = sec < 10 ? '0' + sec:sec;						
						let msg = { url:res.tempFilePath,length: (min + ':' + sec) };
						this.sendMsg(msg,3);
					}
				})
			},
			//发红包
			handRedEnvelopes(){
				uni.navigateTo({
					url:'message/hand'
				});
				this.hideDrawer();
			},
			//选照片 or 拍照
			getImage(type){
				this.hideDrawer();
				uni.chooseImage({
					sourceType:[type],
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					success: (res)=>{
						for(let i=0;i<res.tempFilePaths.length;i++){
							uni.getImageInfo({
								src: res.tempFilePaths[i],
								success: (image) => {
									//console.log(image.width);
									//console.log(image.height);
									let msg = {url:res.tempFilePaths[i],w:image.width,h:image.height};
									this.sendMsg(msg,2);
								}
							});
						}
					}
				});
			},
			// 选择表情
			chooseEmoji(){
				this.hideMore = true;
				if(this.hideEmoji){
					this.hideEmoji = false;
					this.openDrawer();
				}else{
					this.hideDrawer();
				}
			},
			//添加表情
			addEmoji(em){				
				this.textMsg += em.alt;
			},
			
			//获取焦点，如果不是选表情ing,则关闭抽屉
			textareaFocus(){
				let _this = this;
				_this.hideDrawer();
				return;
				if(_this.popupLayerClass == 'showLayer' && this.hideMore == false){
					_this.hideDrawer();
				}
			},
			
			// 发送文字消息
			sendText(){
				this.hideDrawer();//隐藏抽屉
				if(!this.textMsg){
					return;
				}				
				this.sendMsg('',0);
				//清空输入框
				this.textMsg = '';
			},
			
			//替换表情符号为图片
			replaceEmoji(str){
				let replacedStr = str.replace(/\[([^(\]|\[)]*)\]/g,(item, index) => {
					// console.log("item: " + item);
					for(let i=0;i<this.emojiList.length;i++){
						let row = this.emojiList[i];
						for(let j=0;j<row.length;j++){
							let EM = row[j];
							if(EM.alt==item){
								//在线表情路径，图文混排必须使用网络路径，请上传一份表情到你的服务器后再替换此路径 
								//比如你上传服务器后，你的100.gif路径为https://www.xxx.com/emoji/100.gif 则替换onlinePath填写为https://www.xxx.com/emoji/
								let imgstr = '<img style="width:22px;height:22px;" src="' + _data.data('static_url') + '/static/emoji/' + EM.url + '">';
								// console.log("imgstr: " + imgstr);
								return imgstr;
							}
						}
					}
				});				
				// display: flex;
				return '<div style="align-items: center;word-break:break-all;">' + replacedStr + '</div>';
			},
			
			// 发送消息
			sendMsg(content,type){
				
				// #ifdef H5
				uni.showLoading({
				    title: '发送中...'
				});
				// #endif
				
				// #ifdef APP-PLUS
				let wgtWaiting = plus.nativeUI.showWaiting("发送中...");
				// #endif
				
				let _this = this,
				sendMsg = content;
				
				((callback) => {
					
					switch (type){
						/** 文字/表情消息 */
						case 0:
							let msg = this.textMsg.replace(/</g,'&lt;');
							sendMsg = { text: msg };
							callback();
							break;
						/** 语音/图片/视频/文件 */
						case 1:
						case 2:
						case 3:
						case 4:
							_this.$httpSendFile({
								type: 0,
								local_url: sendMsg.url,
								data: {
									'list_id': _this.list_id,
								},
								success(data){
									sendMsg.url = data.save_name;
									callback();
								},
								onProgressUpdate(res){
									
									// #ifdef H5
									uni.showLoading({
										title: ('发送中...' + res.progress + '%'),
									});
									// #endif
									
									// #ifdef APP-PLUS
									wgtWaiting.setTitle('发送中...' + res.progress + '%');
									// #endif
									
								},
							});
							break;
						/** 红包消息 */
						case 5:
							break;
						
						default:
							break;
					}
				})(() => {
					_this.$httpSend({
						path: '/im/message/textMsg',
						data: {
							list_id: _this.list_id,
							content_type: type,
							content: JSON.stringify(sendMsg),
						},
						success_action: true,
						success(res) {
							
							// #ifdef H5
							uni.hideLoading();
							// #endif
							
							// #ifdef APP-PLUS
							wgtWaiting.close();
							// #endif
							
							switch (res.err){
								case 0:
									if(type == 1){
										_action.playVoice('/static/voice/voice.mp3');
									}
									break;
								case 1:
									uni.showModal({
										title: '好友提示',
										confirmText: '发送好友申请',
										content: res.msg,
										success: function (res) {
											if (res.confirm) {
												uni.navigateTo({
													url: ('../friend/apply?user_id=' + _data.localData(_this.list_id).obj_id + '&is_type=0'),
												});
											}
										}
									});
									break;
								case 2:
									uni.showModal({
										content: res.msg,
									});
									let data = _data.localData(data.list_id);
									data.is_msg = 1;
									_this.msgList.is_msg = 1;
									_data.localData(data.list_id,data);
									break;
								default:
									break;
							}
						}
					});
				});		
			},
			
			// 添加文字消息到列表
			addTextMsg(msg){
				this.msgList.list.push(msg);
			},
			// 添加语音消息到列表
			addVoiceMsg(msg){
				this.msgList.list.push(msg);
			},
			// 添加图片消息到列表
			addImgMsg(msg){
				this.msgImgList.push(this.staticPath + msg.msg.content.url);
				msg.msg.content = this.setPicSize(msg.msg.content);
				this.msgList.list.push(msg);
			},
			addRedEnvelopeMsg(msg){
				this.msgList.push(msg);
			},
			// 添加系统文字消息到列表
			addSystemTextMsg(msg){
				this.msgList.list.push(msg);
			},
			// 添加系统红包消息到列表
			addSystemRedEnvelopeMsg(msg){
				this.msgList.list.push(msg);
			},
			// 打开红包
			openRedEnvelope(msg,index){
				let rid = msg.content.rid;
				uni.showLoading({
					title:'加载中...'
				});
				
				console.log("index: " + index);
			
				//模拟请求服务器效果
				setTimeout(()=>{
					//加载数据
					if(rid==0){
						this.redenvelopeData={
							rid:0,	//红包ID
							from:"大黑哥",
							face:"/static/img/im/face/face.jpg",
							blessing:"恭喜发财，大吉大利",
							money:"已领完"
						}
					}else{
						this.redenvelopeData={
							rid:1,	//红包ID
							from:"售后客服008",
							face:"/static/img/im/face/face_2.jpg",
							blessing:"恭喜发财",
							money:"0.01"
						}
						if(!msg.content.isReceived){
							// {type:"system",msg:{id:8,type:"redEnvelope",content:{text:"你领取了售后客服008的红包"}}},
							this.sendSystemMsg({text:"你领取了"+(msg.user_info.uid==this.my_data.id?"自己":msg.user_info.username)+"的红包"},'redEnvelope');
							this.msgList.list[index].msg.content.isReceived = true;
						}
					}
					uni.hideLoading();
					this.windowsState = 'show';
					
				},200)
				
			},
			// 关闭红包弹窗
			closeRedEnvelope(){
				this.windowsState = 'hide';
				setTimeout(()=>{
					this.windowsState = '';
				},200)
			},
			sendSystemMsg(content,type){
				let lastid = this.msgList.list[this.msgList.list.length-1].msg.id;
				lastid++;
				let row = {type:"system",msg:{id:lastid,type:type,content:content}};
				this.screenMsg(row);
			},
			//领取详情
			toDetails(rid){
				uni.navigateTo({
					url:'message/details?rid='+rid
				})
			},
			// 预览图片
			showPic(msg){
				uni.previewImage({
					indicator:"none",
					current: this.staticPath + msg.content.url.replace('_thumb',''),
					urls: this.msgImgList
				});
			},
			// 播放语音
			playVoice(msg){
				let _this = this;
				_this.AUDIO.stop();
				if(_this.playMsgid == msg.id){
					_this.playMsgid = null;
					return;
				}
				/** 这里下载语音文件并保存在本地 */
				let voice_data = _data.localData('voice_data');
				if(!voice_data){
					voice_data = {};
				}
				if(!(_this.list_id in voice_data)){
					voice_data[_this.list_id] = {};
				}
				_this.playMsgid = msg.id;
				
				/** 这里把语音文件保存到本地播放 */
				if(!(msg.id in voice_data[_this.list_id])){
					
					// #ifdef H5
					uni.showLoading({
						title: '加载中...',
					});
					// #endif
					
					// #ifdef APP-PLUS
					let wgtWaiting = plus.nativeUI.showWaiting("加载中...");
					// #endif
					
					let downloadTask = uni.downloadFile({
						url: (_this.staticPath + msg.content.url),
						success: (res) => {
							
							// #ifdef H5
							uni.hideLoading();
							// #endif
							
							// #ifdef APP-PLUS
							wgtWaiting.close();
							// #endif
							
							if (res.statusCode === 200) {
								
								// #ifdef H5
									_this.AUDIO.src = res.tempFilePath;
									_this.$nextTick(function() {
										_this.AUDIO.play();
									});
									voice_data[_this.list_id][msg.id] = res.tempFilePath;
									_data.localData('voice_data',voice_data);
								// #endif
								
								/** 这里如果是APP端就把语音文件缓存到本地 */
								// #ifdef APP-PLUS
									uni.saveFile({
										tempFilePath: res.tempFilePath,
										success(res){
											_this.AUDIO.src = res.savedFilePath;
											_this.$nextTick(function() {
												_this.AUDIO.play();
											});
											voice_data[_this.list_id][msg.id] = res.savedFilePath;
											_data.localData('voice_data',voice_data);
										},
									});
								// #endif
							}
							else{
								uni.showToast({
									title: 'loadding voice file error',
								})
							}
						}
					});
					downloadTask.onProgressUpdate((res) => {
						
						// #ifdef APP-PLUS
						wgtWaiting.setTitle('加载中...' + res.progress + '%');
						// #endif
						
						// #ifdef H5
						uni.showLoading({
							title: '加载中...' + res.progress + '%',
						});
						// #endif
						
					});
				}
				else {
					_this.AUDIO.src = voice_data[_this.list_id][msg.id];				
					_this.$nextTick(function() {
						_this.AUDIO.play();
					});
				}
			},
			// 录音开始
			voiceBegin(e){
				if(e.touches.length>1){
					return ;
				}
				this.initPoint.Y = e.touches[0].clientY;
				this.initPoint.identifier = e.touches[0].identifier;
				this.RECORDER.start({format:"mp3"});//录音开始,
			},
			//录音开始UI效果
			recordBegin(e){
				
				/** 暂停语音播放 */
				this.AUDIO.pause();
				
				this.recording = true;
				this.voiceTis='松开 结束';
				this.recordLength = 0;
				this.recordTimer = setInterval(()=>{
					this.recordLength++;
				},1000)
			},
			// 录音被打断
			voiceCancel(){
				this.recording = false;
				this.voiceTis='按住 说话';
				this.recordTis = '手指上滑 取消发送'
				this.willStop = true;//不发送录音
				this.RECORDER.stop();//录音结束
			},
			// 录音中(判断是否触发上滑取消发送)
			voiceIng(e){
				if(!this.recording){
					return;
				}
				let touche = e.touches[0];
				//上滑一个导航栏的高度触发上滑取消发送
				if(this.initPoint.Y - touche.clientY>=uni.upx2px(100)){
					this.willStop = true;
					this.recordTis = '松开手指 取消发送'
				}else{
					this.willStop = false;
					this.recordTis = '手指上滑 取消发送'
				}
			},
			// 结束录音
			voiceEnd(e){
				if(!this.recording){
					return;
				}
				this.recording = false;
				this.voiceTis='按住 说话';
				this.recordTis = '手指上滑 取消发送'
				this.RECORDER.stop();//录音结束
			},
			//录音结束(回调文件)
			recordEnd(e){
				clearInterval(this.recordTimer);
				if(!this.willStop){
					//console.log("e: " + JSON.stringify(e));
					let msg = {
						length:0,
						url:e.tempFilePath
					}
					let min = parseInt(this.recordLength/60);
					let sec = this.recordLength%60;
					min = min<10?'0'+min:min;
					sec = sec<10?'0'+sec:sec;
					if(min > 0 || sec > 0){
						msg.length = min+':'+sec;
						this.sendMsg(msg,1);
					}
				}
				else{
					console.log('取消发送录音');
				}
				this.willStop = false;
			},
			// 切换语音/文字输入
			switchVoice(){
				this.hideDrawer();
				this.isVoice = this.isVoice ? false : true;
			},
			discard(){
				return;
			}
		},
		watch: {
			
		},
		onNavigationBarButtonTap(e) {
			uni.navigateTo({
				url: './message/more?list_id=' + this.list_id + '&type=' + this.msgList.type,
			});
		},
	}
</script>

<style lang="scss">
	
	@import "@/static/css/chat/style.scss";
	.tool_text {
		margin-top: 120upx;
		font-size: 20upx;
		margin-left: 0upx;
		position: absolute;
		top: 0;
		color: #353535;
	}
	
</style>