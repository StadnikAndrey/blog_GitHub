import Vue from 'vue'
import App from './App.vue'

import router from './router.js'
import { store } from './store'

import VueMeta from 'vue-meta'
Vue.use(VueMeta)

import * as Tokens from './utils/tokens';
 
import VueResource from 'vue-resource'
Vue.use(VueResource)
Vue.http.options.root = '/api/';
Vue.http.options.credentials = true; 

let requestRefresh = true; //для блокировки множественных обращений на refresh

Vue.http.interceptors.push(function (request) {   
  let accessToken = Tokens.getAccessToken();
  if (accessToken !== null) {     
    request.headers.set('Authorization', `Bearer ${accessToken}`);
  }
  // return response callback
  return function (response) {     
    if (response.status === 401 && requestRefresh === true) {       
      requestRefresh = false;       
      Vue.http.get('refresh.php')
      .then(response => response.json())
      .then(async (data) => {              
        if (data.res === false) { // если небыло RT или он устарел, или забанен пользователь                      
          await store.dispatch('user/cleanUser');
          router.push({path: '/home'});           
        }else{             
          Tokens.setTokens(data.accessToken);
          let user = Tokens.getJWTPayload(data.accessToken);             
          await store.dispatch('user/setUser', user);                     
        }                
      })
      .then(() => requestRefresh = true);  
    } 
  }  
}) 
 
store.dispatch('user/autoLogin'); // проверка входа на сайт пользователя при перезагрузке
new Vue({
  router,
  store,
  render: h => h(App)   
}).$mount('#app')