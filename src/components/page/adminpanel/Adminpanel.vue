<template>
  <div v-if="user&&user.admin==1">
    <div class="wrap_adminpanel">
      <div class="sidebar_adminpanel" :class="active_sidebar">
        <div class="content_sidebar">
          <div class="close_wrap"><svg @click="close_menu" class="menu_admin_close" viewBox="0 0 24 24" fill="black" width="36px" height="36px"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></div>
          <router-link v-for="(value, index) in menu_admin" :key="index"  
            class="item_menu_adminpanel"
            tag="p"
            :to="value.path"
            active-class="active_item_menu"						 		 
            >
           <span @click="close_menu"> {{value.name}}</span>
          </router-link>           
        </div>
      </div>
      <div class="content_adminpanel">
        <span><svg @click="open_menu" class="menu_admin_open" viewBox="0 0 24 24" fill="black" width="36px" height="36px"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/></svg></span>  
        <div class="scrim" :class="active_scrim"></div>
        <router-view></router-view> 
      </div>
    </div>   
  </div>       
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'Adminpanel',   
  data() {
    return {       
      active_sidebar: '',
      active_scrim: ''    
    }
  },
  methods:{
    open_menu(){
      this.active_sidebar = 'active_sidebar';
      this.active_scrim = 'active_scrim';
      setTimeout(() => {
        this.$store.dispatch('global/app_wrap_overflow', 'wrap_overflow');
      }, 500);                
    },
    close_menu(){
      this.active_sidebar = '';
      this.active_scrim = '';
      setTimeout(() => {
        this.$store.dispatch('global/app_wrap_overflow', '');
      }, 500);               
    }   
  },
  computed:{
    ...mapGetters('user', {
    user: 'user'
    }),
    menu_admin() {         
      let menu = [
        {name: 'Статьи', path: '/adminpanel/articles'},
        {name: 'Рубрики', path: '/adminpanel/rubrics/'}
      ];         
      let users = { path: '/adminpanel/users', name: 'Пользователи', exact: false };        
      if (this.user.super_admin == 1) {
        menu.push(users);
      }                 
      return menu;
    }  
  }
}
</script>
 
<style>
.wrap_adminpanel{  
  display: flex;
  justify-content: space-between;
  padding: 25px 0;
}
.sidebar_adminpanel{   
  width: 25%;   
}
.content_adminpanel{   
  width: 75%;
  padding: 0 0 0 15px;
}
.content_sidebar{
  position: sticky; 
  top: 100px;   
}
.item_menu_adminpanel{   
  padding: 8px 8px 8px 8px;
}
.item_menu_adminpanel:hover{
  cursor: pointer;
  background: rgb(202, 204, 203);   
}
.active_item_menu{
  background: rgb(202, 204, 203);
}
.menu_admin_close,
.menu_admin_open{   
  transition: 1s;
  display: none;
} 
.menu_admin_open:hover{   
  transform: scale(1.2);
  cursor: pointer;
  transition: 1s;
}
.menu_admin_close:hover{   
  transform: rotate(360deg);
  cursor: pointer;
  transition: 1s;
} 
@media screen and (max-width: 800px){
  .scrim{
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    transition-property: opacity;   
    opacity: 0;
    background: var(--app-drawer-scrim-background, rgba(0, 0, 0, 0.5));   
    z-index: -1;
    visibility: hidden;
  } 
  .wrap_adminpanel{
    display: block;     
  }
  .menu_admin_close,
  .menu_admin_open{    
    display: block;
  }
  .content_adminpanel{
    width: 100%;
    padding: 0;
  }   
  .sidebar_adminpanel{
    position: absolute;
    top: 51px;
    bottom: 0; 
    left: -280px;
    width: 260px; 
    z-index: 1;    
    background: #fff;     
    transition: 1s;  
    overflow: auto; 
    padding: 20px 0;  
  }
  .content_sidebar{  
    position: static;  
  }  
  .item_menu_adminpanel{
    padding: 10px 20px;
  }   
  .close_wrap{
    padding: 6px 20px;
  }   
}
.active_sidebar{
  left: 0;
}
.active_scrim{
  visibility: visible;
  transition: 1s;
  opacity: 1;
  z-index: 0;
} 
</style>