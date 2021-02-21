<template>
   <div>         
		<div class="tags">			 
			<h3 class="tags_title">Выбери что почитать</h3>
			<p class="tags_content">
				<router-link class="tag" v-for="(value) in rubrics" :key="value.rubric_id" 
				tag="span"
				:to="'/rubric/' + value.rubric_url + '/' + value.rubric_id"
				active-class="tag_active"				 
				>
				{{value.rubric_name}} /</router-link>  
			</p>
		</div>   
		<div class="articles" v-if="articles != ''">
			<router-link v-for="(value) in articles" :key="value.id"
			:to="'/article/' + value.id"
			tag="p"
			class="article"
			>		 
				<p class="article_go">GO!</p>
				<div class="article_meta">
					<div>
						<router-link class="article_meta_topic" 
						tag="a"
						:to="'/rubric/' + value.rubric_url + '/' + value.rubric_id"
						active-class="tag_active"				 
						>
						{{value.rubric_name}} </router-link> 
					</div>					 
					<div>
						<router-link class="article_meta_author"
							tag="a"
							:to="'/author/' + value.author_login + '/' + value.author"
							active-class="tag_active"		 
							>
							{{ value.author_login }} 
						</router-link>
					</div>				 
				</div>
				<h3 class="articles_title">{{value.title}}</h3>
				<p class="articles_subtitle">{{value.subtitle}}</p>		 	
			</router-link>			 			 
		</div> 
		<div v-else class="article_empty">Статей по вашему запросу нет!</div> 	 
   </div>
</template>

<script>
import {mapGetters} from 'vuex' 
  
export default {
	name: 'Home',
	metaInfo () {
      return {
        title: this.domain,
        meta: [
          { vmid: 'description', name: 'description', content: this.domain + ": актуальные статьи на разные темы" }
        ]
      }
	},
	data() {
		return {
			listener: this.handler
		}		 
	},
	created(){	 
		// постраничная подгрузка статей при скролле		 
		window.addEventListener('scroll', this.listener); 
		// получение рубрик статей которые отображаются
		this.$store.dispatch('articles/getRubrics');
		// отправка запроса при перезагрузке если была выборка по автору или рубрике
		let param = { [this.$route.params.filter] : this.$route.params.id };
		this.$store.dispatch('articles/filter', param);		 
		this.$store.dispatch('articles/countPages');		 	 
	},
	destroyed(){		 
		window.removeEventListener('scroll', this.listener);		 
	},     
	watch: {
		$route(to) {
			let param = {[to.params.filter] : to.params.id};
			this.$store.dispatch('articles/filter', param);			  
			this.$store.dispatch('articles/countPages');			 		 			 
		}
	},
	computed: {
		...mapGetters('articles', {
			articles: 'articles'
		}),
		...mapGetters('articles', {
			page: 'page'
		}),
		...mapGetters('articles', {
			rubrics: 'rubrics'
		}),
		...mapGetters('articles', {
			countpages: 'countpages'
		}),
		...mapGetters('articles', {
			blockRequest: 'blockRequest'
		}),
		...mapGetters('global', {
			domain: 'domain'
		}) 	  
	},
	methods: {		 
		handler(){
			if( document.documentElement.scrollHeight - document.documentElement.scrollTop - document.documentElement.clientHeight < 5 && this.page < this.countpages && !this.blockRequest){
				//setTimeout чтобы дождаться изменения DOM и исключить дублирование id статей (ошибка в консоли). При разработке можно без setTimeout, а на сервере выдает ошибку (Chrome)
				setTimeout(() => {  
					this.$store.dispatch('articles/incrementPage');
				}, 50); 				 	 		 			 	 			 
			}
		}		 	 
	}
}	 
</script>
 
<style>
.tags{
	margin: 100px 0;
}
.tags_title{	    
	font-size: 12px;
	text-transform: uppercase;
	font-weight: bold;
	text-align: center;	 
}
.tags_content{	  
	font-size: 28px;
	max-width: 600px;
	text-align: center;
	margin: 15px auto 0 auto;	 
	line-height: 1.2;
}
.tag:hover{
	color:red;
	cursor:pointer;
}  
.article{
	border-top: 4px solid;
	padding: 50px 0;
	position: relative;
	overflow: hidden;
}
.article:hover{
	cursor: pointer;
} 
.articles_title{	   
    font-size: 28px;      
    margin: 10px 0 25px 0;
    transition: .5s;
}
.articles_subtitle{	  
	font-size: 20px;
}
.article_meta{
	display: flex;
}
.article_meta_author,
.article_meta_topic{
	display: block;
	text-decoration: none;	
	color: #000; 
	font-weight: bold;
	padding: 10px;
	border: 1px solid;	 
	transition: .5s;
}
.article_meta_topic{
	margin: 0 20px 0 0;
}
.article_meta_author:hover,
.article_meta_topic:hover{
	border: 1px solid red;
	cursor: pointer;
}
.article_go{	 
	position: absolute;	 
	left: -50%;
	bottom: 5%;	 
}
.article:hover .article_go{	  
	transition: 2s;
	transition-delay: .5s;
	display: block;	 
	left: 90%;
}
.button_articles{
	display: block;
	padding: 10px 60px;
	margin: 50px auto 0 auto;
	background: #000;
	color: #fff;
	font-family: 'Raleway', sans-serif; 
	font-size: 20px;
	border: none;
	transition: .2s;
}
.button_articles:hover{
	cursor: pointer;
	background-color: #828282;
}
.article_empty{
	margin: 100px 0 0 0;
}
.tag_active{
	color: red;
}
@media screen and (max-width: 860px){	 
	.tags_content{
		font-size: 25px;
	}
	.article{
		padding: 20px 0;
	}	    
}
@media screen and (max-width: 620px){	 
	.tags_content {
		font-size: 21px;
	}	 
	.articles_title {     
		font-size: 21px;     
		margin: 10px 0 15px 0;     
	}
	.articles_subtitle {     
		font-size: 18px;
	}
}
@media screen and (max-width: 480px){
	.article_meta{
		display: block;
	}
	.article_meta_author,
	.article_meta_topic {     
		padding: 5px;     
		display: inline-block;
	}
	.article_meta_topic {
		margin: 0 0 10px 0;
	}
	.tags {
		margin: 60px 0;
	}
}
</style>