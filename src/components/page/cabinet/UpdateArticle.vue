<template>
  <div v-if="user">       
    <h3 class="insert_article_title">Редактирование статьи</h3> 
    <form novalidate="true" ref="formUpdateArticle" enctype="multipart/form-data">         
      <div class="form_row">
        <label for="" class="form_label">Заголовок</label>
        <input type="text" v-model.trim="clone.title" @input="validation" name="title" class="form_input" :class="title.inputErrClass">
        <p class="form_error">{{errorClient.title}}</p>
      </div>
      <div class="form_row">
        <label for="" class="form_label">Подзаголовок</label>
        <input type="text" v-model.trim="clone.subtitle" @input="validation" name="subtitle" class="form_input" :class="subtitle.inputErrClass">
        <p class="form_error">{{errorClient.subtitle}}</p>          
      </div>
      <div class="form_row">
        <label for="" class="form_label">Рубрика</label>
        <select class="form_input" v-model.trim="clone.rubric_id" name="rubric_id" id="">
          <option disabled value="">Выберите один из вариантов</option>
          <option :value="value.id" v-for="(value) in rubrics" :key="value.id">{{value.name}}</option>
        </select>             
      </div> 
      <input type="hidden" name="author" :value="user.id">
      <div v-for="(value, index) in clone.text" :key="index">
        <div class="form_row">
          <label for="" class="form_label">Изображения</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
          <input type="file" :name="index+'[]'" multiple>          
        </div>
        <div>
          <div class="wrap_imgs" v-if="value.type == 'img'">
            <div class="container_img" v-for="(val, index) in value.value" :key="index">
              <!-- для разработки -->
              <!-- <img :src="require('./../../../assets/img_articles/'+val)" class="img" alt=""> -->
              <!-- для prodaction -->
              <img :src="'/img/'+val" class="img" alt="">
               
            </div>
          </div>
        </div> 
        <div class="form_row">
          <label for="" class="form_label">Текст статьи</label>
          <textarea v-model="txt[index]"  name="text[]" class="form_input" id="" cols="30" rows="10">          
            </textarea>          
        </div>
      </div>      
      <div>
        <div v-for="(value, index) in counter" :key="index">
            <div class="form_row">
            <label for="" class="form_label">Изображения</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
            <input type="file" :name="((clone.text.length)+ index) +'[]'" multiple>          
          </div>
          <div class="form_row">
            <label for="" class="form_label">Текст статьи</label>
            <textarea name="text[]" class="form_input" id="" cols="30" rows="10"></textarea>          
          </div>              
        </div>
      </div>      
      <div class="form_row" v-on:click = "incrementCounter" title="добавить блок"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="black" width="36px" height="36px"><g><rect fill="none" height="24" width="24"/></g><g><path d="M14,10H3v2h11V10z M14,6H3v2h11V6z M18,14v-4h-2v4h-4v2h4v4h2v-4h4v-2H18z M3,16h7v-2H3V16z"/></g></svg></div>          
      <button type="button" v-on:click="sendUpdateForm" class="form_button">Внести изменения</button> 
    </form>
    <div class="server_responce_error">
      <p v-for="(value, index) in errorRequest" :key="index">{{value}}</p>
    </div>     
  </div>   
</template>

<script>
import {mapGetters} from 'vuex'
import {mapActions} from 'vuex'

export default {
  name: 'UpdateArticle',
  metaInfo() {
		return {
			title: this.domain + ' - Кабинет: Редактирование статьи',
			meta: [
			{ vmid: 'description', name: 'description', content: "Редактирование статьи" + this.domain }
			]
		}
	},
  async created(){        
    await this.$store.dispatch('user/oneArticle', this.$route.params.id_article);
    this.$store.dispatch('user/rubrics');                    
  },
  data() {
    return {       
      counter: 0,       
      countRecursion: 0,
      errorRequest: [],      
      errorClient: {},
      title: {         
        inputErrClass: ''
      },
      subtitle: {        
        inputErrClass: ''
      }           
    }
  },
  computed: {
    ...mapGetters('user', {
      user: 'user'
    }),
    ...mapGetters('user', {
      oneArticle: 'oneArticle'
    }),     
    ...mapGetters('user', {
      rubrics: 'rubrics'
    }),
     ...mapGetters('global', {
      domain: 'domain'
    }),
    clone(){        
      let newObj = {...this.oneArticle};
      return newObj;
    },
    txt(){
      let ar = [];
      for (let i = 0; i < this.clone.text.length; i++) {
        if (this.clone.text[i].type =='text') {
          ar.push(this.clone.text[i].value);
        }else{
          ar.push('');
        }                 
      }
      return ar;       
    } 
  },
  methods: {
    ...mapActions('user', ['cleanUser']),
    incrementCounter(){
      this.counter++;                
    },
    validation(e){
      if (e.target.getAttribute('name') == 'title') {
        if(/^.{1,}$/g.test((e.target.value).trim())) {
          this.$delete(this.errorClient, 'title'); 
          this.title.inputErrClass = '';         
        }else{
          this.$set(this.errorClient, 'title', "Заголовок не может быть пустым"); 
          this.title.inputErrClass = 'form_input_error';
        }         
      } 
      if (e.target.getAttribute('name') == 'subtitle') {
        if(/^.{1,}$/g.test((e.target.value).trim())) {
          this.$delete(this.errorClient, 'subtitle'); 
          this.subtitle.inputErrClass = '';         
        }else{
          this.$set(this.errorClient, 'subtitle', "Подзаголовок не может быть пустым"); 
          this.subtitle.inputErrClass = 'form_input_error';
        }         
      }       
    },
    sendUpdateForm() {              
      if (Object.keys(this.errorClient).length === 0 && this.errorClient.constructor === Object) {
        let form = this.$refs.formUpdateArticle;
        let formData = new FormData(form); 
        formData.append('id', this.$route.params.id_article);     
        this.$http.post('update_article.php', formData)
        .then(response => response.json())
        .then(data => {  
          if (data.res) {
            this.countRecursion = 0;
            if (data.error) {
              this.errorRequest=data.error;
            }else{
              this.errorRequest = [];
              this.$router.push({path: `/article/${this.$route.params.id_article}`});
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
              this.sendUpdateForm();
            }, 500);           
          }                             
        });          
      }              
    }
  }
}
</script>
 
<style scoped>
input{
  outline: none;
}
.wrap_imgs {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  -webkit-display: flex;
  -webkit-flex-wrap: wrap;
  -moz-display: flex;
  -moz-flex-wrap: wrap;
}
.container_img {
  width: calc(25% - 10px);
  margin: 0 10px 10px 0;
}
.img {
  width: 100%;
  max-width: 100%;
  height: auto;
  object-fit: cover;
  object-position: center;
}
</style>