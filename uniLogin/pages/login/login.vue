<template>
	<view style="padding-top: 40upx;">
		<view class="inputArea">
			<input v-model="loginPhone" placeholder="请输入手机号" type="number" maxlength="11" class="inputClass" />
		</view>
		<view class="inputArea">
			<input v-model="loginPassword" placeholder="请输入登录密码" type="password" class="inputClass" />
		</view>
		<view style="padding: 0 10%;">
			<text style="color: red;">{{message}}</text>
		</view>
		<view class="inputArea">
			<button style="border-radius:22px;" type="primary" @click="goLogin">登 录</button>
		</view>
		<view class="inputArea">
			<text style="float:right;color:blue;" @click="openRegisterPage">>>注册>></text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loginPhone: '',
				loginPassword: '',
				message: ''
			}
		},
		onLoad() {
			console.log('login页面onLoad');
		},
		methods: {
			openRegisterPage() {
				uni.navigateTo({
					url: '../login/register',
					success: res => {},
					fail: () => {},
					complete: () => {}
				});
			},
			goLogin() {
				let loginPhone = this.loginPhone;
				let loginPassword = this.loginPassword;
				if (!(/^1(3|4|5|7|8|9)\d{9}$/.test(loginPhone))) {
					this.message = "手机号码有误，请重填";
					return false;
				}
				if (!loginPassword) {
					this.message = "密码为空";
					return false;
				}
				uni.showLoading({
					title: '登录中。。。',
					mask: false
				});
				let headers = {};
				headers['content-type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
				let PHPSESSID = uni.getStorageSync('PHPSESSID'); 
				if (PHPSESSID) {
					headers['cookie'] = 'PHPSESSID=' + PHPSESSID;//将PHPSESSID放入请求头中,如你有其他cookies都可以缀后面，分号分割。浏览器端本身就有cookies机制，不设置
				}
				uni.request({
					url: this.$url + '/api/login/login.php',//此处使用了全局变量拼接url（main.js文件中），关于全局变量官方问答里有
					method: 'POST',//get或post
					header: headers,
					data: {
						phone: this.loginPhone,
						pw: this.loginPassword
					},
					success: res => {
						console.log(res);
						let cookies = res.cookies;
						if (cookies) {
							for (let i = 0; i < cookies.length; i++) {
								if (cookies[i].name == 'PHPSESSID') {//PHPSESSID从cookies取出，放入本地储存
									uni.setStorageSync('PHPSESSID', cookies[i].value);
									break;
								}
							}
						}
						//返回的基本信息做本地缓存
						let data = res.data;
						if (data.ec === 0) {
							uni.setStorageSync('userinfo', data.user);
							uni.hideLoading();
							uni.reLaunch({
								url: '../index/indexme'
							});
						} else {
							uni.removeStorageSync('userinfo');
							this.message = data.msg;
							uni.hideLoading();
						}
					},
					fail: () => {
						uni.hideLoading();
						this.message = "网络连接失败";
					},
					complete: () => {}
				});
			}
		}
	}
</script>

<style>
	.inputArea {
		padding: 20upx 10%;
	}

	.inputClass {
		border: 2px solid #ccc;
		border-radius: 22px;
		outline: 0;
		padding: 8px 15px;
	}
</style>
