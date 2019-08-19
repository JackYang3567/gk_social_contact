<template>
	<view class="content">
		<view class="login-bg">
			<view class="login-card">
				<view class="login-head">输入您的账户</view>
				<view class="login-input login-margin-b">
					<input type="text" placeholder="社群号/手机号或者邮箱" v-model="form.username" />
				</view>
				
				<view class="uni-form-item uni-column">
					<view class="with-fun login-input">
						<input class="uni-input" placeholder="请输入密码(6-16位)" :password="showPassword" v-model="form.password"/>
						<view class="uni-icon uni-icon-eye" :class="[showPassword ? '' : 'uni-active']" @click="changePassword"></view>
					</view>
				</view>
				<view class="login-function">
					<!-- <view class="login-forget" @click="go_forget">忘记密码</view> -->
					<view class="login-register" @click="go_register">快速注册></view>
				</view>
			</view>
		</view>
		<view class="login-btn">
			<!-- <button class="landing" type="primary">登 陆</button> -->
			<view :class="['landing',checkIn ? 'landing_true' : 'landing_false']" @tap="subLongin">登 陆</view>
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
		padding: 40upx 20upx 30upx 20upx;
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
