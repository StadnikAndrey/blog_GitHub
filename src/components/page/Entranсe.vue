<template>
   <div class="entrance_wrap">
      <form novalidate="true" ref="formE">
        <p class="form_title">Вход</p>
        <div class="form_row">
          <label for="" class="form_label">e-mail</label>
          <input type="text" name="email" v-model.trim="email.value" class="form_input" :class="email.inputErrClass">
          <p class="form_error">{{errors.email}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">password</label>
          <input type="password" name="password" v-model.trim="password.value" class="form_input" :class="password.inputErrClass">
          <p class="form_error">{{errors.password}}</p>
        </div>
        <button type="button" v-on:click="sendForm" class="form_button">Войти</button>  
      </form>
      <div>
        <p class="error_request" v-for="(value, index) in errorRequest" :key="index">
        {{value}}
        </p>
      </div>  
      <router-link class="entrance_link" 
        tag="p"
        :to="'/registration'"					 				 
        >Регистрация
			</router-link>       
    </div>   
</template>

<script>
import  {mapGetters}  from 'vuex';
import  { mapActions}   from 'vuex';

export default {
  name: 'Entrance',
  metaInfo () {
      return {
        title: this.domain + ': Вход',
        meta: [
          { vmid: 'description', name: 'description', content: "Вход на сайт " + this.domain }
        ]
      }
  },
  data(){
    return{
      errors: {},       
      email: {
        value: '',
        inputErrClass: ''
      },
      password: {
        value: '',
        inputErrClass: ''
      },
      errorRequest: ''
    }
  },
  computed: {
    ...mapGetters('global', {
      domain: 'domain'
    })
  },
  methods: {
    ...mapActions('user', ['login']),
    async sendForm(){       
      if(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g.test(this.email.value)) {
        this.$delete(this.errors, 'email'); 
        this.email.inputErrClass = '';
      }else{
        this.$set(this.errors, 'email', "Введите правильный email"); 
        this.email.inputErrClass = 'form_input_error';
      }
      if(/^[.a-zA-Z0-9_-]{6,20}$/g.test(this.password.value)) {
        this.$delete(this.errors, 'password'); 
        this.password.inputErrClass = '';         
      }else{
        this.$set(this.errors, 'password', "Введите правильный пароль"); 
        this.password.inputErrClass = 'form_input_error';
      }
      if (Object.keys(this.errors).length === 0 && this.errors.constructor === Object) {
        let form = this.$refs.formE;
        let formData = new FormData(form);         
        let response = await this.login(formData); 
        if (response.error == 'exit') {
            this.$router.push('E404');
        }
        if (response.res === false) {
            this.errorRequest = response.error; 
        } 
        if (response.res === true) {
          this.email.value = '';
          this.email.inputErrClass = '';
          this.password.value = '';
          this.password.inputErrClass = '';
          this.errorRequest = ''; 
          this.$router.push({ path: '/cabinet' });
        }          
      }                    
    }     
  }
}
</script>
 
<style>
.entrance_wrap{
  border: 1px solid #828282;
  max-width: 400px;
  margin: 50px auto 0 auto;
  padding: 0 25px 50px 25px;
}
.form_title{   
  text-transform: uppercase;
  font-weight: bold;
  text-align: center;
  padding: 50px 0 50px 0;
}
.form_row{
  padding: 0 0 25px 0;
}
.form_label{
  display: block;
  padding: 0 0 5px 0;   
  text-transform: uppercase;
  font-size: 12px;   
}
.form_input{   
  font-size: 16px;   
  width: 100%;
  padding: 10px 10px 10px 10px;
}
.form_input_error{
  border: 1px solid red;
}
.form_error{
  color: red;
}
.form_button{
  width: 100%;
  background: #5cbe65; 
  color: #fff; 
  padding: 10px;    
  text-transform: uppercase;
  font-size: 12px;
  border: none;
  outline: none;
}
.form_button:hover{
  cursor: pointer;
  background: #25af32;   
}
.entrance_link{  
  color: #3838a0;
  padding: 25px 0 0 0;   
  text-transform: uppercase;   
}
.entrance_link:hover{
  cursor: pointer;
} 
</style>