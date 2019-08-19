import _data from './_data';
import _get from './_get';
import _action from './_action';

export default {
	/** 验证token */
	checkToken(res) {
		if(res.err){
			_action.checkFail();
		} else {
			/** 设置 socket 连接状态 */
			_data.data('socket_state',1);
			/** 获取基础数据 */
			_get.base();
		}
	},
	/** 下线 */
	offline(res){
		_action.checkFail();
		uni.showModal({
			content: '你的账号在另一客户端登陆，如果不是你本人操作，请修改你的密码',
		});
	},
	/** 获得会话列表 */
	getChatList(){
		_get.getChatList();
	},
	/** 获得好友列表 */
	getFriendList(){
		_get.getFriendList({ up: 1});
	},
	/** 新好友提醒 */
	newFriend(data){
		_action.playVoice('/static/voice/friend.mp3');
		let num = _data.data('new_friend_tips_num') + (data.num * 1);
		_data.data('new_friend_tips_num',num);
		_action.setStatusTips();
	},
	/** 点赞提醒 */
	circleLike(data){
		_action.playVoice('/static/voice/circle.mp3');
		let circle_data = _data.localData('circle_data');
		for(let i = 0,j = circle_data.length;i< j; i++){
			if(circle_data[i].post_id == data.id){
				circle_data[i].like = data.likes;
				_data.localData('circle_data',circle_data);
				uni.$emit('data_circle_data',circle_data);
				break;
			}
		}
	},
	/** 接收新消息 */
	chatData(data){
		let chat_data = _data.localData(data.list_id),
		msg_reader_num = 0;
		
		/** 如果不是自己的消息,在这条会话界面,震动提示，没有在这条会话界面，震动加声音提示 */
		if(_data.data('user_info').id != data.data.msg.user_info.uid){
			
			// #ifdef APP-PLUS
			uni.vibrateLong();
			// #endif
			
			if(_data.localData('message_list_id') == data.list_id){
				_action.updataNoReader(data.list_id);
			}
			else{
				_action.playVoice('/static/voice/chat.mp3');
				msg_reader_num = 1;
			}
		}
		
		/** 更新对话列表数据 */
		for(let i = 0,local_chat_list = _data.localData('chat_list'),j = local_chat_list.length;i < j;i ++){
			if(local_chat_list[i].list_id == data.list_id){
				switch(data.data.msg.type * 1){
					case 0:
						local_chat_list[i].last_msg = data.data.msg.content.text;
						break;
					case 1:
						/** 语音 */
						local_chat_list[i].last_msg = '[语音]';
						break;
					case 2:
						/** 图片 */
						local_chat_list[i].last_msg = '[图片]';
						break;
					case 3:
						/** 视频 */
						local_chat_list[i].last_msg = '[视频]';
						break;
					case 4:
						/** 文件 */
						local_chat_list[i].last_msg = '[文件]';
						break;
					case 5:
						/** 红包 */
						local_chat_list[i].last_msg = '[红包]';
						break;
					default:
						/** 未知消息类型 */
						local_chat_list[i].last_msg = '[未知]';
						break;
				}
				local_chat_list[i].no_reader_num += msg_reader_num;
				local_chat_list[i].time = data.data.msg.time;
				
				let action_list_data = local_chat_list[i];
				
				local_chat_list.splice(i,1);
				local_chat_list.unshift(action_list_data);
				
				_data.localData('chat_list',local_chat_list);
				uni.$emit('data_chat_list',local_chat_list);
								
				break;
			}
		}
		
		/** 在有这条对话的缓存数据情况下 */
		if(chat_data){
			chat_data.list.push(data.data);
			chat_data.list = chat_data.list.slice(-15);
			_data.localData(data.list_id,chat_data);
			/** 如果在与对方的对话界面,发送数据到页面显示 */
			if(_data.localData('message_list_id') == data.list_id) {
				/** 保持页面15条数据，提升性能 */
				uni.$emit('data_chat_data_push',chat_data.list);
			}
		}
		_action.setStatusTips();
	},
	/** 接收好友朋友圈动态提示 */
	circleTips(data){
		_action.playVoice('/static/voice/circle.mp3');
		_data.data('no_reader_circle',1);
		_action.setStatusTips();
	},
	/** 接收朋友圈好友回复/赞通知 */
	cricleChatTips(data){
		_action.playVoice('/static/voice/circle.mp3');
		let num = _data.data('no_reader_circle_chat_num');
		num ++;
		_data.data('no_reader_circle_chat_num',num);
		_action.setStatusTips();
	},
	/** 撤回消息 */
	deleteChat(data){
		let chat_data = _data.localData(data.list_id);
		for(let i = 0,j = chat_data.list.length;i < j;i++ ){
			if(chat_data.list[i].msg.id == data.data.msg.id){
				chat_data.list[i] = data.data;
				_data.localData(data.list_id,chat_data);
				uni.$emit('data_chat_data_delete',chat_data.list);
				break;
			}
		}
	},
	/** 加群申请 */
	chatGroupApply(data){
		let local_data = _data.localData('group_apply_list');
		if(!local_data){
			local_data = [];
		}
		local_data.push(data);
		_data.localData('group_apply_list',local_data);
		uni.$emit('data_group_apply_data',local_data);
		let num = _data.data('new_group_tips_num');
		num ++;
		_data.data('new_group_tips_num',num);
		_action.playVoice('/static/voice/friend.mp3');
		_action.setStatusTips();
	},
	
	/** 通知群管理已处理 */
	groupChatApplyAllow(id){
		let local_data = _data.localData('group_apply_list');
		for(let value of local_data){
			if(value.id == id){
				value.status = 1;
				value.text = '已接受';
				let num = _data.data('new_group_tips_num');
				num --;
				if(num < 0){
					num = 0;
				}
				_data.data('new_group_tips_num',num);
			}
			break;
		}
		_data.localData('group_apply_list',local_data);
		uni.$emit('data_group_apply_data',local_data);
		_action.setStatusTips();
	},
	
	/** 解散群 */
	removeGroup(data){
		/** 删除对话列表缓存数据 */
		for(let i = 0,local_chat_list = _data.localData('chat_list'),j = local_chat_list.length;i < j;i ++){
			if(local_chat_list[i].list_id == data.list_id){
				local_chat_list.splice(i,1);
				_data.localData('chat_list',local_chat_list);
				uni.$emit('data_chat_list',local_chat_list);
				break;
			}
		}
		/** 删除对话缓存数据 */
		_data.localData(data.list_id,null);
		uni.showModal({
			title: data.group_name + ' 群聊已经被群主解散了!',
		});
	}
}