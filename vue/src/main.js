import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import axios from "axios";
import VModal from 'vue-js-modal'


Vue.config.productionTip = false
Vue.use(BootstrapVue);
Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(VModal);

Vue.$http = axios


const router = new VueRouter({
  mode: 'history',
  routes: [{
      path: '/',
      component: require("./components/Tables.vue").default,
      name: 'Listetable'
    },
    {
      path: '/info-table-:table',
      component: require("./components/InfoTable.vue").default,
      name: 'infoTable'
    },
    {
      path: '*',
      redirect: '/'
    }
  ]
})


Vue.prototype.$urlApi = "http://127.0.0.1/addsite/ProjetSI/ProjetSIGit/"

new Vue({
  router,
  render: h => h(require('./App.vue').default),
}).$mount('#app')