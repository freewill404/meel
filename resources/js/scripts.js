require('./bootstrap');

window.Vue = require('vue');

Vue.config.productionTip = false;
Vue.config.devtools = false;

Vue.component('when-input',     require('./components/WhenInput.vue').default);
Vue.component('email-schedule', require('./components/EmailSchedule.vue').default);
Vue.component('meel-example',   require('./components/MeelExample.vue').default);

const app = new Vue({
    el: '#app',
});
