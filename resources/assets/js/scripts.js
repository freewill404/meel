require('./bootstrap');

window.Vue = require('vue');

Vue.component('when-input', require('./components/WhenInput.vue'));

const app = new Vue({
    el: '#app'
});
