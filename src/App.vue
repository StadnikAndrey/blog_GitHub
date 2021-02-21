<template>
  <div class="wrap" :class="wrap_overflow">     
    <header class="header">       
      <div class="wrap_head">
        <div class="head">
          <router-link 
            class="logo"
            to="/home"
            >
            {{domain}}!
          </router-link>  
          <p><svg class="gamburger"
          v-on:click="menuShow"  
          viewBox="0 0 24 24" fill="black" width="28px" height="28px"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg></p>
        </div>
      </div>        
        <nav class="overlay" :class="menu_up">
          <div class="wrap_close">
            <svg @click="closeMenu" class="close" viewBox="0 0 24 24" fill="black" width="38px" height="38px"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
          </div>          
          <ul class="menu">               
            <router-link v-for="(value,index) in menu" :key="index"
            :to="value.url"
            tag="li"
            active-class="activ_menu_link"             
            > 
            <span @click="closeMenu">{{ value.text }}</span>
            </router-link>             
          </ul>           
        </nav>       
    </header>
    <main class="main">
      <div class="content">         
        <router-view></router-view>
      </div>      
    </main>
    <footer class="footer">
      <router-link
        class="footer_logo"
        tag="a"
        to="/home"
        >
        {{domain}}! {{year}}
      </router-link>
    </footer> 
  </div>   
</template>

<script>
import {mapGetters} from 'vuex'
 
export default {
  name: 'App',
  data() {
    return{        
      menu_up: null,
      year: new Date().getFullYear()              
    }
  },
  computed: {		 
    ...mapGetters('user', {
      user: 'user'
    }),
    ...mapGetters('global', {
      wrap_overflow: 'app_wrap_overflow'
    }),
    ...mapGetters('global', {
      domain: 'domain'
    }),
    menu(){         
      let menu = [
        {url: '/about', text: 'О сайте'},
        {url: '/contacts', text: 'Контакты'}
      ];         
      let exit = { url: '/exit', text: 'Выйти', exact: false };
      let cabinet = { url: '/cabinet', text: 'Кабинет', exact: false };
      let entrance = { url: '/entrance', text: 'Вход', exact: false };
      let adminPanel = { url: '/adminpanel/', text: 'Админпанель', exact: false }; 
      let res = [];
      if (this.user) {
        res = [cabinet, exit];
        if (this.user.admin == 1) {
          res = [cabinet, adminPanel, exit];
        }
      }else{
        res = [entrance];
      }         
      menu.push(...res);         
      return menu;
    }  
	},
  methods: {
    menuShow() {       
      this.menu_up = 'overlay_active';                          
    },
    closeMenu() {       
      this.menu_up = 'overlay';                   
    }
  }
}
</script>

<style>
*{
  box-sizing: border-box;   
}  
html,
body {
  height: 100%; 
  font-family: 'Raleway', sans-serif;  
} 
.wrap, #app {     
  height: 100%;    
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}
@media screen and (max-width: 800px){
  .wrap_overflow{
    overflow: hidden; /* для мобильного меню админпанели*/
  }
}
.main {
	flex: 1 0 auto;
  background: #FAFAFA;
  padding: 50px 0 25px 0;
}
.footer {
	flex: 0 0 auto;
  text-align: center;
  padding: 20px 0;
  border-top: 1.5px solid #828282;   
}  
.content{    
  margin: 0 auto;
  max-width: 1170px; 
  width: 100%; 
  padding: 0 15px 0 15px;  
}
.gamburger{
  fill:#828282;
  cursor: pointer;
  transition: .5s;
}
.gamburger:hover{
  transform: scale(1.1);   
}
.wrap_head{
  position:fixed;
  background-color: #fff;
  z-index: 8;
  width: 100%;
  top: 0;   
  -webkit-box-shadow: 0px 4px 3px 0px rgba(34, 60, 80, 0.2);
  -moz-box-shadow: 0px 4px 3px 0px rgba(34, 60, 80, 0.2);
  box-shadow: 0px 4px 3px 0px rgba(34, 60, 80, 0.2);
}
.head{   
  max-width: 1170px;  
  padding: 10px 20px 10px 20px;   
  margin: 0 auto;    
  display: flex;
  justify-content: space-between;
  align-items: center;    
  font-size: 21px;   
}
.logo{
  text-decoration: none;
  color: #828282;
}
.overlay{
  display: flex;
  justify-content: center;
  position: absolute;
  top: -100%;  
  left: 0;
  background-color:#1F1F1F;
  width: 100%;
  height: 100%;   
  z-index: 9;
  overflow-y: auto; /*если большое меню   */
  transition: .7s; 
}
.overlay_active{
  position: fixed;
  top: 0;   
}
/* скрыть скролбар меню */
.overlay::-webkit-scrollbar {  
  width: 0px;   
  background: transparent;   
}
.menu{   
  color: #828282;
  font-size: 42px;   
  position: absolute;
  top: 10%;   
}
.menu li{
  padding: 20px 0;
  transition: .5s;
}
.menu li:hover{   
  cursor: pointer;
  transform: translateX(5px);
}
.activ_menu_link{
  color: blue;
}
.wrap_close{
  width: 100%;
  max-width: 1170px;
  padding: 10px 20px;
  text-align: right;
  margin: 0 auto;
}
.close{
  fill: #828282;
  cursor: pointer;
  transition: .5s;   
}
.close:hover{
  transform: scale(1.1);   
}
.footer_logo{ 
  text-decoration: none;     
  color: #828282;
  font-size: 15px;  
  cursor: pointer;
}  
@media screen and (max-width: 520px){
  .menu {      
    font-size: 35px;
  }
}  
</style>