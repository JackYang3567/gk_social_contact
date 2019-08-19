import Vue from 'vue';
import App from './App';
import _mixins from './common/_mixins';

// 关闭生产提示
Vue.config.productionTip = false;
// 小程序页面组件和这个`App.vue`组件的写法和引入方式是一致的，为了区分两者，需要设置`mpType`值
App.mpType = 'app';

Vue.mixin(_mixins);

const app = new Vue({
    ...App
});
app.$mount();
