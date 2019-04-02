<template>
	<view>
		<view @click="openLoginPage">
			{{text}}
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				loginState: false,
				text: ''
			}
		},
		onLoad() {
			console.log('indexme页面onLoad');
			let userinfo = uni.getStorageSync('userinfo');
			if (userinfo && userinfo.phone) {
				this.loginState=true;
				this.text = "已登录，电话：" + userinfo.phone + "；注册时间：" + userinfo.regtime + "；点击退出";
			} else {
				this.text = "点击登录";
				this.loginState=false;
			}
		},
		methods: {
			openLoginPage() {
				if (this.loginState) {uni.navigateTo({
						url: '../login/logout',
						success: res => {},
						fail: () => {},
						complete: () => {}
					});
				} else {
					uni.navigateTo({
						url: '../login/login',
						success: res => {},
						fail: () => {},
						complete: () => {}
					});
				}
			}
		}
	}
</script>

<style>

</style>
