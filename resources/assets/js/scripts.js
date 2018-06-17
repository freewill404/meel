require('./bootstrap');

window.Vue = require('vue');

Vue.component('when-input',     require('./components/WhenInput.vue'));
Vue.component('request-format', require('./components/RequestFormat.vue'));
Vue.component('email-schedule', require('./components/EmailSchedule.vue'));
Vue.component('meel-example',   require('./components/MeelExample.vue'));

const app = new Vue({
    el: '#app'
});
