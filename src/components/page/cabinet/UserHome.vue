<template>
  <div v-if="user">       
      <router-link class="cabinet_insert_link"
        tag="div"
        :to="'/cabinet/insert'"						 		 
        >
        <div class="cabinet_insert_link_content">
          <span>
            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="black" width="36px" height="36px"><g><rect fill="none" height="24" width="24"/></g><g><g/><g><path d="M17,19.22H5V7h7V5H5C3.9,5,3,5.9,3,7v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-7h-2V19.22z"/><path d="M19,2h-2v3h-3c0.01,0.01,0,2,0,2h3v2.99c0.01,0.01,2,0,2,0V7h3V5h-3V2z"/><rect height="2" width="8" x="7" y="9"/><polygon points="7,12 7,14 15,14 15,12 12,12"/><rect height="2" width="8" x="7" y="15"/></g></g></svg>
          </span>
          <span>добавить статью</span>
        </div>
			</router-link>
      <router-link class="reference_article"
        tag="p"
        :to="'/cabinet/reference'"						 		 
        >
        правила оформления статьи 
      </router-link>
      <div class="user_home_article" v-for="(value) in articlesUser" :key="value.id">
        <div class="user_home_article_container_first">
          <p class="user_home_article_id">id: {{value.id}}</p>
          <router-link
          class="user_home_article_title"
          tag="div"
          :to="'/article/' + value.id"           			 
          >
          <p>{{value.title}}</p>
          </router-link>
        </div>
        <div class="user_home_article_container_second">             		 
          <span v-if="value.visibility != 0" title="статья отображается"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
          </span>
          <span v-else title="статья не отображается"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0zm0 0h24v24H0zm0 0h24v24H0zm0 0h24v24H0z" fill="none"/><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg></span> 
          <router-link  
          class="user_home_article_icon" 
          :to="`/cabinet/article/update/${value.id}`"						 		 
          >          		 
          <span title="редактировать статью"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></span>
          </router-link> 
          <div class="user_home_delete_article"  title="удалить статью">
            <img @click="deleteArticle" :data-id="value.id" :key="value.id" src="./../../../assets/img/delete-black-24dp.svg" alt="">             
          </div>
        </div>
      </div>         
  </div>   
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'UserHome',
  metaInfo() {
		return {
			title: this.domain + ': Кабинет пользователя',
			meta: [
			{ vmid: 'description', name: 'description', content: "Кабинет пользователя" + this.domain }
			]
		}
	},
  created(){        
    this.$store.dispatch('user/articlesUser');      
  },
  data(){
    return {       
      countRecursion: 0
    }
  },
  computed: {
    ...mapGetters('user', {
      user: 'user'
    }),
    ...mapGetters('user', {
      articlesUser: 'articlesUser'
    }),
    ...mapGetters('global', {
      domain: 'domain'
    })
  },
  methods: {
    deleteArticle(e){       
      let formData = new FormData(); 
      formData.append('id_article', e.target.dataset.id);       
      this.$http.post('delete_article.php', formData)
        .then(response => response.json())
        .then(data => {           
          if (data.res) {
            this.countRecursion = 0;
            this.$store.dispatch('user/deleteArticle', e.target.dataset.id);          
          }else{
            this.cleanUser();
            this.$router.push({path: '/home'});
          }                              
        })
        .catch(()=>{
          if (this.countRecursion==0) { 
            this.countRecursion++;    
            setTimeout(() => {
              this.deleteArticle(e);
            }, 500);           
          }                             
        }); 
    }
  }
}
</script>
 
<style scoped>
.cabinet_insert_link{
  display: inline-block;
  font-weight: bold;
  margin: 0 0 25px 0;
}
.cabinet_insert_link:hover{
  cursor: pointer;
  color: red;
}
.cabinet_insert_link_content{
  display: flex;
  align-items: center;
}
.user_home_article{ 
	font-size: 18px;    
  padding: 5px;
  border: 1px solid #d1d1d1;      
  display: flex;   
  align-items: center;
}
.user_home_article_container_first{   
	display: flex;	 
	width: 85%;
	align-items: center;
}
.user_home_article_container_second{   
	display: flex;
	justify-content: space-between;
	width: 15%;
	align-items: center;
  padding: 0 10px 0 10px;
}
.user_home_article_id{
  width: 15%;
  padding: 0 15px 0 0;  
  white-space: nowrap; 
}
.user_home_article_icon:hover,
.user_home_delete_article:hover{
  cursor: pointer;
  background: slategray;
}
.user_home_article_title{   
  width: 85%;  
}
.user_home_article_title:hover{
  cursor: pointer;
  text-decoration: underline;
}
.reference_article{
  text-align: right;
  margin: 0 0 25px 0;
} 
.reference_article:hover{
  cursor: pointer;
}
@media screen and (max-width: 870px){
  .user_home_article_container_first{
    display: block;
  }
  .user_home_article_id{
    font-weight: bold;
    padding: 0 0 10px 0;
  }   
  .user_home_article{
    align-items: flex-start;
  }
}
@media screen and (max-width: 680px){
  .user_home_article{
    display: block;
  }
  .user_home_article_container_first{
    width: 100%;
    margin: 0 0 15px 0;
    display: flex;
    align-items: flex-start;
  }
  .user_home_article_container_second{
    width: 100%;
    padding: 0;
    justify-content: flex-end;
  } 
  .user_home_article_container_second *{
    margin: 0 0 0 20px;
  }
}
@media screen and (max-width: 400px){
  .user_home_article_container_first{
    display: block;
  }
} 
</style>