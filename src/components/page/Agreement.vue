<template>
   <div>
    <h1 class="title_page">Безопасность и конфиденциальность</h1>
    <p> Все ваши данные обрабатываются автоматически и не передаются третьим лицам.</p>
   </div>   
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: 'Agreement',
   metaInfo() {
		return {
			title: this.domain + ': Пользовательское соглашение',
			meta: [
			{ vmid: 'description', name: 'description', content: "Пользовательское соглашение сайта" + this.domain }
			]
		}
	},
  computed: {
     ...mapGetters('global', {
      domain: 'domain'
    })
  }
}
</script>
 
<style>
.title_page{
  margin: 50px 0 25px 0;
  font-size: 21px;
} 
</style>