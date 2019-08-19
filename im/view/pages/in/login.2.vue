<template>
	<view class="content">
		<view class="login-bg">
			<view class="login-card">
				<view class="uni-media-image">
					<image class="uni-media-loading" src="/static/theme/default/app.png" />
				</view>
				<view class="login-head">&nbsp;&nbsp;</view>
			<label class="uni-list-cell uni-list-cell-pd" >
				<label class="label-2-text" >
				   <text>账号</text>
				</label>
				<view >
					<input type="text" class="uni-input" placeholder="相遇号/手机号/邮箱" v-model="form.username" />
				</view>
				<view  class="uni-icon uni-active"><text>&nbsp;&nbsp;</text></view>
			</label>
			<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-2-text" >
					   <text>密码</text>
					</label>				   
					<view>
					   <input class="uni-input" placeholder=" 请输入密码(6-16位)" :password="showPassword" v-model="form.password"/>
					</view>
					<view class="uni-icon uni-icon-eye" :class="[showPassword ? '' : 'uni-active']" @click="changePassword"></view>
					
			</label>
		</view>
		<view class="login-btn">
			<button class="landing" type="primary" @tap="subLongin">登 录</button> 
			<!--  <view :class="['landing',checkIn ? 'landing_true' : 'landing_false']" @tap="subLongin">登 陆</view> -->
		</view>
		
		<view>
			<view class="login-function">
				<view class="login-forget" @click="go_forget"><text>&nbsp;&nbsp;</text>忘记密码</view>
				<view class="login-register" @click="go_register">快速注册<text>&nbsp;&nbsp;</text></view>
			</view>
		</view>
	</view>
</template>

<script>
	
	export default {
		data() {
			return {
				showPassword: true,
				form: {
					username: '',
					password: '',
				}
			}
		},
		onLoad() {
			uni.getSystemInfo({
				success: function (res) {
					console.log("model:",res.model);
					console.log("pixelRatio:",res.pixelRatio);
					console.log("windowWidth:",res.windowWidth);
					console.log("windowHeight:",res.windowHeight);
					console.log("language:",res.language);
					console.log("version:",res.version);
					console.log("platform:",res.platform);
				}
			});
		},
		computed: {
			checkIn(){
				return this.form.password != '' && this.form.username != '' && this.form.password.length > 5;
			}
		},
		methods: {
			changePassword() {
				this.showPassword = !this.showPassword;
			},
			subLongin(){
				let _this = this;
				if(!_this.checkIn){
					return;
				}
				_this.$httpSend({
					path: '/im/in/login',
					data: _this.form,
					success: (data) => {
						uni.setStorage({
							key: 'token',
							data: data.token,
							fail: () => {
								uni.showModal({
									content: '本地存储数据不可用!',
								});
							},
							success(){
								uni.reLaunch({
									url: '../chat/index',
								});
							},
						});
					}
				});
			},
			go_forget(){
				uni.navigateTo({
				    url: '../../pages/in/forget'
				})
			},
			go_register(){
				uni.navigateTo({
					url: '../../pages/in/reg'
				})
			}
			
		}
	}
</script>

<style>
	.uni-media-image{
		padding: 10upx 10upx;
		margin-top: 10upx;
		text-align: center;
	}
	.uni-media-loading {
		width:150upx;
		height:150upx;
	}
	.landing {
		height: 84upx;
		line-height: 84upx;
		color: #FFFFFF;
		font-size: 32upx;
		bordor: none;
		border-radius: 10upx;
	}
	.landing_true {
		background-color: #b2e281;
	}
	.landing_false {
		background-color: #d8d8d8;
	}
	.uni-button[type=primary] {
		background-color: #b2e281;
	}
	.login-btn{
		padding: 10upx 20upx;
		margin-top: 100upx;
		text-align: center;
	}
	.login-function{
		overflow: auto;
		margin-top: 0upx;
		padding: 40upx 50upx;
		
	}
	.login-forget{
		float: left;
		font-size: 26upx;
		color: #999;
	}
	.login-register{
		color: #666;
		float: right;
		font-size: 26upx;

	}
	.login-input input{
		background: #F2F5F6;
		font-size: 28upx;
		padding: 10upx 25upx;
		height: 62upx;
		line-height: 62upx;
		border-radius: 8upx;
	}
	.login-margin-b{
		margin-bottom: 25upx;
	}
	.login-input{
		padding: 20upx 20upx;
	}
	.login-head{
		font-size: 34upx;
		text-align: center;
		padding: 25upx 10upx 55upx 10upx;
	}
	.login-card{
		background: #fff;
		border-radius: 12upx;
		padding: 10upx 25upx;
		/* box-shadow: 0 6upx 18upx rgba(0,0,0,0.12); */
		position: relative;
		margin-top: 100upx;
	}
	.login-bg {
		/* height: 260upx;
		padding: 25upx;
		background: linear-gradient(#FF978D, #FFBB69); */
	}
	
	page {
		background-color: #FFFFFF;
	}
	
	.uni-form-item .with-fun .uni-icon {
		text-align: center;
	}
</style>
