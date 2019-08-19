import _mixins from './_mixins';
import _action from './_action';
import _data from './_data';

export default {
	/** 获得会话列表数据 */
	getChatList(){
		_mixins.methods.$httpSend({
			path: '/im/get/chatList',
			success(data) {
				_data.localData('chat_list',data);
				uni.$emit('data_chat_list',data);
				_action.setStatusTips();
			}
		});
	},
   /**
	 * 获得对话数据 
	 * @param {
			发送的数据
			send_data: {
				list_id: list_id,
				time: 0,
				是否更新未读消息数
				is_up: 1,
			},
			回调函数
			calllback(data){
				
			},
			是否需要操作本地缓存数据
			is_action_data: 1,
		}
	 */
	getChatData(config){
		_mixins.methods.$httpSend({
			path: '/im/get/chatData',
			data: config.send_data,
			success(data) {
				if(config.is_action_data){
					uni.$emit('data_chat_data',data);
					_data.localData(data.list_id,data);
				}
				else{
					uni.$emit('data_chat_data_unshift',data.list);
				}
				if('callback' in config){
					config.callback(data);
				}
			}
		});
	},
	/** 获得通讯录数据 */
	getFriendList(send_data,callback){
		if(!send_data){
			send_data = {};
		}
		_mixins.methods.$httpSend({
			path: '/im/get/friendList',
			data: send_data,
			success(data) {
				if(data.data.length || 'up' in send_data){
					_data.localData('friend_list',data.data);
					uni.$emit('data_friend_list',data.data);
				}
				if(callback){
					callback(data);
				}
			}
		});
	},
	/** 获得朋友圈数据 data要发送的数据 type 0加载最新数据 1加载历史数据 */
	getCircleList(send_data,callback){
		_mixins.methods.$httpSend({
			path: '/im/get/circleData',
			data: send_data,
			success(data) {
				let circle_data = _data.localData('circle_data');
				if(!circle_data){
					circle_data = [];
				}
				if(send_data.type){
					circle_data.push(...data.data);
				} else {
					circle_data.unshift(...data.data);
				}
				
				_data.localData('circle_data',circle_data);
				uni.$emit('data_circle_data',circle_data);
				
				if(callback) {
					callback(data);
				}
			}
		});
	},
	/** 获得基础数据 */
	base(){
		_mixins.methods.$httpSend({
			path: '/im/get/base',
			success(data) {
				
				data.user_info.photo += '?_=' + Math.random();
				data.user_info.circle_img += '?_=' + Math.random();
				
				_data.data('user_info',data.user_info);
				uni.$emit('data_user_info',data.user_info);
				
				_data.data('new_friend_tips_num',data.new_friend_tips_num);
				_data.data('no_reader_chat_num',data.no_reader_chat_num);
				_data.data('no_reader_circle',data.no_reader_circle);
				_data.data('no_reader_circle_chat_num',data.no_reader_circle_chat_num);
				_data.data('new_group_tips_num',data.new_group_tips_num);
				
				_action.setStatusTips();
				_action.downloadPhoto();
			}
		});
	},
	/** 获得好友申请列表数据 */
	getFriendApplyList(callback){
		_mixins.methods.$httpSend({
			path: '/im/get/applyFriend',
			success(data) {
				_data.localData('friend_apply_list',data);
				uni.$emit('data_friend_apply_list',data);
				if(callback){
					callback(data);
				}
			}
		});
	},
	/** 获得群认证列表数据 */
	getGroupApplyList(callback){
		_mixins.methods.$httpSend({
			path: '/im/get/applyGroup',
			success(data) {
				_data.localData('group_apply_list',data);
				uni.$emit('data_group_apply_data',data);
				if(callback){
					callback(data);
				}
			}
		});
	},
}