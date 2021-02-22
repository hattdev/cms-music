/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue');
 window.bootbox = require('bootbox');

require('./browser.js');
require('./main.js');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//

Vue.filter('formatSize', function (size) {
    if (size > 1024 * 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024 / 1024).toFixed(2) + ' TB'
    } else if (size > 1024 * 1024 * 1024) {
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB'
    } else if (size > 1024 * 1024) {
        return (size / 1024 / 1024).toFixed(2) + ' MB'
    } else if (size > 1024) {
        return (size / 1024).toFixed(2) + ' KB'
    }
    return size.toString() + ' B'
})
Vue.component('hatt-menu', require('./components/Menu.vue').default);
Vue.component('hatt-menu-mobile', require('./components/MenuMobile.vue').default);
Vue.component('hatt-pagination', require('./components/Pagination.vue').default);
Vue.component('hatt-editor', require('./components/Form/Editor.vue').default);
Vue.component('hatt-datepicker', require('./components/Globals/datepicker').default);
Vue.component('hatt-uploads', require('./components/Globals/field-upload').default);
Vue.component('hatt-select2', require('./components/Globals/field-select2').default);
Vue.component('hatt-datatable', require('./components/Globals/datatable').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from "vue";
import router from "./route";
import {store} from "./store";
import { abilitiesPlugin } from '@casl/vue';
import ability from './ability';
Vue.use(abilitiesPlugin, ability);
Vue.config.devtools = true;

router.beforeEach((to, from, next) => {
    if(typeof to.meta.permissions !='undefined'){
        if (!ability.can(to.meta.permissions)) {
            cmsHattApp.showError({
                message: "Bạn ko có quyền truy cập mục này!"
            });
            // next('/')
            next(from)
        }
        next()
    }
    else {
        next()
    }

});
const app = new Vue({
    el: '#app',
    data:function(){
        return {
            user_name: $('meta[name="auth-name"]').attr('content'),
            user_api: $('meta[name="auth-token"]').attr('content'),
    }
    },
    store,
    router,
    ability
});






