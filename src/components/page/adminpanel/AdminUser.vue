<template>
  <div>
    <h3 class="admin_child_title">Управление пользователями</h3>
    <p><span class="admin_users_sql" @click="sql" data-field="admin" data-val="1">Администраторы сайта</span></p>
    <p><span class="admin_users_sql" @click="sql" data-field="is_ban" data-val="1">Заблокированные</span></p>
    <div class="admin_one_user" v-for="(value, i) in JSON.parse(JSON.stringify(users))" :key="value.id">
      <p class="admin_one_user_title">id: <span class="admin_one_user_title_num">{{value.id}}</span> зарегистрирован
       {{value.date_add}}</p>
      <div class="admin_one_user_info">
        <div class="admin_one_user_info_left">		 
          <p class="adm_one_user_info_body">Login: {{value.login}}</p>           
          <p class="adm_one_user_info_body">Email: {{value.email}}</p>
        </div>
        <div class="admin_one_user_info_rigt">
          <form ref="admUserForm" class="admin_user_form_up" @submit.prevent>
            <div class="admin_user_form_up_row">
              <label for="" class="admin_user_form_up_label">Заблокирован:</label>
              <select name="is_ban" v-model="value.is_ban" class="admin_user_form_up_select">
                <option value="0">Нет</option>
                <option value="1">Да</option>	 
              </select>					
            </div>
            <div class="admin_user_form_up_row">
              <label for="" class="admin_user_form_up_label">Администратор:</label>
              <select name="admin" v-model="value.admin" class="admin_user_form_up_select">
                <option value="0">Нет</option>
                <option value="1">Да</option>	 
              </select>					
            </div>
            <div class="admin_user_form_up_row">
              <label for="" class="admin_user_form_up_label">Главный администратор:</label>
              <select name="super_admin" v-model="value.super_admin" class="admin_user_form_up_select">
                <option value="0" >Нет</option>
                <option value="1">Да</option>	 
              </select>					
            </div>            
            <button type="button" @click="sendForm(i)" class="admin_user_form_up_btn">Сохранить</button> 
          </form>
        </div>
      </div>
    </div>      
  </div>       
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'AdminUser',
  metaInfo() {
		return {
			title: this.domain + ' - Админпанель: Пользователи',
			meta: [
			{ vmid: 'description', name: 'description', content: "Управление пользователями" + this.domain }
			]
		}
	},  
  created() {      
    this.$store.dispatch('user/users', {});                    
  },
  data() {
    return {       
      countRecursion: 0      
    }
  },
  computed: {
    ...mapGetters('user', {
      users: 'users'
    }),
    ...mapGetters('global', {
      domain: 'domain'
    })
  },
  methods: {
    sql(e){
      let k = e.target.dataset.field;
      let val = e.target.dataset.val;
      let params = {[k]: val};
      this.$store.dispatch('user/users', params);
    },
    sendForm(i) {
      let formData = new FormData(this.$refs.admUserForm[i]);
      formData.append('id_user', this.users[i].id);      
      this.$http.post('admin_update_user.php', formData)       
      .then(() => {
        this.countRecursion = 0;
      })
      .catch(()=>{
        if (this.countRecursion == 0) { 
          this.countRecursion++;    
          setTimeout(() => {
            this.sendForm(i);
          }, 500);           
        }                             
      });        
    }
  }
}
</script>
 
<style>
.admin_child_title{
  padding: 8px 0 25px 0;
} 
.admin_users_sql{
  display: inline-block;     
  font-size: 16px;
  margin: 15px 0 0 0;
  text-transform: uppercase;
  text-decoration: none; 
  margin: 0 0 20px 0;
  color: #CE7082;
  border-bottom: 1px dashed;
}
.admin_users_sql:hover{
  cursor: pointer;
}
.admin_one_user{    
  padding: 15px 10px;
  border: 1px solid #d1d1d1;      
}
.admin_one_user_title{     
  font-size: 18px;
  background-color: #45D09E;
  padding: 6px;
}
.admin_one_user_title_num{
  font-weight: bold;
  border-bottom: 1px dashed;
}
.admin_one_user_info{
  display: flex;  
  justify-content: space-between;  
}
.admin_one_user_info_left{
  width: 60%;
}
.adm_one_user_info_body{     
  font-size: 17px;
  margin: 5px 0 0 0 ;     
}
.admin_one_user_info_rigt{
  padding: 0 0 0 10px;
  width: 40%;
}
/*форма редактирования*/ 
.admin_user_form_up_row{
  padding: 6px 0;
}
.admin_user_form_up_label{   
  display: block ;
  font-size: 10px ;
  color: #7f7f7f;
  text-transform: uppercase;
  padding: 0 0 6px;
}
.admin_user_form_up_input,
.admin_user_form_up_select{ 
  font-size: 15px;
  background-color: #e5e5e5;
  border: 1px #e5e5e5 solid;
  width: 100%;
  max-width: 100%;
  resize: none;
  padding: 5px ;
}
.admin_user_form_up_btn{ 
  width: 100%;     
  text-transform: uppercase;   
  padding: 8px 27px 8px 30px;
  text-align: center;
  font-size: 16px;      
  background-color: #AFCFEA;
  color: #fff;
  transition: all .3s ease;
  border: 0;     
}
.admin_user_form_up_btn:hover{
  color: red;
  cursor: pointer;
}
@media screen and (max-width: 830px){
  .admin_users_form{
    max-width: 100%;
  }   
} 
@media screen and (max-width: 720px){
  .admin_one_user_info_left,
  .admin_one_user_info_rigt{
    width: 50%;
  }
}
@media screen and (max-width: 580px){
  .admin_one_user_info {
    display: block; 
  }
  .admin_one_user_info_left,
  .admin_one_user_info_rigt{
    width: 100%;
  }
  .admin_one_user_info_rigt {
    padding: 0;
  }  
  .admin_user_form_up{
    margin: 20px 0 0 0 ;
  }
}
@media screen and (max-width: 550px){
  .admin_orders_form{
    display: block;
  }
  .admin_orders_form_row{
    padding: 6px 0;
  }
  .admin_orders_form_btn {     
    width: 100%;     
    padding: 8px 30px 8px 30px;
  }
}
</style>