<template>
   <div>
      <h1 class="title_page">О сайте</h1>
      <ul class="about_ul">
        <li>Это публичный блог созданный для публикации материалов связаннных с Digital.</li>
        <li>Вы можете размещать свои статьи здесь предварительно зарегистрировавшись.</li>
      </ul>              
   </div>       
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: 'Ab',
  metaInfo() {
		return {
			title: this.domain + ': О сайте',
			meta: [
			{ vmid: 'description', name: 'description', content: "Информация о сайте" + this.domain }
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
.about_ul li{
  margin: 0 0 20px 0;
}
</style>