export default {
	/**
	 * [设置获取globalData数据]
	 * @param {Object} k 设置/获取的键
	 * @param {Object} v 设置的值,没有传值就是获取这个键的值
	 * @return {String|Array|Object}
	 */
	data(k,v){
		if(v === undefined){
			return getApp().globalData[k];
		}
		else{
			getApp().globalData[k] = v;
		}
	},
	/**
	 * [设置获取保存在本地的页面数据]
	 * @param {Object} k 设置/获取的键
	 * @param {Object} v 设置的值,v为undefined获取这个键的值,v为null,移除这个键的数据
	 * @return {String|Array|Object}
	 */
	localData(k,v){
		if(v === undefined){
			return uni.getStorageSync(k);
		}
		else if(v === null){
			uni.removeStorage({
				key: k,
				fail(){
					uni.showModal({
						content: '删除本地数据失败',
					});
				}
			});
		}
		else {
			uni.setStorage({
				key: k,
				data: v,
				fail(){
					uni.showModal({
						content: '本地数据设置失败,请检测storage存储',
					});
				}
			});
		}
	},
	/** 聊天静态文件地址 */
	staticChat(){
		return getApp().globalData.static_url + '/static/chat/';
	},
	/** 朋友圈静态文件地址 */
	staticCircle(){
		return getApp().globalData.static_url + '/static/circle/';
	},
	/** 头像地址 */
	staticPhoto(){
		return getApp().globalData.static_url + '/static/photo/';
	},
	/** 获取会话界面有多少未读消息 */
	chatTipsNum(){
		let num = 0,
		chat_list = uni.getStorageSync('chat_list');
		if(chat_list){
			for(let value of chat_list){
				num += (value.no_reader_num * 1);
			}
		}
		return num;
	},
}