<template>
  <div>       
    <router-link class="admin_rubrics_insert_link"
      tag="div"
      :to="'/adminpanel/rubrics/adminpanel/rubrics/insert'"						 		 
      >
      <div class="admin_rubrics_insert_link_content">
        <span>
          <svg viewBox="0 0 24 24" fill="black" width="36px" height="36px"><path d="M0 0h24v24H0z" fill="none"/><path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
        </span>
        <span>добавить рубрику</span>
      </div>
    </router-link>
    <div class="admin_one_rubric" v-for="(value) in rubrics" :key="value.id">
      <p class="admin_one_rubric_id">id: <span class="admin_one_rubric_id_num">{{value.id}}</span></p>
      <p class="admin_one_rubric_title">{{value.name + '/' +value.url}}</p>
      <router-link            
        :to="`/adminpanel/rubrics/adminpanel/rubrics/update/${value.id}`"						 		 
        >          		 
        <span title="редактировать рубрику"><svg viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg></span>
      </router-link> 
    </div>      
  </div>       
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'AdminRubricsMain',
  metaInfo() {
		return {
			title: this.domain + ' - Админпанель:Рубрики',
			meta: [
			{ vmid: 'description', name: 'description', content: "Управление рубриками" + this.domain }
			]
		}
	},   
  created() {
    this.$store.dispatch('user/rubrics');
  },
  computed: {
    ...mapGetters('user', {
      rubrics: 'rubrics'
    }),
    ...mapGetters('global', {
      domain: 'domain'
    })
  }
}
</script>
 
<style>
.admin_rubrics_insert_link{
  display: inline-block;
  font-weight: bold;
  margin: 0 0 25px 0;
}
.admin_rubrics_insert_link:hover{
  cursor: pointer;
  color: red;
}
.admin_rubrics_insert_link_content{
  display: flex;
  align-items: center;
}
.admin_one_rubric{	 
	font-size: 18px;    
  padding: 5px;
  border: 1px solid #d1d1d1;      
  display: flex;   
  align-items: center;
  margin: 0 0 10px 0;
}
.admin_one_rubric_id{
	width: 10%;	
	margin: 0 15px 0 0; 
}
.admin_one_rubric_id_num{
	font-weight: bold;
}
.admin_one_rubric_title{
	width: 80%;	 
} 
</style>