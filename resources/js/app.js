// require('./bootstrap');
import Vue from 'vue'
import router from './router'
import common from './common'
import Swal from 'sweetalert2'
import moment from 'moment'
Vue.mixin(common)

import LottieAnimation from 'lottie-vuejs/src/LottieAnimation.vue'
Vue.component('LottieAnimation', LottieAnimation)

window.Vue = require('vue').default

import VueTrix from 'vue-trix'
Vue.component('VueTrix', VueTrix)

import Table from './components/backend/common/Table.vue'
import NoData from './components/backend/common/NoData.vue'
import Header from './components/backend/partials/Header.vue'
import Sidebar from './components/backend/partials/Sidebar.vue'
import Footer from './components/backend/partials/Footer.vue'
import Breadcrumb from './components/backend/common/Breadcrumbs.vue'

Vue.component('Table', Table)
Vue.component('NoData', NoData)
Vue.component('Header', Header)
Vue.component('Sidebar', Sidebar)
Vue.component('Footer', Footer)
Vue.component("Breadcrumb", Breadcrumb);

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
})
window.Toast = Toast

const SwalCustomBtn = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success mx-1',
    cancelButton: 'btn btn-danger mx-1',
    denyButton: 'btn btn-info mx-1',
  },
  buttonsStyling: false,
})

window.SwalCustomBtn = SwalCustomBtn

Vue.filter('formatDate', function (value) {
  if (!value) return ''
  return moment(String(value)).format('DD MMM YYYY')
})
Vue.filter('formatDateTime', function (value) {
  if (!value) return ''
  return moment(String(value)).format('DD-MMM-YYYY hh:mm A')
})
Vue.filter('formatTime', function (value) {
  if (!value) return ''
  return moment(String(value)).format('hh:mm a')
})

Vue.filter('rupees', function (value) {
  if (!value) return '₹ 0'
  return '₹ ' + parseFloat(value).toFixed(1)
})
Vue.filter('precentage', function (value) {
  if (!value) return ''
  return parseFloat(value).toFixed(1) + '%'
})
Vue.filter('upperCase', function (value) {
  if (!value) return ''
  return value.toUpperCase()
})

Vue.component(
  'example-component',
  require('./components/ExampleComponent.vue').default,
)
Vue.component(
  'admin-component',
  require('./components/backend/admin/MainComponent.vue').default,
)
Vue.component(
  'center-component',
  require('./components/backend/service_center/MainComponent.vue').default,
)

const app = new Vue({
  el: '#app',
  router,
})
