<template>
	<view class="content">
		<view class="login-bg">
			<view class="login-card">
				<view class="locked-head"> 
					<view  class="uni-icon uni-icon-locked locked"></view>
				</view>
				<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-2-text" >
					   <text>邮　箱</text>
					</label>
					<view>
						<input class="uni-input email" placeholder="输入您的电子邮箱" v-model="form.email" />
					</view>	
					<view class="uni-icon uni-icon-clear" v-if="form.email" @click="delInputUEmailText"></view>
					<view  class="uni-icon placeholdertext"></view>
				</label>
				<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-3-text" >
					   <text>验证码</text>
					</label>
					<view>
						<input class="uni-input captch" placeholder="请输入验证码" v-model="form.captch" />
					</view>	
					<view class="uni-icon uni-icon-clear" v-if="form.captch" @click="delInputCaptchText"></view>
					<view  class="uni-icon uni-active"><button class="captchtext">获取验证码</button></view>
				</label>
				<label class="uni-list-cell uni-list-cell-pd" >
					<label class="label-2-text" >
					   <text>密　码</text>
					</label>
					<view>
						 <input class="uni-input pass" placeholder="请输入密码(6-16位)" :password="showPassword" v-model="form.password"/>
					</view>
					<view class="uni-icon uni-icon-clear" v-if="form.password" @click="delInputPasswordText"></view>
					<view class="uni-icon uni-icon-eye" :class="[showPassword ? '' : 'uni-active']" @click="changePassword"></view>
								
				
				</label>
			</view>
		</view>
		<view class="login-btn">
			<button :class="['landing',checkIn ? 'landing_true' : 'landing_false']" 
			:disabled="checkIn ? false : true" type="primary" @tap="subReg">确 定</button> 
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
					captch: '',
					email: '',
					password: '',
				}
			}
		},
		onLoad() {

		},
		computed: {
			checkIn(){
				return this.form.captch != '' && this.form.password != '' && this.form.email != '' && this.form.password.length > 5;
			}
		},
		methods: {
			changePassword() {
				this.showPassword = !this.showPassword;
			},
			delInputUEmailText(){
				this.form.email = ''
			},
			delInputCaptchText(){
				this.form.captch = ''
			},
			delInputPasswordText(){
				this.form.password = ''
			},
			subReg(){
				let _this = this;
				if(!_this.checkIn){
					return;
				}
				if(!(/^\w{1,20}$/.test(this.form.email))){
					uni.showModal({
						content: '社群号只能包括下划线、数字、字母,并且不能超过20个',
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
	.landing_true {
		
	}
	.landing_false {
		background-color: #d8d8d8;
	}
	.uni-button[type=primary] {
		
	}
	.placeholdertext{
		/* #ifdef H5 */
		width: 48upx;
		/* #endif */
		
		/*#ifdef APP-PLUS */
		 width: 10upx;
		/* #endif */
		height:24upx;
	}
	.captch { 
		/* #ifdef H5 */
		width: 180upx;
		/* #endif */
		
	 /* #ifdef APP-PLUS */
	  width: 165upx;
	/* #endif */
	}
	.login-btn{
		padding: 10upx 20upx;
		margin-top: 100upx;
		text-align: center;
	}
	.uni-input{float: left;}
	.captchtext{font-size: 28upx; padding: 5upx 8upx;}
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
	.pass  .email {
		  /* #ifdef H5 */
			width: 320upx;
			/* #endif */
			
		 /* #ifdef APP-PLUS */
		  width: 420upx;
		/* #endif */
		}
	
	.login-input{
		padding: 20upx 20upx;
	}
	.locked {font-size: 100upx;
	  font-weight: 1000;
	   color:#ffffff;
	
	}
	.locked-head{
		background: #dcdcdc;
		text-align: center;
		border-radius: 100upx;
		width:130upx;
		height:130upx;
		margin: auto;
		padding: 25upx 25upx;
	}
	.login-card{
		background: #fff;
		border-radius: 12upx;
		padding: 10upx 25upx;
		/* box-shadow: 0 6upx 18upx rgba(0,0,0,0.12); */
		position: relative;
		margin-top: 115upx;
	}
	.login-bg {
		/* height: 260upx;
		padding: 25upx;
		background: linear-gradient(#FF978D, #FFBB69); */
	}
	
	page {
		background-color: #FFFFFF;
	}
</style>
