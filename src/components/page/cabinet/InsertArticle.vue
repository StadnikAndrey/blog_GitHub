<template>
   <div v-if="user">       
    <h2 class="insert_article_title">Добавление статьи</h2>
    <form novalidate="true" ref="formInsertArticle" enctype="multipart/form-data">         
        <div class="form_row">
          <label for="" class="form_label">Заголовок</label>
          <input type="text" v-model.trim="title.value" name="title" class="form_input" :class="title.inputErrClass">
          <p class="form_error">{{errorClient.title}}</p>
        </div>
        <div class="form_row">
          <label for="" class="form_label">Подзаголовок</label>
          <input type="text" v-model.trim="subtitle.value" name="subtitle" class="form_input" :class="subtitle.inputErrClass">
          <p class="form_error">{{errorClient.subtitle}}</p>          
        </div>
        <div class="form_row">
          <label for="" class="form_label">Рубрика</label>
          <select class="form_input" v-model.trim="rubric_id.value" name="rubric_id" id="" :class="rubric_id.inputErrClass">
            <option disabled value="">Выберите один из вариантов</option>
            <option :value="value.id" v-for="(value) in rubrics" :key="value.id">{{value.name}}</option>
          </select>
          <p class="form_error">{{errorClient.rubric_id}}</p>          
        </div>
        <div v-for="(value, index) in counter" :key="index">
          <div class="form_row">
            <label for="" class="form_label">Изображения</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input type="file" :name="index+'[]'" multiple>          
          </div>
          <div class="form_row">
            <label for="" class="form_label">Текст статьи</label>
            <textarea name="text[]" class="form_input" id="" cols="30" rows="10"></textarea>          
          </div>
        </div>
        <div class="form_row" v-on:click = "incrementCounter" title="добавить блок"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="black" width="36px" height="36px"><g><rect fill="none" height="24" width="24"/></g><g><path d="M14,10H3v2h11V10z M14,6H3v2h11V6z M18,14v-4h-2v4h-4v2h4v4h2v-4h4v-2H18z M3,16h7v-2H3V16z"/></g></svg></div>         
        <button type="button" v-on:click="sendInsertForm" class="form_button">Создать статью</button>  
    </form>
    <div class="server_responce_error">
      <p v-for="(value, index) in errorRequest" :key="index">{{value}}</p>
    </div>         
  </div>   
</template>

<script>
import {mapGetters} from 'vuex'
import  { mapActions}   from 'vuex'; 

export default {
  name: 'InsertArticle',
  metaInfo() {
		return {
			title: this.domain + ' - Кабинет: Добавление статьи',
			meta: [
			{ vmid: 'description', name: 'description', content: "Добавление статьи" + this.domain }
			]
		}
	},
  created(){        
    this.$store.dispatch('user/rubrics');      
  },
  data () {
    return {
      counter: [1],       
      countRecursion: 0,
      errorRequest: [],
      errorClient: {},
      title: {
        value: '',
        inputErrClass: ''
      },
      subtitle: {
        value: '',
        inputErrClass: ''
      },
      rubric_id: {
        value: '',
        inputErrClass: ''
      }
    }
  },
  computed: {
    ...mapGetters('user', {
      user: 'user'
    }),
    ...mapGetters('user', {
      rubrics: 'rubrics'
    }),
    ...mapGetters('global', {
      domain: 'domain'
    })     
  },
  methods: {
    ...mapActions('user', ['cleanUser']),
    incrementCounter(){
      this.counter.push('');
    },
    async sendInsertForm(){     
      if(/^.{1,}$/g.test(this.title.value)) {
        this.$delete(this.errorClient, 'title'); 
        this.title.inputErrClass = '';         
      }else{
        this.$set(this.errorClient, 'title', "Заголовок не может быть пустым"); 
        this.title.inputErrClass = 'form_input_error';
      }
      if(/^.{1,}$/g.test(this.subtitle.value)) {
        this.$delete(this.errorClient, 'subtitle'); 
        this.subtitle.inputErrClass = '';         
      }else{
        this.$set(this.errorClient, 'subtitle', "Подзаголовок не может быть пустым"); 
        this.subtitle.inputErrClass = 'form_input_error';
      }
      if(this.rubric_id.value != '') {
        this.$delete(this.errorClient, 'rubric_id'); 
        this.rubric_id.inputErrClass = '';         
      }else{
        this.$set(this.errorClient, 'rubric_id', "Выберите рубрику"); 
        this.rubric_id.inputErrClass = 'form_input_error';
      }
      if (Object.keys(this.errorClient).length === 0 && this.errorClient.constructor === Object) {
        let form = this.$refs.formInsertArticle;
        let formData = new FormData(form);  
        formData.append('author', this.user.id);  
        this.$http.post('insert_article.php', formData)
        .then(response => response.json())
        .then(data => {  
          if (data.res) {
            this.countRecursion = 0;
            if (data.error) {
              this.errorRequest=data.error;
            }else{
              this.errorRequest = [];
              this.$router.push({path: '/cabinet'});
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
 
<style>
.insert_article_title{
  margin: 0 0 20px 0;
  text-transform: uppercase;   
}
.server_responce_error{
  color: red;
  margin: 20px 0 0 0;
}
.form_error{
  color: red;
}
.form_input_error{
  border: 1px solid red;
}
</style>