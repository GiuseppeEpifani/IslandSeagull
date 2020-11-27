require('./bootstrap');
require('sweetalert2')
require('datatables.net')
require('datatables.net-bs4')
require('datatables.net-responsive-dt')

window.Vue = require('vue');

//deshboard
Vue.component('deshboard', require('./components/main/DeshboardComponent.vue').default);

//User
Vue.component('table-data-user', require('./components/main/TableUserComponent.vue').default);
Vue.component('profile-user', require('./components/main/ProfileUserComponent.vue').default);

//General
Vue.component('error', require('./components/ErrorComponent.vue').default);
Vue.component('dropzone-add', require('./components/DopzoneAddComponent.vue').default);
Vue.component('dropzone-update', require('./components/DropzoneUpdateComponent.vue').default);

//Specie
Vue.component('table-data-specie', require('./components/specie/TableSpecieComponent.vue').default);
Vue.component('update-specie', require('./screen/specie/UpdateSpecieComponent.vue').default);
Vue.component('new-specie', require('./screen/specie/NewSpecieComponent.vue').default);

//Kind
Vue.component('table-data-kind', require('./components/specie/TableKindComponent.vue').default);
Vue.component('new-kind', require('./screen/specie/NewKindComponent.vue').default);
Vue.component('update-kind', require('./screen/specie/UpdateKindComponent.vue').default);

//Content
Vue.component('table-data-content', require('./components/content/TableContentComponent.vue').default);
Vue.component('update-content', require('./screen/content/UpdateContentComponent.vue').default);
Vue.component('new-content', require('./screen/content/NewContentComponent.vue').default);


const app = new Vue({
    el: '#app',
});
