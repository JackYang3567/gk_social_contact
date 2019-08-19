<template>
	<view class="content">
		<view class="login-bg">
			<view class="login-card">
				<view class="login-head">输入您的注册信息</view>
				
				<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-2-text" >
					   <text>帐号</text>
					</label>
					<view>
						<input type="text" class="uni-input" placeholder="手机号/相遇号(6-16位字母/数字)" v-model="form.username" />
					</view>	
					<view class="uni-icon uni-icon-clear" v-if="form.username" @click="delInputUsernameText"></view>
					<view class="uni-icon" v-else ></view>
					<view class="uni-icon placeholdertext"></view>
				</label>
				<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-2-text" >
					   <text>密码</text>
					</label>
					<view>
						 <input class="uni-input" placeholder="请输入密码(6-16位)" :password="showPassword" v-model="form.password"/>
					</view>
					<view class="uni-icon uni-icon-clear" v-if="form.password" @click="delInputPasswordText"></view>
					<view class="uni-icon" v-else ></view>
					<view class="uni-icon uni-icon-eye" :class="[showPassword ? '' : 'uni-active']" @click="changePassword"></view>
								
				
				</label>
			</view>
		</view>
		<view class="login-btn">
			<button :class="['landing',checkIn ? 'landing_true' : 'landing_false']" 
			:disabled="checkIn ? false : true" type="primary" @tap="subReg">注 册</button> 
		<!--	<view :class="['landing',checkIn ? 'landing_true' : 'landing_false']" @tap="subReg">注 册</view> -->
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
				return this.form.password != '' && this.form.username != '' && this.form.password.length > 5 &&  this.form.username.length > 5;
			}
		},
		methods: {
			changePassword() {
				this.showPassword = !this.showPassword;
			},
			delInputUsernameText(){
				this.form.username = ''
			},
			delInputPasswordText(){
				this.form.password = ''
			},
			subReg(){
				let _this = this;
				if(!_this.checkIn){
					return;
				}
				if(!(/^\w{1,20}$/.test(this.form.username)))
				{
					uni.showModal({
						content: '相遇号只能包括下划线、数字、字母,并且不能超过20个',
					});
					return;
				}
				
				if(!(/^\w{1,20}$/.test(this.form.password))){
					uni.showModal({
						content: '密码只能包括下划线、数字、字母,长度6-20位',
					});
					return;
				}
 
				_this.$httpSend({
					path: '/im/in/reg',
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
	.placeholdertext{
		/* #ifdef H5 */
		width: 48upx;
		/* #endif */
		/*#ifdef APP-PLUS */
		
		width: 40upx;
		/* #endif */
		height: 24upx;
	}
	.landing_true {
		
	}
	.landing_false {
		background-color: #d8d8d8;
	}
	.uni-button[type=primary] {
		
	}
	.login-btn{
		padding: 10upx 20upx;
		margin-top: 100upx;
		text-align: center;
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
		margin-top: 35upx;
		margin-bottom: 24upx;
		padding: 25upx 80upx 130upx 10upx;
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
	.uni-input {
		height: 50upx;
		width: 460upx;
		padding: 15upx 0 15upx 25upx;
		line-height:50upx;
		font-size:28upx;
		background:#FFF;
		flex: 1;
	}
	.uni-icon {
		text-align: left;
	}
	page {
		background-color: #FFFFFF;
	}
</style>
