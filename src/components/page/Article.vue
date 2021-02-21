<template>
   <div class="article_wrap" v-if="article">           
       <div class="article_meta">
			<div>
				<router-link class="article_meta_topic" 
					tag="a"
					:to="'/rubric/' + article.rubric_url + '/' + article.rubric_id"
					active-class="tag_active"				 
					>
					{{article.rubric_name}}
				</router-link> 
			</div>					 
			<div>
				<router-link class="article_meta_author"
					tag="a"
					:to="'/author/' + article.author_login + '/' + article.author"
					active-class="tag_active"		 
					>
					{{ article.author_login }} 
				</router-link>
			</div>				 
		</div>
        <h1 class="article_title">{{article.title}}</h1>
		<h2 class="article_subtitle">{{article.subtitle}}</h2>
		<div class="article_text">   
			<div v-for="(value, index) in article.text" :key="index">				   
				<div v-if="value.type == 'text'" v-html="value.value"></div>  
				<div v-else> 
				<!-- для разработки -->
				<!-- <img class="img_article" v-for="(val, index) in value.value" :key="index" :src="require('./../../assets/img_articles/'+val)" alt="">			  -->
				<!-- для prodaction -->
				<img class="img_article" v-for="(val, index) in value.value" :key="index" :src="'/img/'+val" alt="">				 
				</div>  				    
			</div>			 
		</div>		 
   </div>   
</template>

<script>
import {mapGetters} from 'vuex';

export default {
	name: 'Article',
	metaInfo() {
		return {
			title: this.article['title'],
			meta: [
			{ vmid: 'description', name: 'description', content: this.article['subtitle'] }
			]
		}
	},
	async created() {
		await this.$store.dispatch('articles/article', {'article': this.$route.params.id});	       
	},
	computed: {
		...mapGetters('articles', {
			article: 'article'
		}) 
	},
	watch: {
		article() {
			if (!this.article) {
				this.$router.push({path: '/home'});
			} 
		}
	}
}
</script>
 
<style>
.article_wrap{
	padding: 60px 0 0 0;
}
.article_title{	    
    font-size: 28px;     
    margin: 10px 0 25px 0;
    transition: .5s;
	word-wrap: break-word;
} 
.article_subtitle{	  
	font-size: 20px;	 
	color: #828282;
	word-wrap: break-word;
}
.article_text{	 
	font-size: 18px;
	margin: 25px 0 0 0 ;
	line-height: 1.44;
	word-wrap: break-word;
}
.img_article {     
	display: block;
	margin: 10px auto;     
    max-width: 100%;
}
@media screen and (max-width: 740px){
	.article_title{	   
		font-size: 23px;          
		margin: 10px 0 15px 0;     
	} 
}
</style>