require('./bootstrap');

window.Vue = require('vue');

Vue.component('when-input',     require('./components/WhenInput.vue'));
Vue.component('request-format', require('./components/RequestFormat.vue'));

const app = new Vue({
    el: '#app'
});
