try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

require('./layer/layer');


window.Vue = require('vue');

Vue.component('medium', require('./components/MediumComponent.vue'));
Vue.component('upload-file', require('./components/UploadFileComponent.vue'));


const app = new Vue({
    el: '#app',
    data: {
    },
    created: function () {
    },
    methods: {
    }
});