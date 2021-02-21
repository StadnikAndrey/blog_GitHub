<template>
   <div class="entrance_wrap">
      <form novalidate="true" ref="formR">
        <p class="form_title">Регистрация</p>
        <div class="form_row">
          <label for="" class="form_label">логин</label>
          <input type="text" name="login" v-model.trim="login.value" class="form_input"
          :class="login.inputErrClass">
          <p class="form_error">{{errors.login}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">e-mail</label>
          <input type="text" name="email" v-model.trim="email.value" class="form_input"
          :class="email.inputErrClass">
          <p class="form_error">{{errors.email}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">пароль</label>
          <input type="password" name="password" v-model.trim="password.value" class="form_input"
          :class="password.inputErrClass">
          <p class="form_error">{{errors.password}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">подтверждение пароля</label>
          <input type="password" name="password_verify" v-model.trim="passwordVerify.value" class="form_input"
          :class="passwordVerify.inputErrClass">
          <p class="form_error">{{errors.password_verify}}</p>
        </div>
        <router-link class="registration_link" 
        tag="p"
        :to="'/agreement'"					 				 
        >Регистрируясь вы соглашаетесь с правилами
        </router-link>
        <button type="button" v-on:click="sendForm" class="form_button">Создать аккаунт</button>  
      </form>  
      <div>
        <p class="error_request" v-for="(value, index) in errorRequest" :key="index">
        {{value}}
        </p>
      </div>       
    </div>   
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: 'Registration',
  metaInfo () {
      return {
        title: this.domain + ': Регистрация',
        meta: [
          { vmid: 'description', name: 'description', content: this.domain + ": Регистрация" }
        ]
      }
  },
  data(){
    return{
      errors: {},
      login: {
        value: '',
        inputErrClass: ''
      },
      email: {
        value: '',
        inputErrClass: ''
      },
      password: {
        value: '',
        inputErrClass: ''
      },
      passwordVerify: {
        value: '',
        inputErrClass: ''
      },
      errorRequest: []       
    }
  },
  computed: {
    ...mapGetters('global', {
      domain: 'domain'
    })
  },
  methods: {
    sendForm(){
      if(/^[a-zA-Z0-9_-]{5,20}$/g.test(this.login.value)) {
        this.$delete(this.errors, 'login'); 
        this.login.inputErrClass = '';
      }else{
        this.$set(this.errors, 'login', "Логин может содержать латиницу, нижнее подчеркивание и быть одним словом!"); 
        this.login.inputErrClass = 'form_input_error';
      }
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
        if (this.password.value === this.passwordVerify.value) {
            this.$delete(this.errors, 'password_verify'); 
            this.passwordVerify.inputErrClass = '';
        }else{
          this.$set(this.errors, 'password_verify', "Пароль и подтверждение не совпадают!"); 
          this.passwordVerify.inputErrClass = 'form_input_error';
        }
      }else{
        this.$set(this.errors, 'password', "Введите правильный пароль"); 
        this.password.inputErrClass = 'form_input_error';
      }
      if (Object.keys(this.errors).length === 0 && this.errors.constructor === Object) {
        let form = this.$refs.formR;
        let formData = new FormData(form);       
        this.$http.post('registration.php', formData)
        .then(response => response.json())
        .then(data => {
          if (data === 1) {
            this.$router.push('entrance');
          }else{
            this.errorRequest = data;
          }                    
        }); 
      }                    
    }
  }
}
</script>
 
<style> 
.registration_link{
  margin: 0 0 25px 0;
  text-decoration: underline;
  color: #3838a0;
}
.registration_link:hover{
  cursor: pointer;
}
.error_request{
  font-family: 'Raleway', sans-serif;
  color: red;
  margin: 10px 0 0 0;
} 
</style>