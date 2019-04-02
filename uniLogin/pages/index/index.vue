<template>
	<view class="content">
        <image class="logo" src="../../static/logo.png"></image>
		<view>
            <text class="title">{{title}}</text>
        </view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				title: '主页————星尘',
			}
		},
		onLoad() {
			console.log('index页面onLoad');
			//拉取登录状态
			uni.showLoading({
				title: 'waiting',
				mask: false
			});

			let headers = {};
			let PHPSESSID = uni.getStorageSync('PHPSESSID');
			if (PHPSESSID) {
				headers['cookie'] = 'PHPSESSID=' + PHPSESSID;//将PHPSESSID放入请求头中,如你有其他cookies都可以缀后面，分号分割。浏览器端本身就有cookies机制，不设置
			}
			uni.request({
				url: this.$url+'/api/userinfo.php',//此处使用了全局变量拼接url（main.js文件中），关于全局变量官方问答里有
				method: 'GET',//get或post
				header: headers,
				data: {},
				success: res => {
					console.log(res);
					let cookies=res.cookies;
					if(cookies){
						for(let i=0;i<cookies.length;i++){
							if(cookies[i].name=='PHPSESSID'){//PHPSESSID从cookies取出，放入本地储存
								uni.setStorageSync('PHPSESSID', cookies[i].value);
								break;
							}
						}
					}
					let data = res.data;
					if(data.ec===1){
						uni.setStorageSync('userinfo', data.user);//储存其他信息
					}else{
						uni.removeStorageSync('userinfo');
					}
					uni.hideLoading();
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
		},
		methods: {

		}
	}
</script>

<style>
	.content {
		text-align: center;
		height: 400upx;
	}
    .logo{
        height: 200upx;
        width: 200upx;
        margin-top: 200upx;
    }
	.title {
		font-size: 36upx;
		color: #8f8f94;
	}
</style>
