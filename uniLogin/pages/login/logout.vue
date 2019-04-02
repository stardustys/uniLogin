<template>
	<view style="padding-top: 40upx;">
		<view style="padding: 0 10%;">
			<view class="listcell">
					电话:{{userinfo.phone}}
			</view>
			<view class="listcell">
					注册时间:{{userinfo.regtime}}
			</view>
			<view class="listcell">
					其他:等等等等
			</view>
			<view class="listcell" @click="logout" style="color: blue;">
					点此登出
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				userinfo: '',
			}
		},
		onLoad() {
			console.log('logout页面onLoad');
			this.userinfo = uni.getStorageSync('userinfo');
		},
		methods: {
			logout() {
				uni.showLoading({
					title: 'waiting',
					mask: false
				});
				
				let headers = {};
				let PHPSESSID = uni.getStorageSync('PHPSESSID');
				if (PHPSESSID) {
					headers['cookie'] = 'PHPSESSID=' + PHPSESSID;
				}
				uni.request({
					url: this.$url+'/api/login/logout.php',
					method: 'GET',
					header: headers,
					data: {},
					success: res => {
						if(res.data.ec===0){
							uni.removeStorageSync('PHPSESSID');//清空session
							uni.removeStorageSync('userinfo');
						}
						uni.hideLoading();
						uni.reLaunch({
							url: '../index/indexme'
						});
					},
					fail: () => {
						uni.hideLoading();
						uni.showToast({
							title: '网络连接失败',
							duration: 5000
						});
					},
					complete: () => {}
				});
				
				
			}
		}
	}
</script>

<style>
.listcell{
	padding: 10upx 0;
	border-bottom: 1px solid #ccc;
}
</style>
