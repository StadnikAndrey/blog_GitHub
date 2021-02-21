<template>
   <div>
      <h2 class="insert_article_title">Редактирование рубрики</h2>
      <form novalidate="true" ref="formAdmUpd">         
        <div class="form_row">
          <label for="" class="form_label">Название рубрики</label>
          <input type="text" v-model.trim="clone.name" @input="validation" name="name" class="form_input" :class="name.inputErrClass">
          <p class="form_error">{{errorClient.name}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">URL</label>
          <input type="text" v-model.trim="clone.url" @input="validation" name="url" class="form_input" :class="url.inputErrClass">
          <p class="form_error">{{errorClient.url}}</p>          
        </div>       
        <button type="button" v-on:click="sendInsertForm" class="form_button">Редактировать рубрику</button>  
      </form> 
      <div class="server_responce_error">
        <p v-for="(value, index) in errorRequest" :key="index">{{value}}</p>
      </div>
   </div>       
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'AdminRubricsUpdate',
  metaInfo() {
		return {
			title: this.domain + ' - Админпанель:Редактирование рубрики',
			meta: [
			{ vmid: 'description', name: 'description', content: "Редактирование рубрики" + this.domain }
			]
		}
	},   
  created() {
    this.$store.dispatch('user/oneRubric', this.$route.params.id);
  },
  data() {
    return {       
      name: {        
        inputErrClass: ''
      },
      url: {         
        inputErrClass: ''
      }, 
      errorClient: {},
      countRecursion: 0,
      errorRequest: []     
    }
  },
  computed: {
    ...mapGetters('user', {
      oneRubric: 'oneRubric'
    }),     
    clone(){        
      let newObj = {...this.oneRubric};
      return newObj;
    },
    ...mapGetters('global', {
      domain: 'domain'
    })
  },
  methods: {
    validation(e){
      if (e.target.getAttribute('name') == 'name') {
        if(/^.{1,}$/g.test((e.target.value).trim())) {
          this.$delete(this.errorClient, 'name'); 
          this.name.inputErrClass = '';         
        }else{
          this.$set(this.errorClient, 'name', "Название рубрики не может быть пустым!"); 
          this.name.inputErrClass = 'form_input_error';
        }        
      } 
      if (e.target.getAttribute('name') == 'url') {
        if(/^[a-zA-Z0-9]{1,}$/g.test((e.target.value).trim())) {
          this.$delete(this.errorClient, 'url'); 
          this.url.inputErrClass = '';         
        }else{
          this.$set(this.errorClient, 'url', "URL может содержать символы латинского алфавита и цифры без пробелов!"); 
          this.url.inputErrClass = 'form_input_error';
        }        
      }       
    },
    sendInsertForm(){       
      if (Object.keys(this.errorClient).length === 0 && this.errorClient.constructor === Object) {
        let form = this.$refs.formAdmUpd;
        let formData = new FormData(form);  
        formData.append('id', this.$route.params.id);     
        this.$http.post('admin_rubric_update.php', formData)
        .then(response => response.json())
        .then(data => {  
          if (data.res) {
            this.countRecursion = 0;
            if (data.error) {
              this.errorRequest=data.error;
            }else{
              this.errorRequest = [];
              this.$router.push({path: '/adminpanel/rubrics/adminpanel/rubrics/main'});
            }          
          }else{
            this.cleanUser();
            this.$router.push({path: '/home'});
          }                                       
        })
        .catch(()=>{
          if (this.countRecursion==0) { 
            this.countRecursion++;    
            setTimeout(() => {
              this.sendInsertForm();
            }, 500);           
          }                             
        }); 
      }
    }
  }
}
</script>