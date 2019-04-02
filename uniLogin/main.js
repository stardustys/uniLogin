import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false
Vue.prototype.$url = 'http://192.168.1.84:81'; 

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
