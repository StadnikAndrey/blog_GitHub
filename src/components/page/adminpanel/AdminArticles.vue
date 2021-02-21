<template>
  <div>
    <h3 class="admin_child_title">Управление статьями</h3>
    <div class="admin_one_article" v-for="(value, i) in articles" :key="value.id">
      <p class="admin_one_article_id">id: <span class="admin_one_article_id_num">{{value.id}}</span></p>
      <router-link    
        class="admin_one_article_title"
        tag="p"
        :to="'/article/' + value.id"            					 		 
        >
        {{value.title}}
      </router-link>
      <form ref="frm" class="admin_one_article_visibility">
        <input @click="form_submit(i, $event)" :data-id="value.id" name="check" type="checkbox" :checked="value.visibility==1" title="видимость статьи">
      </form>
    </div>      
  </div>       
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'AdminArticles',
  metaInfo() {
		return {
			title: this.domain + '- Админпанель: Статьи',
			meta: [
			{ vmid: 'description', name: 'description', content: "Управление всеми статьями" + this.domain }
			]
		}
	},   
  created() {
    this.$store.dispatch('articles/allArticlesForAdmin');
  },
  data() {
    return {
      countRecursion: 0             
    }
  },
  computed: {
    ...mapGetters('articles', {
			articles: 'allArticlesForAdmin'
		}),
    ...mapGetters('global', {
      domain: 'domain'
    })     
  },
  methods: {
    form_submit(i, e) {              
      let formData = new FormData(this.$refs.frm[i]);
      formData.append('id_article', e.target.dataset.id);      
      this.$http.post('articles_visibility.php', formData)
      .then(() => this.countRecursion = 0)
      .catch(()=>{
        if (this.countRecursion == 0) { 
          this.countRecursion++;    
          setTimeout(() => {
            this.form_submit(i, e);
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
.admin_one_article{	 
	font-size: 18px;    
  padding: 5px;
  border: 1px solid #d1d1d1;      
  display: flex;   
  align-items: center;
  margin: 0 0 10px 0;
}
.admin_one_article_id{
	width: 10%;	
	margin: 0 15px 0 0; 
}
.admin_one_article_id_num{
	font-weight: bold;
}
.admin_one_article_title{
	width: 80%;	 
}
.admin_one_article_title:hover{
  cursor: pointer;
  text-decoration: underline;
} 
.admin_one_article_visibility{
  margin: 0 20px;
}
@media screen and (max-width: 830px){
  .admin_news_form{
      max-width: 100%;
  }
  .admin_news_form_row {
  width: 84%;
  }
}
</style>